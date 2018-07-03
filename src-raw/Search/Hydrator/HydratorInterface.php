<?php
namespace Raw\Search\Hydrator;


use Raw\Search\Query\QueryHit;

interface HydratorInterface
{

    /**
     * @param QueryHit[] $hits
     *
     * @return mixed[]
     */
    public function hydrateAll(array $hits);
}