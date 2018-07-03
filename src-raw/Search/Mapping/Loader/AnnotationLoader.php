<?php
namespace Raw\Search\Mapping\Loader;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;
use Raw\Search\Mapping\ClassMetadata;

use Raw\Search\Mapping;
use Symfony\Component\VarDumper\VarDumper;

class AnnotationLoader implements LoaderInterface
{

    /**
     * @var AnnotationReader
     */
    private $reader;

    public function __construct()
    {
        $this->reader = new AnnotationReader(new DocParser());
    }

    /**
     * @inheritdoc
     */
    public function loadClassMetadata(ClassMetadata $classMetadata)
    {
        $ann = $this->reader->getClassAnnotation($classMetadata->getReflectionClass(), Mapping\Document::class);

        if($ann === null) {
            return false;
        }

        $mapping = [
            'type' => $ann->type,
            'indexes' => $ann->indexes,
            'fields' => [],
            'filters' => [],
        ];

        $classAnnotations = $this->reader->getClassAnnotations($classMetadata->getReflectionClass());

        $constantAnnotations = array_filter($classAnnotations, function($classAnnotation){
            return $classAnnotation instanceof Mapping\Constant;
        });

        foreach($constantAnnotations as $annotation) {
            $mapping['fields'][$annotation->name] = [
                'type' => $annotation->type,
                'indexes' => $annotation->indexes,
                'name' => $annotation->name,
                'value' => $annotation->value,
                'encoding' => $annotation->encoding,
            ];
        }
        $filterAnnotations = array_filter($classAnnotations, function($classAnnotation){
            return $classAnnotation instanceof Mapping\Filter;
        });

        foreach($filterAnnotations as $filterAnnotation) {
            $mapping['filters'][] = [
                'expr' => $filterAnnotation->expr,
                'indexes' => $filterAnnotation->indexes,
            ];
        }

        foreach($classMetadata->getReflectionClass()->getProperties() as $rp) {
            $annotations = $this->reader->getPropertyAnnotations($rp);

            $fieldAnnotations = array_filter($annotations, function($annotation){
                return $annotation instanceof Mapping\Field;
            });

            if(count($fieldAnnotations) === 0) {
                continue;
            }
            foreach($fieldAnnotations as $annotation) {

                $propertyName = $rp->getName();
                $fieldName = $annotation->name ?: $propertyName;

                $mapping['fields'][$fieldName] = [
                    'type' => $annotation->type,
                    'indexes' => $annotation->indexes,
                    'name' => $fieldName,
                    'encoding' => $annotation->encoding,
                    'property' => $propertyName,
                ];

            }
        }


        $loader = new ArrayLoader([
            $classMetadata->getClassName() => $mapping,
        ]);

        return $loader->loadClassMetadata($classMetadata);
    }
}