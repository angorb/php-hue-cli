<?php

namespace Angorb\HueCli\Commands;

use Angorb\HueCli\Environment;
use Angorb\HueCli\Strings\Message;

abstract class AbstractCommand
{
    protected $target;
    protected $value;

    public function __construct(
        private Environment $env
    ) {
        // parse CLImate arguments
        $env->console->arguments->parse();
        $this->target = $env->console->arguments->get('target');
        $this->value = $env->console->arguments->get('value');

        $env->logger->debug(
            Message::LOG_CMD_RECEIVED,
            [
                'command' => __CLASS__,
                'target' => $this->target,
                'value' => $this->value
            ]
        );

        $this->do($env);
    }

    public function __destruct()
    {
        exit();
    }

    abstract protected function do($env);
}
