<?php

namespace Moonshiner\BrigthenBundle\JsonResources;

use Moonshiner\BrigthenBundle\Services\Resource;
use Moonshiner\BrigthenBundle\Services\Service\SystemSettings;

class AssetResource extends Resource
{
    public function toArray()
    {
        $host = SystemSettings::getHostUrl();

        return [
            'name' => $this->resource->getFilename(),
            'path' => $host . $this->resource->getFullPath(),
            'modificationDate' => $this->resource->getModificationDate(),
            'type' => $this->resource->getType(),
            'size' => $this->resource->getFileSize(true),
        ];
    }
}
