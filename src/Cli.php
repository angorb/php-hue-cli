<?php

namespace Angorb\HueCli;

use Angorb\HueCli\Commands\Brightness;
use Angorb\HueCli\Commands\ColorTemp;
use Angorb\HueCli\Commands\Info;
use Angorb\HueCli\Commands\LightList;
use Angorb\HueCli\Commands\Rgb;
use Angorb\HueCli\Commands\ToggleOnOff;
use Angorb\HueCli\Strings\Message;
use Angorb\HueCli\Strings\Pattern;

class Cli
{
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
        private Environment $env
    ) {
        $this->env->console->arguments->add($this->arguments);
        $this->dispatchCommand();
    }

    private function dispatchCommand(): void
    {
        $command = \strtolower($_SERVER['argv'][1]) ?? '';

        // General Commands
        switch ($command) {
            case 'list':
                new LightList($this->env);
                break;
            case 'info':
                new Info($this->env);
                break;
        }
        // Targeted Commands
        $this->validateTarget();
        switch ($command) {
            case 'on':
                (new ToggleOnOff($this->env))
                    ->on();
                break;
            case 'off':
                (new ToggleOnOff($this->env))
                    ->off();
                break;
            case 'toggle':
                (new ToggleOnOff($this->env))
                    ->switch();
                break;
            case 'brightness':
                new Brightness($this->env);
                break;
            case 'rgb':
                new Rgb($this->env);
                break;
            case 'colortemp':
                new ColorTemp($this->env);
                break;
            case 'name': // TODO function
                break;
            case 'alert': // TODO function
                break;
            case 'effect': // TODO function
                break;
            default:
                $this->unknownCommand($command);
                // $this->usage(); // TODO
                break;
        }
    }

    /********************************
     *          UTILITY
     *********************************/

    private function validateTarget(): void
    {
        if (\false === $this->env->console->arguments->defined('target')) {
            $this->env->console->error(Message::VALIDATE_MISSING_TARGET);
            exit();
        }

        $target = $this->env->console->arguments->get('target');
        if (\false === \is_numeric($target)) {
            $this->env->console->error(Message::VALIDATE_NON_NUMERIC_TARGET);
            exit();
        }

        $lights = $this->env->hub->getLights();
        if (\false === \array_key_exists($target, $lights)) {
            $this->env->console->error(Message::VALIDATE_INVALID_TARGET);
            exit();
        }
    }

    private function unknownCommand(?string $command = \null)
    {
        $command = empty($command) ? '' : " \"{$command}\" ";
        $this->env->logger->warning('Unknown command', ['Command' => $command]);
        $this->env->console->error("Unknown command{$command}");
    }
}
