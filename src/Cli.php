<?php

namespace Angorb\HueCli;

use League\CLImate\CLImate;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;
use Phue\Client;
use Phue\Command\Ping;
use Phue\Transport\Exception\ConnectionException;

class Cli
{
    protected CLImate $console;
    protected Client $hub;
    protected array $lights;

    private array $arguments = [
        'target' => [
            'prefix'       => 't',
            'longPrefix'   => 'target',
            'description'  => 'Target Light ID',
        ],
        'value' => [
            'prefix'       => 'v',
            'longPrefix'   => 'value',
            'description'  => 'The value for the command',
        ],
    ];

    public function __construct(
        private string $ip,
        private string $token,
        protected ?Logger $logger = \null
    ) {
        // set up outputs //
        $this->console = new CLImate();
        $this->console->arguments->add($this->arguments);

        if (\is_null($this->logger)) {
            $this->logger = new Logger(__CLASS__);
            $this->logger->pushHandler(
                new SyslogHandler('hue-cli-php')
            );
        }

        // set up connection to Hue hub
        $this->hub = new Client($ip, $token);
        try {
            $this->hub->sendCommand(new Ping());
            // TODO check auth
            $this->lights = $this->hub->getLights();

            $this->dispatchCommand();
        } catch (ConnectionException $ex) {
            $this->logger->critical($ex->getMessage());
            $this->console->error('Could not connect to hue hub.');
        }
    }

    private function dispatchCommand(): void
    {
        $command = \strtolower($_SERVER['argv'][1]) ?? '';

        // parse CLImate arguments
        $this->console->arguments->parse();
        $target = $this->console->arguments->defined('target') ? $this->console->arguments->get('target') : \null;
        $value = $this->console->arguments->defined('value') ? $this->console->arguments->get('value') : \null;

        $this->logger->debug(
            'Got command',
            [
                'command' => $command,
                'target' => $target,
                'value' => $value
            ]
        );

        switch ($command) {
            case 'list':
                $this->list();
                break;
            case 'info':
                $this->info($target);
                break;
            case 'on':
                $this->validateTarget();
                $this->onState($target, \true);
                break;
            case 'off':
                $this->validateTarget();
                $this->onState($target, \false);
                break;
            case 'toggle':
                $this->validateTarget();
                $this->onState($target);
                break;
            case 'brightness':
                $this->validateTarget();
                $this->brightness($target, $value);
                break;
            case 'rgb':
                $this->validateTarget();
                $this->rgb($target, $value);
                break;
            case 'colortemp': // TODO function
                break;
            case 'name': // TODO function
                break;
            case 'alert': // TODO function
                break;
            case 'effect': // TODO function
                break;
            default:
                $this->unknownCommand($command);
                $this->usage();
                break;
        }
    }

    /********************************
     *          COMMANDS
     *********************************/
    private function alert(int $target, int $value): void
    {
    }

    private function brightness(int $target, int $value): void
    {
        //validate value
        if (\false === \is_numeric($value)) {
            $this->console->error('Brightness value must be a number [0-255]');
            exit();
        }
        // enforce bounds
        if ($value < 0) {
            $value = 0;
        } elseif ($value > 255) {
            $value = 255;
        }
        $lights = $this->hub->getLights();
        $lights[$target]->setBrightness($value);
        $this->console->green('Brightness of target ' . $target . ' set to ' . $value);
        exit();
    }

    private function colortemp(int $target, int $value): void
    {
    }

    private function effect(int $target, int $value): void
    {
    }

