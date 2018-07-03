<?php
namespace Raw\Search\Mapping\Expr;

use Raw\Search\Mapping\Factory;
use Raw\Search\Mapping\FieldReference;

use Raw\Search\Mapping\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;
use ZendSearch\Lucene\Document;

class ExprResolver
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Document
     */
    private $document;

    /**
     * ExprResolver constructor.
     * @param ContainerInterface $container
     * @param Document $document
     */
    public function __construct(ContainerInterface $container, Document $document)
    {
        $this->container = $container;
        $this->document = $document;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return Document
     */
    public function getDocument()
    {
        return $this->document;
    }



    public function resolve($value)
    {
        if(is_array($value)) {
            foreach($value as $k => $elm) {
                $value[$k] = $this->resolve($elm);
            }
            return $value;
        }
        if($value instanceof Expr) {
            return $value->resolve($this);
        }

        return $value;
    }
}