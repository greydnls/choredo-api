<?php

namespace Choredo;

class JsonApiSerializer extends \League\Fractal\Serializer\JsonApiSerializer
{
    public function meta(array $meta)
    {
        if (empty($meta)) {
            return [];
        }

        $result['meta'] = $meta;

        if (array_key_exists('pagination', $result['meta'])) {
            $result['links'] = $result['meta']['pagination']['links'];
            unset($result['meta']['pagination']);
        }

        if (empty($result['meta'])) {
            unset($result['meta']);
        }

        return $result;
    }
}
