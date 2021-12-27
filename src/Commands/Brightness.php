<?php

namespace Angorb\HueCli\Commands;

use Angorb\HueCli\Strings\Message;
use Angorb\HueCli\Strings\Pattern;

class Brightness extends AbstractCommand
{
    public const MAX = 254;
    public const MIN = 0;

    protected function do($env)
    {
        //validate value
        $value = (int) $env->console->arguments->get('value');
        if (\false === \is_numeric($value)) {
            $env->console->error(Message::CMD_BRIGHTNESS_INVALID_TYPE);
            exit();
        }
        // enforce bounds
        if ($value < self::MIN) {
            $value = self::MIN;
        } elseif ($value > self::MAX) {
            $value = self::MAX;
        }

        $id = $env->console->arguments->get('target');
        $env->lights[$id]->setBrightness($value);
        $env->console->out(
            \sprintf(
                Pattern::CMD_BRIGHTNESS,
                $id,
                $env->lights[$id]->getName(),
                $value,
                self::asPercent($value)
            )
        );
    }

    public static function asPercent(int $value)
    {
        return round(($value / self::MAX) * 100);
    }
}
