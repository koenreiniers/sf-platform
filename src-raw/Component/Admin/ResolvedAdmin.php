<?php
namespace Raw\Component\Admin;


use Raw\Component\Admin\Easy\Element\FormElement;
use Raw\Component\Admin\Easy\Element\LayoutElement;
use Raw\Component\Admin\Layout\Definition\Builder\ArrayNodeDefinition;
use Raw\Component\Admin\Layout\Definition\Builder\ContentNodeDefinition;
use Raw\Component\Admin\Routing\AdminUrlGenerator;
use Raw\Component\Layout\Action\ActionCollection;
use Raw\Component\Layout\Action\ActionCollectionBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ResolvedAdmin
{
    /**
     * @var Admin
     */
    private $innerAdmin;

    /**
     * @var ResolvedAdmin|null
     */
    private $parent;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @var ActionCollection
     */
    private $actionCollection;

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var AdminUrlGenerator
     */
    private $urlGenerator;

    /**
     * ResolvedAdmin constructor.
     * @param Admin $innerAdmin
     * @param ResolvedAdmin $parent
     * @param AdminUrlGenerator $urlGenerator
     */
    public function __construct($alias, Admin $innerAdmin, ResolvedAdmin $parent = null, AdminUrlGenerator $urlGenerator)
    {
        $this->innerAdmin = $innerAdmin;
        $this->parent = $parent;
        $this->alias = $alias;
        $this->urlGenerator = $urlGenerator;
    }

    public function getActions()
    {
        if($this->actionCollection === null) {
            $builder = new ActionCollectionBuilder();
            if($this->parent !== null) {
                foreach($this->parent->getActions() as $action) {
                    $builder->addAction($action);
                }
            }
            $this->innerAdmin->configureActions($builder);
            $this->actionCollection = $builder->getCollection();
        }
        return $this->actionCollection;
    }

    public function getOption($name)
    {
        $this->options = $this->getOptionsResolver()->resolve([]);
        return $this->options[$name];
    }

    public function getOptions()
    {
        $this->options = $this->getOptionsResolver()->resolve([]);
        return $this->options;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function getTemplate($alias)
    {
        return $this->innerAdmin->getTemplate($alias);
    }

    public function generateUrl($verb, $parameters = null, $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->urlGenerator->generate($verb, $parameters, $referenceType);
    }

    public function getRoute($verb)
    {
        return $this->urlGenerator->resolveRoute($verb);
    }

    public function getClassName()
    {
        return $this->innerAdmin->getClassName();
    }

    /**
     * @return OptionsResolver
     */
    public function getOptionsResolver()
    {
        if($this->optionsResolver !== null) {
            return $this->optionsResolver;
        }
        if($this->parent !== null) {
            $optionsResolver = clone $this->parent->getOptionsResolver();
        } else {
            $optionsResolver = new OptionsResolver();
        }
        $this->innerAdmin->configureOptions($optionsResolver);

        return $this->optionsResolver = $optionsResolver;
    }

    public function entityAction($name, ContentNodeDefinition $content, ArrayNodeDefinition $actions, $entity, array $options)
    {
        if($this->parent !== null) {
            $this->parent->entityAction($name, $content, $actions, $entity, $options);
        }
        $methodName = $name;
        if(method_exists($this->innerAdmin, $methodName)) {
            call_user_func([$this->innerAdmin, $methodName], $content, $actions, $entity, $options);
        }
    }


    public function createEntity()
    {
        return $this->innerAdmin->createEntity();
    }

    public function buildListLayout(LayoutElement $layout, array $options)
    {
        if($this->parent !== null) {
            $this->parent->buildListLayout($layout, $options);
        }
        $this->innerAdmin->buildListLayout($layout, $options);
    }

    public function buildCreateLayout(FormElement $layout, $entity, array $options)
    {
        if($this->parent !== null) {
            $this->parent->buildCreateLayout($layout, $entity, $options);
        }
        $this->innerAdmin->buildCreateLayout($layout, $entity, $options);
    }

    public function buildEditLayout(FormElement $layout, $entity, array $options)
    {
        if($this->parent !== null) {
            $this->parent->buildEditLayout($layout, $entity, $options);
        }
        $this->innerAdmin->buildEditLayout($layout, $entity, $options);
    }

    public function buildViewLayout(LayoutElement $layout, $entity, array $options)
    {
        if($this->parent !== null) {
            $this->parent->buildViewLayout($layout, $entity, $options);
        }
        $this->innerAdmin->buildViewLayout($layout, $entity, $options);
    }

    public function buildDeleteLayout(LayoutElement $layout, $entity, array $options)
    {
        if($this->parent !== null) {
            $this->parent->buildDeleteLayout($layout, $entity, $options);
        }
        $this->innerAdmin->buildDeleteLayout($layout, $entity, $options);
    }

    /**
     * @param string $elementClass
     * @return LayoutElement
     */
    public function createEmptyLayout($elementClass = LayoutElement::class)
    {


        $content = new $elementClass();

        return $content;
    }

    public function createLayout($verb, $entity = null)
    {
        $options = $this->getOptions();

        $elementClass = LayoutElement::class;

        if(in_array($verb, ['create', 'edit'])) {
            $elementClass = FormElement::class;
        }

        $content = new $elementClass();

        switch($verb) {
            case 'view':
                $this->buildViewLayout($content, $entity, $options);
                break;
            case 'edit':
                $this->buildEditLayout($content, $entity, $options);
                break;
            case 'create':
                $this->buildCreateLayout($content, $entity, $options);
                break;
            case 'list':
                $this->buildListLayout($content, $options);
                break;
            case 'delete':
                $this->buildDeleteLayout($content, $entity, $options);
                break;
            default:
                throw new \Exception('not yet implemented');
        }

        return $content;
    }
}