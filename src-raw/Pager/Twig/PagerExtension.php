<?php
namespace Raw\Pager\Twig;

use Raw\Pager\PagerRenderer;
use Raw\Pager\PagerView;
use Raw\Pager\Renderer\Engine\TwigEngine;

class PagerExtension extends \Twig_Extension implements \Twig_Extension_InitRuntimeInterface
{
    /**
     * @var PagerRenderer
     */
    private $renderer;

    /**
     * PagerExtension constructor.
     * @param PagerRenderer $renderer
     */
    public function __construct(PagerRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('pager_widget', [$this, 'renderPager'], [
                'is_safe' => ['html'],
            ]),
        ];
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $engine = $this->renderer->getEngine();
        if($engine instanceof TwigEngine) {
            $engine->setEnvironment($environment);
        }
    }

    public function renderPager(PagerView $pager, array $options = [])
    {
        return $this->renderer->render($pager, $options);
    }
}