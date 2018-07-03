<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Expression;

class ExpressionBuilder
{

    public function equals($field, $value)
    {
        return new Comparison($field, Comparison::OP_EQUALS, $value);
    }

    public function startsWith($field, $term)
    {
        return $this->equals($field, new Wildcard($term.'*'));
    }

    public function fuzzy($term, $similarity = null)
    {
        if(is_string($term)) {
            $term = new Term($term);
        }
        return new Fuzzy($term, $similarity);
    }

    public function range($start, $end, $inclusive = true)
    {
        if(!$start instanceof Term) {
            $start = new Term($start);
        }
        if(!$end instanceof Term) {
            $end = new Term($end);
        }
        return new Range($start, $end, $inclusive);
    }

    public function between($field, $start, $end, $inclusive = true)
    {
        return new Comparison($field, Comparison::OP_BETWEEN, $this->range($start, $end, $inclusive));
    }

    public function prohibit($term)
    {
        if(is_string($term)) {
            $term = new Term($term);
        }
        return new Prohibit($term);
    }

    public function andX($x = null)
    {
        $expressions = func_get_args();
        return new CompositeExpression($expressions, CompositeExpression::TYPE_AND);
    }

    public function orX($x = null)
    {
        $expressions = func_get_args();
        return new CompositeExpression($expressions, CompositeExpression::TYPE_OR);
    }

    public function multi($x = null)
    {
        $terms = func_get_args();
        return new MultiTerm($terms);
    }


    public function boost(Value $value, $factor)
    {
        return new Boost($value, $factor);
    }

    public function proximity($proximity, $terms)
    {
        $termArgs = func_get_args();
        array_shift($termArgs);

        if(count($termArgs) === 1) {
            if(is_array($termArgs[0])) {
                $termArgs = $termArgs[0];
            }
        }

        $terms = [];

        foreach($termArgs as $termArg) {
            if(!$termArg instanceof Term) {
                $termArg = new Term($termArg);
            }
            $terms[] = $termArg;
        }


        return new Proximity($proximity, $terms);
    }

    /**
     * @param string $phrase
     * @return Phrase
     */
    public function phrase($phrase)
    {
        return new Phrase($phrase);
    }

    /**
     * @param string $term
     * @return Term
     */
    public function term($term)
    {
        return new Term($term);
    }
}