<?php

namespace Moonshiner\ResourceBundle\Services;

class Resource implements ResourceInterface
{
    protected $resource;

    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'data';

    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public static function collection($collection)
    {
        $className = get_called_class();

        return self::toResponse(array_map(function ($item) use ($className) {
            return (new $className($item))->toArray();
        }, $collection));
    }

    public function toArray()
    {
        if (is_null($this->resource)) {
            return [];
        }

        return is_array($this->resource)
            ? $this->resource
            : $this->resource->toArray();
    }

    public static function toResponse($data)
    {
        return [self::$wrap => $data];
    }
}
