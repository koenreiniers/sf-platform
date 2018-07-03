<?php
namespace Platform\Magento\ArrayConverter;

class DefaultArrayConverter
{
    /**
     * @var array
     */
    protected $map;

    /**
     * DefaultArrayConverter constructor.
     * @param array $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    protected function applyMap(array $map, array $data)
    {
        $mapped = [];
        foreach($map as $to => $from) {
            $value = null;
            if(is_string($from)) {
                if(isset($data[$from])) {
                    $value = $data[$from];
                }
            } else {
                $value = $from($data);
            }
            $mapped[$to] = $value;
        }
        return $mapped;
    }

    public function convert(array $data)
    {
        $data = $this->applyMap($this->map, $data);
        return $data;
    }

    public function convertAll(array $items)
    {
        $converted = [];
        foreach($items as $item) {
            $converted[] = $this->convert($item);
        }
        return $converted;
    }
}