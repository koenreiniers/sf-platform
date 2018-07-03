<?php
namespace Raw\Filter\Adapter;


use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Raw\Filter\FilterAdapter;
use Raw\Filter\Expr;
use Symfony\Component\VarDumper\VarDumper;

class DoctrineOrmAdapter extends FilterAdapter
{
    /**
     * @var QueryBuilder
     */
    private $qb;

    /**
     * DoctrineOrmAdapter constructor.
     * @param Query $query
     */
    public function __construct(QueryBuilder $qb)
    {
        $this->qb = $qb;
    }

    public function dispatch(Expr\Base $expression)
    {


        $expr = $this->convert($expression);

        $this->qb->andWhere($expr);
    }

    protected function walkComposite(Expr\Composite $expression)
    {
        if($expression->getType() === Expr\Composite::TYPE_AND) {
            $converted = new Query\Expr\Andx();
        } else {
            $converted = new Query\Expr\Orx();
        }
        foreach($expression->getExpressions() as $subexpr) {
            $subexpr = $this->convert($subexpr);
            $converted->add($subexpr);
        }
        return $converted;
    }

    protected function walkComparison(Expr\Comparison $expression)
    {
        $operator = $this->convertOperator($expression->getOperator());
        $leftExpr = $this->convert($expression->getField());
        $rightExpr = $this->convert($expression->getValue());
        return new Query\Expr\Comparison($leftExpr, $operator, $rightExpr);
    }

    protected function walkLiteral(Expr\Literal $expression)
    {
        $args = $expression->getLiteral();
        return $this->qb->expr()->literal($args);
    }

    private function convertOperator($operator)
    {
        $map = [
            Expr\Comparison::EQ => '=',
        ];
        if(isset($map[$operator])) {
            $operator = $map[$operator];
        }
        return $operator;
    }


    private function convert($expression)
    {
        if(is_scalar($expression)) {
            return $expression;
        }
        if($expression instanceof Expr\Literal) {
            return $this->walkLiteral($expression);
        }
        if($expression instanceof Expr\Comparison) {
            return $this->walkComparison($expression);
        }
        if($expression instanceof Expr\Composite) {
            return $this->walkComposite($expression);
        }
        throw new \Exception('Unknown expression "%s"', get_class($expression));
    }
}