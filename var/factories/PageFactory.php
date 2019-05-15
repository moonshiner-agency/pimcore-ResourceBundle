<?php
use Pimcore\Model\Document\Page;

$factory->define(Page::class, function(){
    return [
        'parentId'=> 1,
    ];
});

$factory->state(Page::class, 'folder', function () {
    return [
        'type' => 'folder',
    ];
});