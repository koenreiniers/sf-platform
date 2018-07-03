<?php
namespace Raw\Component\Statistics\Dataset;

use Doctrine\ORM\QueryBuilder;

class DoctrineDataset extends Dataset
{

    /**
     * @var QueryBuilder
     */
    protected $qb;

    /**
     * @var string
     */
    private $labelField = 'label';

    /**
     * @var string
     */
    private $datumField = 'datum';

    /**
     * @var callable
     */
    private $callback;

    /**
     * @var string|null
     */
    protected $scale;

    /**
     * @var mixed|null
     */
    protected $start;

    /**
     * @var mixed|null
     */
    protected $end;

    /**
     * @var string
     */
    protected $xAxisType = 'text';

    /**
     * @var string|null
     */
    protected $groupBy;

    /**
     * DoctrineDataset constructor.
     * @param callable $callback
     */
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param callable $callback
     * @return DoctrineDataset
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

    public function setQueryBuilder(QueryBuilder $qb)
    {
        $this->qb = $qb;
        return $this;
    }

    public function setLabelField($labelField)
    {
        $this->labelField = $labelField;
        return $this;
    }

    public function setDatumField($datumField)
    {
        $this->datumField = $datumField;
        return $this;
    }

    public function setXAxisType($xAxisType)
    {
        $this->xAxisType = $xAxisType;
        return $this;
    }

    /**
     * @return array
     */
    public function getDataPoints(array $parameters)
    {
        $cb = $this->callback;
        $cb($this, $parameters);

        $parameters = $this->resolveParameters($parameters);

        $this->start = $parameters['start'];
        $this->end = $parameters['end'];

        if($this->scale === null) {
            $this->scale = $this->getScale($parameters);
        }

        $qb = $this->qb;

        $this->doGroupBy($this->groupBy);

        $labels = $this->generateLabels($parameters);

        $dataPoints = $this->generateDataPoints($qb, $labels, $parameters);

        return $dataPoints;
    }

    protected function getScale(array $parameters)
    {
        if($this->xAxisType === 'date') {
            return $this->getDateScale($parameters['start'], $parameters['end']);
        }
        return null;
    }

    private function getDateScale(\DateTime $start, \DateTime $end)
    {
        $diff = $end->getTimestamp() - $start->getTimestamp();
        $margin = 1.2;

        if($diff > 365 * 24 * 60 * 60 * $margin) {
            return 'year';
        } else if($diff > 30 * 24 * 60 * 60 * $margin) {
            return 'month';
        } else if($diff > 24 * 60 * 60 * $margin) {
            return 'day';
        } else if($diff > 60 * 60 * $margin) {
            return 'hour';
        } else if($diff > 60 * $margin) {
            return 'minute';
        }
        return 'second';
    }

    public function groupBy($groupBy, $xAxisType = 'text')
    {
        $this->groupBy = $groupBy;
        $this->xAxisType = $xAxisType;
        return $this;
    }

    protected function doGroupBy($fieldName)
    {
        if($this->xAxisType === 'date') {
            $this->groupByDate($this->qb, $fieldName, $this->scale);
        }
        $this->between($fieldName, $this->start, $this->end);
    }

    private function groupByDate(QueryBuilder $qb, $dateField, $scale)
    {
        $groupByFormat = $this->getDateFormat($scale);

        $qb
            ->addSelect(sprintf('DATE_FORMAT(%s, :groupByFormat) AS %s', $dateField, $this->labelField))
            ->setParameter('groupByFormat', $groupByFormat)
            ->groupBy($this->labelField);
    }

    private function betweenDates(QueryBuilder $qb, \DateTime $start, \DateTime $end, $dateField)
    {
        $qb
            ->andWhere(sprintf('%s >= :start', $dateField))
            ->andWhere(sprintf('%s <= :end', $dateField))
            ->setParameter('start', $start)
            ->setParameter('end', $end);
    }

    private function getDateFormat($scale)
    {
        switch($scale) {
            case 'day':
                $groupByFormat = '%d-%m-%Y';
                break;
            case 'month':
                $groupByFormat = '%m-%Y';
                break;
            case 'year':
                $groupByFormat = '%Y';
                break;
            case 'hour':
                $groupByFormat = '%H:%i';
                break;
            case 'minute':
                return '%i:%s';
            case 'second':
                return '%s';
            default:
                throw new \Exception(sprintf('Invalid scale "%s"', $scale));
        }
        return $groupByFormat;
    }

    private function getPhpDateFormat($interval)
    {
        return str_replace('%', '', $this->getDateFormat($interval));
    }



    protected function generateLabels(array $parameters)
    {
        if($this->xAxisType === 'date') {
            return $this->generateDateLabels($parameters['start'], $parameters['end'], $this->scale);
        }
        return [];
    }

    private function generateDateLabels(\DateTime $start, \DateTime $end, $scale)
    {
        $interval = $this->createDateInterval($scale);
        $period = new \DatePeriod($start, $interval, $end);
        $labels = [];
        $dateFormat = $this->getPhpDateFormat($scale);
        foreach($period as $date) {
            $labels[] = $date->format($dateFormat);
        }
        return $labels;
    }

    protected function generateDataPoints(QueryBuilder $qb, array $labels, array $parameters)
    {
        $dataPoints = $this->createEmptyDataPoints($labels);
        $results = $qb->getQuery()->getArrayResult();
        foreach($results as $result) {
            $key = $result[$this->labelField];
            $dataPoints[$key] = (int)$result[$this->datumField];
        }
        return $dataPoints;
    }

    private function createDateInterval($scale)
    {
        switch($scale) {
            case 'day':
                $intervalSpec = 'P1D';
                break;
            case 'month':
                $intervalSpec = 'P1M';
                break;
            case 'year':
                $intervalSpec = 'P1Y';
                break;
            case 'hour':
                $intervalSpec = 'PT1H';
                break;
            case 'minute':
                $intervalSpec = 'PT1M';
                break;
            case 'second':
                $intervalSpec = 'PT1S';
                break;
            default:
                throw new \Exception(sprintf('Invalid scale "%s"', $scale));
        }
        return new \DateInterval($intervalSpec);
    }

    public function between($fieldName, $start, $end)
    {
        if($this->xAxisType === 'date') {
            $this->betweenDates($this->qb, $start, $end, $fieldName);
        }
        return $this;
    }

    protected function resolveParameters(array $parameters)
    {
        if($this->xAxisType === 'date') {
            $defaults = [
                'start' => new \DateTime(),
                'end' => new \DateTime(),
            ];
            $parameters = array_merge($defaults, $parameters);
            if(is_string($parameters['start'])) {
                $parameters['start'] = new \DateTime($parameters['start']);
            }
            if(is_string($parameters['end'])) {
                $parameters['end'] = new \DateTime($parameters['end']);
            }
        }
        return $parameters;
    }
}