<?php
namespace Raw\Component\Batch\Item\Writer;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnitOfWork;
use Raw\Component\Batch\Item\ItemWriterInterface;

class NullWriter implements ItemWriterInterface
{
    public function write($items)
    {
    }
}