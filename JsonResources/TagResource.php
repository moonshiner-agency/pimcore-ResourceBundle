<?php

namespace Moonshiner\BrigthenBundle\JsonResources;

use Moonshiner\BrigthenBundle\Services\Resource;

class TagResource extends Resource
{
    public function toArray()
    {
        if($this->resource) {
            return [
                'text' => $this->resource->getTitle(),
                'icon' => $this->resource->getIcon()
            ];
        }

        return;
    }
}
