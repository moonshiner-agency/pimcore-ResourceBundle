<?php

namespace Moonshiner\BrigthenBundle\Services;

use Pimcore\Http\RequestHelper;

class Navigation
{
    protected $from;
    protected $request;
    protected $siteRoot;
    protected $container;

    public function __construct(RequestHelper $request)
    {
        $this->request = $request;
        $this->siteRoot = \Pimcore\Tool\Frontend::getWebsiteConfig()->get('rootPath') ?: '';
    }

    public function from( $pageId )
    {
        $this->from = $pageId;
        return $this;
    }

    public function toJson()
    {
        $this->buildContainer();
        return $this
            ->mapPages($this->container->getPages())
            ->toJson();
    }

    public function toArray()
    {
        $this->buildContainer();
        return $this
            ->mapPages($this->container->getPages())
            ->toArray();
    }

    protected function buildContainer()
    {
        $builder = new \Pimcore\Navigation\Builder($this->request);
        $location = $this->request->getCurrentRequest()->get('location') ?: 1;
        $currentPage = \Pimcore\Model\Document::getById($location);
        $rootPage = \Pimcore\Model\Document::getById($this->getRoot());
        $this->container = $builder->getNavigation($currentPage, $rootPage);
    }

    protected function mapPages($pages)
    {
        return collect($pages)
            ->filter(function ($page) {
                return $page->getVisible();
            })
            ->map(function ($page) {
                $hasChild = count( $page->getPages() );
                return [
                    'id' => $page->getId(),
                    'uri' => $this->getUri($page->getUri()),
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
        if ($this->from) {
            return $this->from;
        }

        $lang = $this->request->getCurrentRequest()->getLocale();
        $root = \Pimcore\Model\Document::getList([
            'condition' => " `key` = '{$lang}' ",
        ])->loadIdList();
        if (count($root)) {
            return $root[0];
        }

        return \Pimcore\Model\Document::getById(1)->getId();
    }

    protected function getUri($uri)
    {
        $lang = $this->request->getCurrentRequest()->getLocale();
        $search = $this->siteRoot;
        return $search === '' ? $uri : array_reverse(explode($search, $uri, 2))[0];
    }
}