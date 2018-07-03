<?php
namespace Raw\Search\Hydrator;

use ZendSearch\Lucene\Document;

class CallbackHydrator implements HydratorInterface
{
    /**
     * @var \callable
     */
    private $callback;

    /**
     * CallbackHydrator constructor.
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function hydrateAll(array $hits)
    {
        $callback = $this->callback;
        return $callback($hits);
    }

}