    private function info(?int $target): void
    {
        $target = \is_null($target) ? \array_keys($this->lights) : [$target];
        $info = [];
        foreach ($target as $key) {
            $lightInfo = [
                'Type'              => $this->lights[$key]->getType(),
                'Model ID'          => $this->lights[$key]->getModelId(),
                'Software Version'  => $this->lights[$key]->getSoftwareVersion(),
                'On'                => $this->lights[$key]->isOn() ? 'Yes' : 'No',
                'Alert'             => $this->lights[$key]->getAlert(),
                'Brightness'        => $this->lights[$key]->getBrightness(),
                'Color Mode'        => $this->lights[$key]->getColorMode(),
            ];

            $colorInfo = [];
            if (false === empty($this->lights[$key]->getColorMode())) {
                $colorInfo = [
                    'Hue'           => $this->lights[$key]->getHue(),
                    'Saturation'    => $this->lights[$key]->getSaturation(),
                    'X'             => $this->lights[$key]->getXY()['x'],
                    'Y'             => $this->lights[$key]->getXY()['y'],
                    '<red>Red</red>'        => \abs($this->lights[$key]->getRGB()['red']),
                    '<green>Green</green>'  => \abs($this->lights[$key]->getRGB()['green']),
                    '<blue>Blue</blue>'     => \abs($this->lights[$key]->getRGB()['blue']),
                    'Effect'        => $this->lights[$key]->getEffect(),
                    'Color Temp'    => $this->lights[$key]->getColorTemp(),
                ];
            }
            $this->console->out(
                \sprintf(
                    '<bold>Light ID #%u:</bold> <yellow>%s</yellow>',
                    $key,
                    $this->lights[$key]->getName()
                )
            );
            $this->console->table([$lightInfo]);
            // print color info, if available
            if (\false === empty($colorInfo)) {
                $this->console->dim('Color Info:');
                $this->console->table([$colorInfo]);
            }
            $this->console->br();
        }
    }

    private function list(): void
    {
        foreach ($this->lights as $lightId => $light) {
            $lights[] = [
                '<bold>ID</bold>' => $lightId,
                '<bold>Name</bold>' => $light->getName()
            ];
        }
        $this->console->out(\sprintf(
            '<bold><green>%u</green></bold> lights ',
            \count($this->lights)
        ));
        $this->console->table($lights);
    }

    private function name(int $target, int $value): void
    {
    }

    public function onState(int $target, ?bool $state = \null): void
    {
        $state = \is_null($state) ? !$this->lights[$target]->isOn() : $state;
        $this->lights[$target]->setOn($state);
    }

    private function rgb(int $target, string $value): void
    {
        // validate value
        if (\strlen($value) !== 6 || !\ctype_xdigit($value)) {
            $this->console->error('Value must be a RGB hexadecimal color');
            exit();
        }

        $red    = \hexdec(\substr($value, 0, 2));
        $green  = \hexdec(\substr($value, 2, 2));
        $blue   = \hexdec(\substr($value, 4, 2));

        $this->logger->debug('Converted RGB color', [
            'hex' => $value,
            'rgb' => "({$red}, {$green}, {$blue})"
        ]);

        $brightness = $this->lights[$target]->getBrightness();
        $this->lights[$target]->setRGB($red, $green, $blue);
        $newBrightness = $this->lights[$target]->getBrightness();

        if ($newBrightness !== $brightness) {
            $this->logger->notice('Color change adjusted brightness', [
                'Was' => $brightness,
                'Now' => $brightness
            ]);
        }
    }

    private function usage()
    {
    }

    /********************************
     *          UTILITY
     *********************************/

    private function validateTarget(): void
    {
        if (\false === $this->console->arguments->defined('target')) {
            $this->console->error('Must supply a target ID');
            exit();
        }

        $target = $this->console->arguments->get('target');
        if (\false === \is_numeric($target)) {
            $this->console->error('Target ID must be numeric');
            exit();
        }

        $lights = $this->hub->getLights();
        if (\false === \array_key_exists($target, $lights)) {
            $this->console->error('Provided target does not exist');
            exit();
        }
    }

    private function unknownCommand(?string $command = \null)
    {
        $command = empty($command) ? '' : " \"{$command}\" ";
        $this->logger->warning('Unknown command', ['Command' => $command]);
        $this->console->error("Unknown command{$command}");
    }
}
