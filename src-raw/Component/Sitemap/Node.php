<?php
namespace Raw\Component\Sitemap;

class Node implements \IteratorAggregate
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $url;

    /**
     * @var Node|null
     */
    private $parent;

    /**
     * @var Node[]
     */
    private $children;


    public function __construct(Node $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Node
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Node
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return null|Node
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function add($title, $url)
    {
        $child = new Node($title, $url);
        $this->children[] = $child;
        return $child;
    }

    public function end()
    {
        return $this->parent;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->children);
    }
}