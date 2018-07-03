<?php
namespace Raw\Pager;


use Doctrine\ORM\QueryBuilder;
use Raw\Pager\Adapter\AdapterInterface;

use Raw\Pager\Adapter\DoctrineOrmAdapter;
use Raw\Pager\Event\BuildViewEvent;
use Raw\Pager\Event\HandleRequestEvent;
use Raw\Pager\Event\PagerViewEvent;
use Raw\Pager\EventListener\PagerExtensionSubscriber;
use Raw\Pager\RequestHandler\HttpFoundationRequestHandler;
use Raw\Pager\RequestHandler\RequestHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\EventDispatcher\EventDispatcher;

class PagerFactory
{
    use ContainerAwareTrait;

    /**
     * @var PagerExtension[]
     */
    private $extensions;

    public function __construct(array $extensions)
    {
        $this->extensions = $extensions;
    }

    public function create(AdapterInterface $adapter)
    {
        return $this->createBuilder($adapter)->getPager();
    }

    public function createAdapter($source)
    {
        if($source instanceof QueryBuilder) {
            return new DoctrineOrmAdapter($source);
        }
        throw new \Exception('Cannot create adapter for this data source');
    }

    public function createBuilder(AdapterInterface $adapter, array $options = [])
    {
        $builder = new PagerBuilder($this, $adapter, new EventDispatcher(), $options);
        foreach($this->extensions as $extension) {
            $builder->addEventSubscriber(new PagerExtensionSubscriber($extension));
        }

        return $builder;
    }
}