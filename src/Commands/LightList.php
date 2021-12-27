<?php

namespace Angorb\HueCli\Commands;

use Angorb\HueCli\Strings\Pattern;

class LightList extends AbstractCommand
{
    public function __construct($console, $lights)
    {
        foreach ($lights as $lightId => $light) {
            $lights[] = [
                '<bold>ID</bold>' => $lightId,
                '<bold>Name</bold>' => $light->getName()
            ];
        }
        $console->out(\sprintf(
            Pattern::CMD_LIST,
            \count($this->lights)
        ));
        $console->table($lights);
    }
}
