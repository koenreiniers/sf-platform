<?php
namespace Raw\Component\Bol\Plaza\Model;

class Transport
{
    /**
     * @var string
     */
    protected $transporterCode;

    /**
     * @var string
     */
    protected $trackAndTrace;

    public static function create()
    {
        return new self();
    }

    /**
     * @return string
     */
    public function getTransporterCode()
    {
        return $this->transporterCode;
    }

    /**
     * @param string $transporterCode
     * @return Transport
     */
    public function setTransporterCode($transporterCode)
    {
        $this->transporterCode = $transporterCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getTrackAndTrace()
    {
        return $this->trackAndTrace;
    }

    /**
     * @param string $trackAndTrace
     * @return Transport
     */
    public function setTrackAndTrace($trackAndTrace)
    {
        $this->trackAndTrace = $trackAndTrace;
        return $this;
    }
}