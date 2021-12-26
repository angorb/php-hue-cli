<?php

namespace Angorb\HueCli\Strings;

class Pattern
{
    public const CMD_BRIGHTNESS = '<yellow>Brightness</yellow> of target ID %u (<dim>%s</dim>) set to <bold>%u</bold> (<dim>%u%%</dim>)';
    public const CMD_COLOR_TEMP = 'yellow>Color temp</yellow> of target ID %u (<dim>%s</dim>) set to <bold>%u</bold>';
    public const CMD_LIST = '<bold><green>%u</green></bold> lights';
    public const CMD_RGB_BRIGHTNESS = '<yellow>NOTICE:</yellow> color change adjusted brightness from %u (%u%%) to %u (%u%%)';
    public const CMD_RGB_COLOR = '<yellow>RGB color</yellow> of target ID %u (<dim>%s</dim>) set to <red>%u</red>, <green>%u</green>, <blue>%u</blue>';
}
