<?php
namespace Raw\Component\Admin;

use Raw\Component\Admin\Page\AdminPage;

class AdminBuilder
{
    /**
     * @var AdminPage[]
     */
    private $pages = [];

    public function createPage($name, $pageClass, array $options)
    {
        $page = new $pageClass;
        return $page;
    }

    public function addPage($name, $pageClass, array $options = [])
    {
        $page = $this->createPage($name, $pageClass, $options);
        $this->pages[$name] = $page;
        return $this;
    }
}