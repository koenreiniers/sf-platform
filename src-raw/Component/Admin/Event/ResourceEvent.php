<?php
namespace Raw\Component\Admin\Event;

use Symfony\Component\EventDispatcher\Event;

class ResourceEvent extends Event
{
    /**
     * @var object
     */
    private $resource;

    /**
     * ResourceEvent constructor.
     * @param object $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }


    /**
     * @return object
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param object $resource
     * @return ResourceEvent
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
        return $this;
    }


}