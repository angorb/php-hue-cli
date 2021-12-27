<?php

namespace Angorb\HueCli\Commands;

abstract class AbstractCommand
{
    public function __destruct()
    {
        exit();
    }
}
