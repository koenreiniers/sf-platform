<?php
namespace Raw\Component\Grid;

use Raw\Component\Grid\DataSource\Data;
use Raw\Component\Grid\Event\GridDataEvent;
use Raw\Component\Grid\Event\GridEvent;
use Raw\Component\Grid\Mapping\GridMetadata;
use Raw\Component\Grid\DataSource\DataSource;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class Grid
{
    /**
     * @var GridExtension[]
     */
    protected $extensions = [];

    /**
     * @var DataSource
     */
    protected $dataSource;

    /**
     * @var GridMetadata
     */
    protected $metadata;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var array|null
     */
    private $data;

    /**
     * Grid constructor.
     * @param GridMetadata $gridMetadata
     * @param array $extensions
     */
    public function __construct(GridMetadata $gridMetadata, EventDispatcherInterface $eventDispatcher, array $extensions)
    {
        $this->metadata = $gridMetadata;
        $this->eventDispatcher = $eventDispatcher;
        $this->extensions = $extensions;
        $this->parameters = [];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->metadata->getName();
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getProperty($name)
    {
        return $this->metadata->getProperty($name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasProperty($name)
    {
        return $this->metadata->hasProperty($name);
    }

    /**
     * @return DataSource
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    public function hasParameter($name)
    {
        return isset($this->parameters[$name]);
    }

    public function getParameter($name, $default = null)
    {
        if(!$this->hasParameter($name)) {
            return $default;
        }
        return $this->parameters[$name];
    }

    public function setParameter($name, $value)
    {
        $this->parameters[$name] = $value;
        return $this;
    }

    public function handleRequest(Request $request)
    {
        foreach($request->get($this->getName(), []) as $name => $value) {
            $this->setParameter($name, $value);
        }
    }

    /**
     * @return GridView
     */
    public function createView()
    {
        $view = new GridView($this);
        foreach($this->extensions as $extension) {
            $extension->buildView($view, $this);
        }
        foreach($this->extensions as $extension) {
            $extension->finishView($view, $this);
        }
        return $view;
    }

    /**
     * @return Data
     */
    public function getData()
    {
        if($this->data !== null) {
            return $this->data;
        }

        $this->eventDispatcher->dispatch(GridEvents::PRE_GET_DATA, new GridEvent($this));

        $data =  $this->dataSource->getData();

        $this->eventDispatcher->dispatch(GridEvents::POST_GET_DATA, new GridDataEvent($this, $data));

        return $this->data = $data;
    }
}