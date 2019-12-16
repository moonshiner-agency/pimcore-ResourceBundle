<?php

namespace Moonshiner\BrigthenBundle\JsonResources;

use Moonshiner\BrigthenBundle\Services\Navigation;
use Moonshiner\BrigthenBundle\Services\Resource;
use Moonshiner\BrigthenBundle\Services\Service\SystemSettings;
use Pimcore\Model\Asset;
use Pimcore\Model\Document;

class LinkResource extends Resource
{
    public function toArray()
    {
        $link = $this->resource->getData();
        $host = SystemSettings::getHostUrl();

        if ($link['internal'] === null && $link['text'] === null) {
            return null;
        }

        if ($link['internal'] === true) {
            $document = Document::getByPath($link['path']);
            if ($document !== null) {
                $link['path'] = $document->getFullPath();
            }

            if ($link['internalType'] === 'asset' && intval($link['internalId']) > 0) {
                $asset = Asset::getById($link['internalId']);
                $link['path'] = $host . $asset->getFullPath();
            }
        }

        return [
            'internalType' => $link['internalType'],
            'linktype' => $link['linktype'],
            'text' => $link['text'],
            'path' => Navigation::getUri($link['path']),
            'target' => $link['target'],
            'parameters' => $link['parameters'],
            'anchor' => $link['anchor'],
            'title' => $link['title'],
            'accesskey' => $link['accesskey'],
            'rel' => $link['rel'],
            'tabindex' => $link['tabindex'],
            'class' => $link['class'],
            'attributes' => $link['attributes'],
            'internal' => $link['internal'],
            'internalId' => $link['internalId'],
        ];
    }
}
