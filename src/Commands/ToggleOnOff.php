<?php

namespace Angorb\HueCli\Commands;

use Phue\Light;

class ToggleOnOff extends AbstractCommand
{

    private Light $light;

    protected function do($env)
    {
        $this->light = $env->lights[$this->target];
        return $this;
    }

    public function on()
    {
        $this->light->setOn(\true);
    }

    public function off()
    {
        $this->light->setOn(\false);
    }

    public function switch()
    {
        $state = $this->light->isOn();
        $this->light->setOn(!$state);
    }
}
