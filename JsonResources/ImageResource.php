<?php

namespace Moonshiner\BrigthenBundle\JsonResources;

use Moonshiner\BrigthenBundle\Services\Resource;
use Moonshiner\BrigthenBundle\Services\Service\SystemSettings;

class ImageResource extends Resource
{
    public function toArray()
    {
        $host =  SystemSettings::getHostUrl();
        return [
            'src' => $host . $this->resource->getFullPath(),
            'thumbnails' => [
                'intended_lg' => $host . $this->resource->getThumbnail('intended_lg')->getPath(),
                'intended_md' => $host . $this->resource->getThumbnail('intended_md')->getPath(),
                'intended_sm' => $host . $this->resource->getThumbnail('intended_sm')->getPath(),
                'thumbnail_md' => $host . $this->resource->getThumbnail('thumbnail_md')->getPath(),
                'thumbnail_sm' => $host . $this->resource->getThumbnail('thumbnail_sm')->getPath(),
                'mobile_intended_md' => $host . $this->resource->getThumbnail('mobiles_intended_md')->getPath(),
                'mobile_intended_sm' => $host . $this->resource->getThumbnail('mobile_intended_sm')->getPath(),
                'mobile_thumbnail_md' => $host . $this->resource->getThumbnail('mobile_thumbnail_md')->getPath(),
                'mobile_thumbnail_sm' => $host . $this->resource->getThumbnail('mobile_thumbnail_sm')->getPath(),
            ]
        ];
    }
}
