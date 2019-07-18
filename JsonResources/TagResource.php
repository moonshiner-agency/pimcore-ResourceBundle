<?php

namespace Moonshiner\BrigthenBundle\JsonResources;

use Moonshiner\BrigthenBundle\Services\Resource;

class TagResource extends Resource
{
    public function toArray()
    {
        return [
            'text' => $this->resource->getTitle(),
            'icon' => $this->resource->getIcon() ? \Pimcore\Tool::getHostUrl().$this->resource->getIcon()->getThumbnail('icon')->getPath() : null
        ];
    }
}
