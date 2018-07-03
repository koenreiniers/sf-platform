<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Expression;



class Boost extends Value
{
    /**
     * @var Value
     */
    private $value;

    /**
     * @var int
     */
    private $factor;

    public function __construct(Value $value, $factor)
    {
        $this->value = $value;
        $this->factor = $factor;
    }

    public function __toString()
    {
        return sprintf('%s^%s', $this->value, $this->factor);
    }
}