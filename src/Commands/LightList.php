<?php

namespace Angorb\HueCli\Commands;

use Angorb\HueCli\Environment;
use Angorb\HueCli\Strings\Pattern;

class LightList extends AbstractCommand
{
    protected function do($env)
    {
        foreach ($env->lights as $lightId => $light) {
            $lights[] = [
                '<bold>ID</bold>' => $lightId,
                '<bold>Name</bold>' => $light->getName()
            ];
        }
        $env->console->out(\sprintf(
            Pattern::CMD_LIST,
            \count($env->lights)
        ));
        $env->console->table($lights);
    }
}
