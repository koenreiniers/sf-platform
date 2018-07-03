<?php
namespace Raw\Component\Layout\Renderer;

use Raw\Component\Admin\Easy\Element;
use Raw\Component\Admin\Layout\Definition\ArrayNode;
use Raw\Component\Admin\Layout\Definition\TemplateNode;
use Raw\Component\Layout\LayoutRendererInterface;

use Raw\Component\Admin\Layout\Definition\FieldsetNode;
use Raw\Component\Admin\Layout\Definition\FormNode;

use Raw\Component\Admin\Layout\Definition\ActionNode;

use Raw\Component\Admin\Layout\Definition\FormRowNode;
use Raw\Component\Admin\Layout\Definition\GridNode;
use Raw\Component\Admin\Layout\Definition\Node;
use Raw\Component\Admin\Layout\Definition\TabsNode;
use Twig\Template;

class TwigRenderer implements LayoutRendererInterface
{

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var array
     */
    private $templates;

    /**
     * @var null|Template
     */
    private $theme;

    /**
     * TwigRenderer constructor.
     * @param \Twig_Environment $twig
     * @param array $templates
     */
    public function __construct(\Twig_Environment $twig, array $templates)
    {
        $this->twig = $twig;
    }


    private $resources;

    private function getResources($cacheKey, Template $theme)
    {
        if($this->resources !== null) {
            return;
        }
        // TODO


        if (null === $this->theme) {
            // Store the first Template instance that we find so that
            // we can call displayBlock() later on. It doesn't matter *which*
            // template we use for that, since we pass the used blocks manually
            // anyway.
            $this->theme = $theme;
        }

        // Use a separate variable for the inheritance traversal, because
        // theme is a reference and we don't want to change it.
        $currentTheme = $theme;

        $context = $this->twig->mergeGlobals(array());

        // The do loop takes care of template inheritance.
        // Add blocks from all templates in the inheritance tree, but avoid
        // overriding blocks already set.
        do {
            foreach ($currentTheme->getBlocks() as $block => $blockData) {
                if (!isset($this->resources[$cacheKey][$block])) {
                    // The resource given back is the key to the bucket that
                    // contains this block.
                    $this->resources[$cacheKey][$block] = $blockData;
                }
            }
        } while (false !== $currentTheme = $currentTheme->getParent($context));
    }

    public function renderNew(Element $element, array $context = [], array $opts = [])
    {
        $cacheKey = 'a';
        if($this->theme === null) {
            $this->getResources($cacheKey, $this->twig->loadTemplate('RawAdminBundle:Theme:bootstrap_3_new.html.twig'));
        }

        $node = $element;

        $view = $context;
        $view['node'] = $node;

        $nodeName = get_class($node);

        $blockNames = [
            Element\FormElement::class => 'form',
            Element\TabsetElement::class => 'tabset',
            Element\GridElement::class => 'grid',
            //ActionEle::class => 'action',
            Element\FieldElement::class => 'field',
            Element\FieldsetElement::class => 'fieldset',
            Element\ControllerElement::class => 'controller',
        ];

        $blockName = isset($blockNames[$nodeName]) ? $blockNames[$nodeName] : null;

        if($blockName !== null && isset($this->resources[$cacheKey][$blockName])) {
            return $this->theme->displayBlock($blockName, $view);
        } else if($node instanceof Element\TemplateElement) {
            $context = array_merge($view, $node->getAttribute('context'));
            return $this->twig->render($node->getAttribute('name'), $context);
        } else if($node instanceof Element\LayoutElement) {
            $out = '';
            foreach($node as $childNode) {
                $out .= $this->renderNew($childNode, $context, $opts);
            }
            return $out;
        }


        throw new \Exception(sprintf('Cannot display node of class "%s"', get_class($node)));
    }


    public function render(Node $node, array $context = [], array $opts = [])
    {
        $cacheKey = 'a';
        if($this->theme === null) {
            $this->getResources($cacheKey, $this->twig->loadTemplate('RawAdminBundle:Theme:bootstrap_3.html.twig'));
        }

        $view = $context;
        $view['node'] = $node;

        $nodeName = get_class($node);

        $blockNames = [
            FormNode::class => 'form',
            TabsNode::class => 'tabset',
            GridNode::class => 'grid',
            ActionNode::class => 'action',
            FormRowNode::class => 'field',
            FieldsetNode::class => 'fieldset',
        ];

        $blockName = isset($blockNames[$nodeName]) ? $blockNames[$nodeName] : null;

        if($blockName !== null && isset($this->resources[$cacheKey][$blockName])) {
            return $this->theme->displayBlock($blockName, $view);
        } else if($node instanceof TemplateNode) {
            $context = array_merge($view, $node->getAttribute('context'));
            return $this->twig->render($node->getAttribute('template'), $context);
        } else if($node instanceof ArrayNode) {
            $out = '';
            foreach($node as $childNode) {
                $out .= $this->render($childNode, $context, $opts);
            }
            return $out;
        }


        throw new \Exception(sprintf('Cannot display node of class "%s"', get_class($node)));

    }
}