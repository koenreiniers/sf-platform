<?php
namespace Raw\Pager;

class PagerView implements \ArrayAccess
{

    /**
     * @var array
     */
    public $vars = [
        'items' => [],
    ];

    /**
     * @var Pager
     */
    public $pager;

    /**
     * @var bool
     */
    private $rendered = false;

    /**
     * PagerView constructor.
     * @param Pager $pager
     */
    public function __construct(Pager $pager)
    {
        $this->pager = $pager;
    }

    public function isRendered()
    {
        return $this->rendered === true;
    }

    public function setRendered()
    {
        $this->rendered = true;
    }

    public function offsetGet($offset)
    {
        return $this->vars[$offset];
    }
    public function offsetUnset($offset)
    {
        throw new \Exception('Not supported');
    }
    public function offsetExists($offset)
    {
        return isset($this->vars[$offset]);
    }
    public function offsetSet($offset, $value)
    {
        throw new \Exception('Not supported');
    }
}