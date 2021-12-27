<?php

namespace Angorb\HueCli\Commands;

class Info extends AbstractCommand
{
    protected function do($env)
    {
        $target = empty($this->target)
            ? \array_keys($env->lights)
            : [$this->target];
        $info = [];
        foreach ($target as $key) {
            $lightInfo = [
                'Type'              => $env->lights[$key]->getType(),
                'Model ID'          => $env->lights[$key]->getModelId(),
                'Software Version'  => $env->lights[$key]->getSoftwareVersion(),
                'On'                => $env->lights[$key]->isOn() ? 'Yes' : 'No',
                'Alert'             => $env->lights[$key]->getAlert(),
                'Brightness'        => $env->lights[$key]->getBrightness(),
                'Color Mode'        => $env->lights[$key]->getColorMode(),
            ];

            $colorInfo = [];
            if (false === empty($env->lights[$key]->getColorMode())) {
                $colorInfo = [
                    'Hue'           => $env->lights[$key]->getHue(),
                    'Saturation'    => $env->lights[$key]->getSaturation(),
                    'X'             => $env->lights[$key]->getXY()['x'],
                    'Y'             => $env->lights[$key]->getXY()['y'],
                    '<red>Red</red>'        => \abs($env->lights[$key]->getRGB()['red']),
                    '<green>Green</green>'  => \abs($env->lights[$key]->getRGB()['green']),
                    '<blue>Blue</blue>'     => \abs($env->lights[$key]->getRGB()['blue']),
                    'Effect'        => $env->lights[$key]->getEffect(),
                    'Color Temp'    => $env->lights[$key]->getColorTemp(),
                ];
            }
            $env->console->out(
                \sprintf(
                    '<bold>Light ID #%u:</bold> <yellow>%s</yellow>',
                    $key,
                    $env->lights[$key]->getName()
                )
            );
            $env->console->table([$lightInfo]);
            // print color info, if available
            if (\false === empty($colorInfo)) {
                $env->console->dim('Color Info:');
                $env->console->table([$colorInfo]);
            }
            $env->console->br();
        }
    }
}
