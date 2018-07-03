<?php
namespace Raw\Component\Bol\Plaza\Normalizer;

use Raw\Component\Bol\Plaza\Model\ProcessStatus;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ProcessStatusNormalizer implements DenormalizerInterface
{
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        $data = $this->removeXmlNamespace($data);
        $defaults = [
            'id' => null,
            'entityId' => null,
            'eventType' => null,
            'description' => null,
            'status' => null,
            'createTimestamp' => null,
            'links' => [],
        ];
        $data['links'] = isset($data['Links']) ? $data['Links'] : [];
        unset($data['Links']);
        $data = array_merge($defaults, $data);


        if($data['createTimestamp'] !== null) {
            $data['createTimestamp'] = new \DateTime($data['createTimestamp']);
        }

        $processStatus = new ProcessStatus();
        $processStatus->setId($data['id']);
        $processStatus->setEntityId($data['entityId']);
        $processStatus->setEventType($data['eventType']);
        $processStatus->setDescription($data['description']);
        $processStatus->setStatus($data['status']);
        $processStatus->setCreateTimestamp($data['createTimestamp']);
        $processStatus->setLinks($data['links']);
        return $processStatus;
    }

    private function removeXmlNamespace($data)
    {
        if(!is_array($data)) {
            return $data;
        }
        $normalized = [];
        foreach($data as $key => $value) {

            $pos = strpos($key, ':');

            if($pos !== false) {
                $key = substr($key, $pos + 1);
            }

            $normalized[$key] = $this->removeXmlNamespace($value);
        }
        return $normalized;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === ProcessStatus::class;
    }
}