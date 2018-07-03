<?php
namespace Raw\Bundle\PlatformBundle\Twig;

use Raw\Bundle\PlatformBundle\Twig\Parser\ComponentTokenParser;
use Raw\Bundle\PlatformBundle\Twig\Parser\EndcomponentTokenParser;

class ComponentExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('select_component', [$this, 'selectComponent'], [

            ]),
            new \Twig_SimpleFunction('comp', [$this, 'displayComponent'], [
                'needs_environment' => true,
                'is_safe' => ['html'],
            ]),
            new \Twig_SimpleFunction('component_start', [$this, 'componentStart'], [
                'needs_environment' => true,
                'is_safe' => ['html'],
            ]),
            new \Twig_SimpleFunction('component_end', [$this, 'componentEnd'], [
                'needs_environment' => true,
                'is_safe' => ['html'],
            ])
        ];
    }

    private $components = [
        'box' => [
            'templateName' => 'RawPlatformBundle:Component:box.html.twig',
            'template' => null,
        ],
        'button' => [
            'templateName' => 'RawPlatformBundle:Component:button.html.twig',
            'template' => null,
        ],
    ];

    private $currentComponent = null;

    private $stack = [];

    public function getTokenParsers()
    {
        return [
            new ComponentTokenParser(),
            new EndcomponentTokenParser(),
        ];
    }


    public function getComponent(\Twig_Environment $environment, $name)
    {
        $comp = $this->components[$name];
        if($comp['template'] === null) {
            $comp['template'] = $environment->loadTemplate($comp['templateName']);
        }
        return $comp;
    }

    public function displayComponent(\Twig_Environment $environment, $name, $content, array $context = [])
    {
        $this->componentStart($environment, $name, $context);
        echo $content;
        $this->componentEnd($environment, $context);
    }

    public function componentStart(\Twig_Environment $environment, $name, array $context = [])
    {
        if($this->currentComponent === null) {
            $this->currentComponent = $this->getComponent($environment, $name);
        }

        $this->stack[] = $name;

        /** @var \Twig_Template $template */
        $template = $this->currentComponent['template'];

        $template->displayBlock($name.'_start', $context);
    }

    public function componentEnd(\Twig_Environment $environment, array $context = [])
    {
        $name = array_pop($this->stack);
        if($this->currentComponent === null) {
            $this->currentComponent = $this->getComponent($environment, $name);
        }

        //array_pop($this->stack);

        /** @var \Twig_Template $template */
        $template = $this->currentComponent['template'];

        if(count($this->stack) === 0) {
            $this->currentComponent = null;
        }

        $template->displayBlock($name.'_end', $context);
    }
}