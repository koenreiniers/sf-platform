<?php
namespace Raw\Bundle\ApiBundle\Envelope;

class CollectionEnvelope extends Envelope
{

    public function __construct($items = [], $count = 0)
    {
        parent::__construct([
            'items' => $items,
            'count' => $count,
        ]);
    }

    public function setItems($items)
    {
        return $this->setDataValue('items', $items);
    }

    public function setCount($count)
    {
        return $this->setDataValue('count', $count);
    }

    public function getCount()
    {
        return $this->getDataValue('count');
    }

    public function getItems()
    {
        return $this->getDataValue('items');
    }


}