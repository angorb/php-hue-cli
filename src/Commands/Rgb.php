<?php

namespace Angorb\HueCli\Commands;

use Angorb\HueCli\Strings\Message;
use Angorb\HueCli\Strings\Pattern;
use Angorb\HueCli\Commands\AbstractCommand;

class Rgb extends AbstractCommand
{
    protected function do($env)
    {
        // validate value
        if (\strlen($this->value) !== 6 || !\ctype_xdigit($this->value)) {
            $this->console->error(Message::CMD_RGB_INVALID_TYPE);
            exit();
        }

        $red    = \hexdec(\substr($this->value, 0, 2));
        $green  = \hexdec(\substr($this->value, 2, 2));
        $blue   = \hexdec(\substr($this->value, 4, 2));

        $env->logger->debug(Message::LOG_RGB_CONVERT, [
            'hex' => $this->value,
            'rgb' => "({$red}, {$green}, {$blue})"
        ]);

        $brightness = $env->lights[$this->target]->getBrightness();
        $env->lights[$this->target]->setRGB($red, $green, $blue);
        $env->console->out(
            \sprintf(
                Pattern::CMD_RGB_COLOR,
                $this->target,
                $env->lights[$this->target]->getName(),
                $red,
                $green,
                $blue
            )
        );

        $newBrightness = $env->lights[$this->target]->getBrightness();
        if ($newBrightness !== $brightness) {
            $env->logger->notice(Message::LOG_RGB_BRIGHTNESS_CHANGE, [
                'Was' => $brightness,
                'Now' => $newBrightness
            ]);
            $env->console->out(
                \sprintf(
                    Pattern::CMD_RGB_BRIGHTNESS,
                    $brightness,
                    Brightness::asPercent($brightness),
                    $newBrightness,
                    Brightness::asPercent($newBrightness),
                )
            );
        }
    }
}
