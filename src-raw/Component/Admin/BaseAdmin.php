<?php
namespace Raw\Component\Admin;

use Raw\Component\Admin\Easy\Element\FormElement;
use Raw\Component\Admin\Easy\Element\GridElement;
use Raw\Component\Admin\Easy\Element\LayoutElement;
use Raw\Component\Admin\Layout\Definition\Builder\ArrayNodeDefinition;
use Raw\Component\Admin\Layout\Definition\Builder\ContentNodeDefinition;
use Raw\Component\Admin\Layout\Definition\Builder\FormNodeDefinition;
use Raw\Component\Layout\Action\Action;
use Raw\Component\Layout\Action\ActionCollectionBuilder;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BaseAdmin extends Admin implements AdminInterface
{
    protected function configure()
    {

    }

    public function getParent()
    {
        return null;
    }

    public function configureActions(ActionCollectionBuilder $actions)
    {
        $actions
            ->callbackAdd('return_to_view', function(Action $action){
                $action
                    ->setType('return')
                    ->setLabel('Return')
                    ->setRoute('view')
                    ->setRouteParametersResolver(function($entity){
                        return [
                            'id' => $entity->getId(),
                        ];
                    })
                ;
            })
            ->callbackAdd('create', function(Action $action){
                $action
                    ->setType('create')
                    ->setLabel('Create')
                    ->setRoute('create')
                    ->setLevel('success')
                ;
            })
            ->callbackAdd('edit', function(Action $action){
                $action
                    ->setType('edit')
                    ->setLabel('Edit')
                    ->setRoute('edit')
                    ->setLevel('primary')
                    ->setRouteParametersResolver(function($entity){
                        return [
                            'id' => $entity->getId(),
                        ];
                    })
                ;
            })
            ->callbackAdd('delete', function(Action $action){
                $action
                    ->setType('delete')
                    ->setLabel('Delete')
                    ->setRoute('delete')
                    ->setLevel('danger')
                    ->setRouteParametersResolver(function($entity){
                        return [
                            'id' => $entity->getId(),
                        ];
                    })
                    ;
            })
            ->callbackAdd('save', function(Action $action){
                $action
                    ->setType('save')
                    ->setLabel('Save')
                    ->setLevel('success')
                ;
            })
            ->callbackAdd('list', function(Action $action){
                $action
                    ->setType('return')
                    ->setLabel('Return to list')
                    ->setRoute('list')
                ;
            })
            ->callbackAdd('return', function(Action $action){
                $action
                    ->setType('return')
                    ->setLabel('Return')
                ;
            })
            ->callbackAdd('cancel', function(Action $action){
                $action
                    ->setType('return')
                    ->setLabel('Cancel')
                ;
            })
            ->callbackAdd('cancel_create', function(Action $action){
                $action
                    ->setType('return')
                    ->setLabel('Cancel')
                    ->setRoute('index')
                ;
            })
            ->callbackAdd('cancel_edit', function(Action $action){
                $action
                    ->setType('return')
                    ->setLabel('Cancel')
                    ->setRoute('view')
                    ->setRouteParametersResolver(function($entity){
                        return [
                            'id' => $entity->getId(),
                        ];
                    })
                    ;
            })
            ->callbackAdd('cancel_delete', function(Action $action){
                $action
                    ->setType('return')
                    ->setLabel('Cancel')
                    ->setRoute('view')
                    ->setRouteParametersResolver(function($entity){
                        return [
                            'id' => $entity->getId(),
                        ];
                    })
                ;
            })
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'plural' => null,
            'singular' => null,
            'view' => [
                'actions' => ['list', 'edit', 'delete'],
            ],
            'create' => [
                'actions' => ['cancel', 'save'],
            ],
            'edit' => [
                'actions' => ['cancel', 'save'],
            ],
            'list' => [
                'actions' => ['create'],
            ],
            'delete' => [
                'actions' => ['cancel'],
            ],
        ]);
        $resolver->setRequired(['className', 'gridName']);
        $resolver->setNormalizer('plural', function(Options $options, $v){
            if($v !== null) {
                return $v;
            }
            return $this->getPlural($options['className']);
        });
        $resolver->setNormalizer('singular', function(Options $options, $v){
            if($v !== null) {
                return $v;
            }
            return $this->getSingular($options['className']);
        });
    }



    public function buildDeleteLayout(LayoutElement $layout, $entity, array $options)
    {
        $layout->setTitle(sprintf('Delete "%s"', $this->toString($entity)));
    }

    private function getPlural($className)
    {
        return $this->getSingular($className).'s';
    }

    private function getSingular($className)
    {
        $rc = new \ReflectionClass($className);

        $singular = $rc->getShortName();

        $singular = strtolower($this->humanize($singular));
        return $singular;
    }
    /**
     * {@inheritdoc}
     */
    private function humanize($text)
    {
        return ucfirst(trim(strtolower(preg_replace(array('/([A-Z])/', '/[_\s]+/'), array('_$1', ' '), $text))));
    }

    private function toString($entity)
    {
        return method_exists($entity, '__toString') ? (string)$entity : spl_object_hash($entity);
    }

    public function buildCreateLayout(FormElement $layout, $entity, array $options)
    {
        $layout->setTitle('Create new '.$options['singular']);
    }

    public function buildEditLayout(FormElement $layout, $entity, array $options)
    {
        $layout->setTitle(sprintf('Edit "%s"', $this->toString($entity)));
    }

    public function buildListLayout(LayoutElement $layout, array $options)
    {
        $layout->setTitle('List of '.$options['plural']);;
    }

    public function buildViewLayout(LayoutElement $layout, $entity, array $options)
    {
        $layout->setTitle(sprintf('%s "%s"', ucfirst($options['singular']), $this->toString($entity)));
        ;
    }
}