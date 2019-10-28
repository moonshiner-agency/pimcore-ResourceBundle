<?php

namespace Moonshiner\BrigthenBundle\JsonResources;

use Moonshiner\BrigthenBundle\Services\Navigation;
use Moonshiner\BrigthenBundle\Services\Resource;

class LinkResource extends Resource
{
    public function toArray()
    {
        $link = $this->resource->getData();

        if ($link['internal'] === null && $link['text'] === null) {
            return null;
        }

        if ($link['internal'] === true) {
            $document = Document::getByPath($link['path']);
            if ($document !== null) {
                $link['path'] = $document->getFullPath();
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
