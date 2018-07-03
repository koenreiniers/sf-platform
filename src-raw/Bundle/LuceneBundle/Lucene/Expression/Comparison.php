<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Expression;

use ZendSearch\Lucene\Search\Query\Preprocessing\Term as TermQuery;
use ZendSearch\Lucene\Search\Query\Preprocessing\Term as PhraseQuery;

class Comparison extends Expression
{
    const OP_EQUALS = 0;
    const OP_BETWEEN = 1;

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var string
     */
    private $value;

    public function __construct($field, $operator = self::OP_EQUALS, $value)
    {
        $this->field = $field;
        $this->operator = $operator;
        switch($operator) {
            case self::OP_EQUALS:
                if(!$value instanceof Value) {
                    $value = new Term($value);
                }
                break;
            case self::OP_BETWEEN:
                if(!$value instanceof Range) {
                    throw new \InvalidArgumentException(sprintf('Value must be an instance of "%s"', Range::class));
                }
                break;
        }
        $this->value = $value;
    }

    public function getQuery()
    {
        if($this->value instanceof Phrase) {
            return new PhraseQuery($this->value, '', $this->field);
        }
        return new TermQuery($this->value, '', $this->field);
    }

    public function __toString()
    {
        return $this->field.':'.$this->value;
    }
}