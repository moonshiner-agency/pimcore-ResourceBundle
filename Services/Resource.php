<?php

namespace Moonshiner\BrigthenBundle\Services;

class Resource implements ResourceInterface
{
    const NESTED = true;
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

    public static function collection($collection, $nested = false)
    {
        $className = get_called_class();
        $transformed = array_map(function ($item) use ($className) {
            return (new $className($item))->toArray();
        }, $collection);

        if($nested) {
            return $transformed;
        }
        return self::toResponse(  $transformed);
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
