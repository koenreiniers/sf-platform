<?php
namespace Raw\Component\Grid\Mapping\Loader;

use Doctrine\ORM\EntityManager;
use Raw\Component\Grid\Grid;
use Raw\Component\Grid\Mapping\GridMetadataBuilder;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\Yaml\Yaml;

class SimpleEntityLoader implements LoaderInterface
{
    /**
     * @var array
     */
    private $pathsByName = [];

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * YamlFileLoader constructor.
     * @param array $pathsByName
     */
    public function __construct(array $pathsByName, EntityManager $entityManager)
    {
        $this->pathsByName = $pathsByName;
        $this->entityManager = $entityManager;
    }

    public function load(GridMetadataBuilder $gridMetadata)
    {
        if(!isset($this->pathsByName[$gridMetadata->getName()]) || count($this->pathsByName[$gridMetadata->getName()]) === 0) {
            return false;
        }
        $name = $gridMetadata->getName();

        $className = $this->pathsByName[$name];

        $classMetadata = $this->entityManager->getClassMetadata($className);

        $dataSourceConfig = [
            'type' => 'orm',
            'options' => [
                'query' => [
                    'from' => [
                        'o' => $className,
                    ],
                    'select' => ['o'],
                ],
            ],
        ];

        $columns = [];
        foreach($classMetadata->getColumnNames() as $columnName) {
            $columns[$columnName] = [
                'label' => $columnName,
            ];
        }
        $columns = array_slice($columns, 0, 5);

        $gridMetadata->addExtensionConfig('data_source', $dataSourceConfig);
        $gridMetadata->addExtensionConfig('columns', $columns);

        return true;
    }


}