<?php

namespace Moonshiner\BrigthenBundle\Services;

use Pimcore\Http\RequestHelper;

class Navigation
{
    protected $container;
    protected $request;

    public function __construct(RequestHelper $request)
    {
        $this->request = $request->getCurrentRequest();
        $builder = new \Pimcore\Navigation\Builder($request);

        $location = $this->request->get('location') ?: 1;
        $currentPage = \Pimcore\Model\Document::getById($location);
        $rootPage = \Pimcore\Model\Document::getById($this->getRoot());
        $this->container = $builder->getNavigation($currentPage, $rootPage);

    }

    public function toJson()
    {
        return $this
            ->mapPages($this->container->getPages())
            ->toJson();
    }

    public function toArray()
    {
        return $this
            ->mapPages($this->container->getPages())
            ->toArray();
    }

    protected function mapPages($pages)
    {
        return collect($pages)
            ->map(function ($page) {
                $hasChild = count( $page->getPages() );
                return [
                    'id' => $page->getId(),
                    'uri' => $page->getUri(),
                    'title' => $page->getTitle() ? $page->getTitle() : $page->getLabel(),
                    'class' => $page->getClass(),
                    'target' => $page->getTarget() ? $page->getTarget() : "_self",
                    'pages' => $hasChild ? $this->mapPages( $page->getPages()) : [],
                ];
            })
            ->values();
    }

    protected function getRoot()
    {
        $lang = $this->request->getLocale();
        $root = \Pimcore\Model\Document::getList([
            'condition' => " `key` = '{$lang}' ",
        ])->loadIdList();
        if (count($root)) {
            return $root[0];
        }

        return \Pimcore\Model\Document::getById(1)->getId();
    }
}