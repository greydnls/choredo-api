<?php

declare(strict_types=1);

namespace Choredo {
    const DAYS_OF_WEEK              = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    const SHORT_DATA_FIELD_MAX_SIZE = 255;

    const REQUEST_HANDLER_CLASS = 'request-handler-class';
    const REQUEST_VARIABLES     = 'request-url-vars';
    const REQUEST_PAGINATION    = 'page';
    const REQUEST_RESOURCE      = 'request-resource';
    const REQUEST_SORT          = 'sort';
    const REQUEST_FILTER        = 'filter';
    const REQUEST_FAMILY        = 'request-family';

    const HEX_COLOR_REGEX = '/(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i';

    function getBaseUrl(): string
    {
        $url = $_SERVER['HTTPS'] ? 'https://' : 'http://';
        $url .= $_SERVER['SERVER_NAME'];
        if ($_SERVER['SERVER_PORT']) {
            $url .= ':' . $_SERVER['SERVER_PORT'];
        }

        return $url;
    }
}
