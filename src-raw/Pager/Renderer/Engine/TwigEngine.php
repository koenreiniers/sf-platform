<?php
namespace Raw\Pager\Renderer\Engine;

use Raw\Pager\PagerView;

class TwigEngine implements EngineInterface
{
    /**
     * @var \Twig_Environment
     */
    private $environment;

    public function setEnvironment(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function render(PagerView $pager, array $options = [])
    {
        $defaults = [
            'template' => 'bootstrap_3_pager.html.twig',
        ];
        $options = array_merge($defaults, $options);
        return $this->environment->render($options['template'], $pager->vars);
    }
}