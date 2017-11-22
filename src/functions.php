<?php

namespace Choredo {

    const DAYS_OF_WEEK = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    const SHORT_DATA_FIELD_MAX_SIZE = 255;

    // Request Attributes
    const REQUEST_HANDLER_CLASS = 'request-handler-class';
    const REQUEST_VARIABLES = 'request-url-vars';
    const REQUEST_PAGINATION = 'page';
    const REQUEST_SORT = 'sort';

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
