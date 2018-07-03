<?php
namespace Raw\Component\Batch\Model;

use Raw\Component\Batch\StepExecution;

class Warning
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $item;

    /**
     * @var StepExecution
     */
    protected $stepExecution;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Warning
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return array
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param array $item
     * @return Warning
     */
    public function setItem(array $item)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @return StepExecution
     */
    public function getStepExecution()
    {
        return $this->stepExecution;
    }

    /**
     * @param StepExecution $stepExecution
     * @return Warning
     */
    public function setStepExecution($stepExecution)
    {
        $this->stepExecution = $stepExecution;
        return $this;
    }


}