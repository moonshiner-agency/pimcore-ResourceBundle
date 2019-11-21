<?php

namespace Moonshiner\BrigthenBundle\JsonResources;

use Moonshiner\BrigthenBundle\Services\Resource;
use Moonshiner\BrigthenBundle\Services\Service\SystemSettings;

class ImageResource extends Resource
{
    public function toArray()
    {
        $host = SystemSettings::getHostUrl();

        return [
            'src' => $host . $this->resource->getFullPath(),
            'thumbnails' => [
                'cover' => $host . $this->resource->getThumbnail('cover')->getPath(),
                'card' => $host . $this->resource->getThumbnail('card')->getPath(),
                'smallCard' => $host . $this->resource->getThumbnail('smallCard')->getPath(),
                'highlightedItem' => $host . $this->resource->getThumbnail('highlightedItem')->getPath(),
                'callToAction' => $host . $this->resource->getThumbnail('callToAction')->getPath(),
                'icon' => $host . $this->resource->getThumbnail('icon')->getPath(),
            ]
        ];
    }
}
