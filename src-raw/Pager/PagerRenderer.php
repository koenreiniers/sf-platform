<?php
namespace Raw\Pager;

use Raw\Pager\Renderer\Engine\EngineInterface;

class PagerRenderer
{
    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * PagerRenderer constructor.
     * @param EngineInterface $engine
     */
    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
    }

    /**
     * @return EngineInterface
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @param PagerView $pager
     * @param array $options
     *
     * @return string
     */
    public function render(PagerView $pager, array $options = [])
    {
        if($pager->isRendered()) {
            #return '';
        }
        $pager->setRendered();
        return $this->engine->render($pager, $options);
    }
}