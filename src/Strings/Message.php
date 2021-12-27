<?php

namespace Angorb\HueCli\Strings;

class Message
{
    # HUE HUB ERRORS
    public const HUB_CONNECT_ERROR = 'Could not connect to hue hub.';

    # COMMAND ERRORS
    public const CMD_BRIGHTNESS_INVALID_TYPE = 'Brightness value must be a number [0-255]';
    public const CMD_COLORTEMP_INVALID_TYPE = 'Color temp value must be a number [153-500]';
    public const CMD_RGB_INVALID_TYPE = 'Value must be a RGB hexadecimal color';
    public const VALIDATE_MISSING_TARGET = 'Must supply a target ID';
    public const VALIDATE_NON_NUMERIC_TARGET = 'Target ID must be numeric';
    public const VALIDATE_INVALID_TARGET = 'Provided target does not exist';

    # LOG MESSAGES
    public const LOG_RGB_CONVERT = 'Converted RGB color';
    public const LOG_RGB_BRIGHTNESS_CHANGE = 'Color change adjusted brightness';
    public const LOG_CMD_RECEIVED = 'Got command';
}
