<?php
namespace Raw\Bundle\VueBundle\Twig;

use Raw\Bundle\VueBundle\Vue\Vue;
use Raw\Bundle\VueBundle\Vue\VueApp;
use Raw\Bundle\VueBundle\Vue\VueRegistry;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class VueExtension extends \Twig_Extension implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var VueRegistry
     */
    private $vueRegistry;

    private function initDeps()
    {
        $this->vueRegistry = $this->container->get('raw_vue.registry');
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('raw_vue_compile_templates', [$this, 'compileTemplates'], [
                'needs_environment' => true,
                'is_safe' => ['html'],
            ]),
        ];
    }

    private function tempApp($srcDir)
    {

        $templates = Vue::generateTemplatePaths($srcDir);

        return [
            'templatePaths' => $templates,
        ];
    }

    public function compileTemplates(\Twig_Environment $environment, $srcDir)
    {
        $app = $this->tempApp($srcDir);
        return $environment->render('RawVueBundle:Compiled:app.html.twig', [
            'vueApp' => $app,
        ]);
    }
}