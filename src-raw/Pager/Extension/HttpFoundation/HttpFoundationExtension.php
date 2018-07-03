<?php
namespace Raw\Pager\Extension\HttpFoundation;

use Raw\Pager\PagerBuilder;
use Raw\Pager\PagerExtension;
use Raw\Pager\RequestHandler\HttpFoundationRequestHandler;
use Raw\Pager\RequestHandler\RequestHandlerInterface;

class HttpFoundationExtension extends PagerExtension
{
    /**
     * @var RequestHandlerInterface
     */
    private $requestHandler;

    /**
     * HttpFoundationExtension constructor.
     * @param RequestHandlerInterface|null $requestHandler
     */
    public function __construct(RequestHandlerInterface $requestHandler = null)
    {
        $this->requestHandler = $requestHandler ?: new HttpFoundationRequestHandler();
    }

    public function buildPager(PagerBuilder $builder, array $options)
    {

        if($builder->getRequestHandler() === null) {
            $builder->setRequestHandler($this->requestHandler);
        }

    }

}