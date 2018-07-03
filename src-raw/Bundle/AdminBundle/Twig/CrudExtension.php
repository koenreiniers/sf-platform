<?php
namespace Raw\Bundle\AdminBundle\Twig;


use Raw\Component\Admin\Easy\Element;
use Raw\Component\Admin\Easy\Element\LayoutElement;
use Raw\Component\Layout\LayoutRendererInterface;
use Raw\Component\Layout\Renderer\TwigRenderer;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

use Raw\Component\Admin\Layout\Definition\Node;

class CrudExtension extends \Twig_Extension implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var LayoutRendererInterface
     */
    private $renderer;

    private function initDeps()
    {
        if($this->renderer === null) {
            $this->renderer = $this->container->get('raw_admin.layout.renderer');
        }

    }

    public function renderLayout($context, Element $layout, array $options = [])
    {
        $this->initDeps();
        $context = is_array($context) ? $context : [];

        return $this->renderer->renderNew($layout, $context, $options);

        $node = $layout->toNode()->createLayout();

        return $this->renderer->render($node, $context, $options);
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('layout_render', [$this, 'renderLayout'], [
                'needs_context' => true,
                'is_safe' => ['html'],
            ]),
            new \Twig_SimpleFunction('layout_node', [$this, 'displayNode'], [
                'needs_context' => true,
                'is_safe' => ['html'],
            ]),
        ];
    }

    public function displayNode($context, Node $node, array $options = [])
    {
        $this->initDeps();
        $context = is_array($context) ? $context : [];
        return $this->renderer->render($node, $context, $options);
    }
}