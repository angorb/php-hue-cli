<?php

namespace Angorb\HueCli;

use Phue\Client;
use Monolog\Logger;
use Phue\Command\Ping;
use League\CLImate\CLImate;
use Psr\Log\LoggerInterface;
use Angorb\HueCli\Strings\Message;
use Monolog\Handler\SyslogHandler;
use Phue\Transport\Exception\ConnectionException;

class Environment
{
    public CLImate $console;

    public Client $hub;
    public string $hub_ip;
    public array $lights;

    public LoggerInterface $logger;

    public function __construct()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../config');
        $dotenv->load();
        $dotenv->required('HUE_HUB_IP')
            ->allowedRegexValues('/([0-9\.]+){7,15}/'); // TODO better
        $dotenv->required('HUE_HUB_TOKEN')
            ->allowedRegexValues('/([\d\w]{40})/'); // TODO generate other tokerns to check
        // set up outputs //
        $this->console = new CLImate();

        $this->logger = new Logger(__CLASS__);
        $this->logger->pushHandler(
            new SyslogHandler('hue-cli-php')
        );


        // set up connection to Hue hub
        $this->hub = new Client($_ENV['HUE_HUB_IP'], $_ENV['HUE_HUB_TOKEN']);
        try {
            $this->hub->sendCommand(new Ping());
            // TODO check auth
            $this->lights = $this->hub->getLights();
        } catch (ConnectionException $ex) {
            $this->logger->critical($ex->getMessage());
            $this->console->error(Message::HUB_CONNECT_ERROR);
        }
    }
}
