<?php
namespace Raw\Filter\Type;

use Doctrine\ORM\EntityManager;
use Raw\Filter\Exception\FilterValidationException;
use Raw\Filter\FilterType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Raw\Filter\Expr\Comparison as Op;
use Symfony\Component\PropertyAccess\PropertyAccess;

class EntityFilterType extends FilterType
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * EntityFilterType constructor.
     * @param EntityManager $entityManager
     */
    public function __construct()
    {
        #$this->entityManager = $entityManager;
    }


    public function validate($operator, array $data, array $options)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        global $kernel;
        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.default_entity_manager');
        $resolver->setDefaults([
            'input_type' => 'select',
            'operators' => [Op::EQ, Op::NEQ],
            'class' => null,
            'choice_label' => null,
        ]);
        $resolver->setRequired(['class']);
        $resolver->setNormalizer('choices', function(Options $options){
            $accessor = PropertyAccess::createPropertyAccessor();
            $class = $options['class'];
            $choiceLabel = $options['choice_label'];
            $repository = $this->entityManager->getRepository($class);
            $entities = $repository->findAll();
            $choices = [];
            foreach($entities as $entity) {
                $label = $entity->getId();
                $label = $accessor->getValue($entity, $choiceLabel);
                $choices[$label] = $entity->getId();
            }
            return $choices;
        });
    }
}