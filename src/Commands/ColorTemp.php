<?php

namespace Angorb\HueCli\Commands;

use Angorb\HueCli\Strings\Message;
use Angorb\HueCli\Strings\Pattern;
use Angorb\HueCli\Commands\AbstractCommand;

class ColorTemp extends AbstractCommand
{
    protected function do($env)
    {
        //validate value
        if (\false === \is_numeric($this->value)) {
            $this->console->error(Message::CMD_COLORTEMP_INVALID_TYPE);
            exit();
        }
        // enforce bounds
        if ($this->value < 153) {
            $this->value = 153;
        } elseif ($this->value > 500) {
            $this->value = 500;
        }
        $this->lights[$this->target]->setColorTemp($this->value);
        $this->console->out(
            \sprintf(
                Pattern::CMD_COLOR_TEMP,
                $this->target,
                $this->lights[$this->target]->getName(),
                $this->value
            )
        );
    }
}
