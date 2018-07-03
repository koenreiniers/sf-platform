<?php
namespace Raw\Filter;

use Raw\Filter\Expr\Comparison;
use Raw\Filter\Expr\Composite;
use Raw\Filter\Storage\InMemoryStorage;
use Symfony\Component\VarDumper\VarDumper;

class Filterer
{
    /**
     * @var FilterAdapter
     */
    private $adapter;

    /**
     * @var FilterRegistry
     */
    private $registry;

    /**
     * @var ExpressionBuilder
     */
    private $expressionBuilder;

    /**
     * @var FilterContext
     */
    private $context;

    /**
     * @var FilterStorageInterface
     */
    private $storage;

    /**
     * Filterer constructor.
     * @param FilterAdapter $adapter
     */
    public function __construct(FilterContext $context)
    {
        $this->context = $context;
        $this->adapter = $context->getAdapter();
        $this->registry = $context->getRegistry();
        $this->storage = $context->getStorage() ?: new InMemoryStorage();
        $this->expressionBuilder = new ExpressionBuilder();
    }

    /**
     * @return FilterContext
     */
    public function getContext()
    {
        return $this->context;
    }

    public function getStorage()
    {
        return $this->storage;
    }

    public function addFilter(Filter $filter)
    {
        $this->storage->addFilter($filter);
        return $this;
    }

    public function getFilters()
    {
        return $this->storage->getFilters();
    }

    public function removeFilter(Filter $filter)
    {
        $this->storage->removeFilter($filter);
        return $this;
    }

    public function apply()
    {
        foreach($this->storage->getFilters() as $filter) {
            $expr = $this->createExpression($filter);
            $this->adapter->dispatch($expr);
        }
    }

    public function clear()
    {
        $this->storage->clear();
    }



    private function preProcess(Filter $filter)
    {
        $field = $filter->getField();
        $operator = $filter->getOperator();
        $data = $filter->getData();

        switch($operator)
        {
            case 'CONTAINS':
                $operator = Comparison::LIKE;
                $data[0] = '%'.$data[0].'%';
                break;
            case 'STARTSWITH':
                $operator = Comparison::LIKE;
                $data[0] = $data[0].'%';
                break;
            case 'ENDSWITH':
                $operator = Comparison::LIKE;
                $data[0] = '%'.$data[0];
                break;
        }


        return new Filter($field, $operator, $data);
    }

    private function getCallable($operator)
    {
        $map = [
            Comparison::EQ => 'eq',
            Comparison::NEQ => 'neq',
            Comparison::GT => 'gt',
            Comparison::GTE => 'gte',
            Comparison::LT => 'lt',
            Comparison::LTE => 'lte',
            Comparison::LIKE => 'like',
            Composite::TYPE_BETWEEN => 'between',
        ];
        return [$this->expressionBuilder, $map[$operator]];
    }

    private function escapeData(array $data)
    {
        return array_map(function($v){
            return $this->expressionBuilder->literal($v);
        }, $data);
    }

    private function createExpression(Filter $filter)
    {
        $filterName = $filter->getField();
        $definition = $this->context->findDefinition($filterName);

        $fieldName = $definition->getFieldName();

        $filter = $this->preProcess($filter);

        $data = $this->escapeData($filter->getData());

        $fn = $this->getCallable($filter->getOperator());

        $args = array_merge([$fieldName], $data);

        return call_user_func_array($fn, $args);

    }
}