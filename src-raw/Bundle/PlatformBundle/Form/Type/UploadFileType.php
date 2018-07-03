<?php
namespace Raw\Bundle\PlatformBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Raw\Bundle\PlatformBundle\Entity\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\VarDumper\VarDumper;

class UploadFileType extends AbstractType
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var string
     */
    private $uploadsDirectory;

    public function __construct()
    {
        global $kernel;
        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.default_entity_manager');
        $this->uploadsDirectory = $kernel->getContainer()->getParameter('raw_platform.uploads_directory');
    }

    public function getBlockPrefix()
    {
        return 'raw_upload_file';
    }

    private function generateUploadPath()
    {
        $path = sprintf('%s/%s/%s/%s', $this->uploadsDirectory, date('Y'), date('m'), date('d'));

        if(!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        return $path;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('entity', EntityType::class, [
            'class' => File::class,
            'choice_label' => 'id',
            'required' => false,
        ]);
        $builder->add('file', FileType::class, [
            'required' => false,
        ]);

        $builder->addModelTransformer(new CallbackTransformer(function(File $file = null){
            return [
                'entity' => $file,
                'file' => null,
            ];
        },function($data){
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $data['file'];

            if($uploadedFile === null) {
                return $data['entity'];
            }

            $filename = $uploadedFile->getClientOriginalName();
            $originalName = $uploadedFile->getClientOriginalName();
            $mimeType = $uploadedFile->getClientMimeType();
            $extension = $uploadedFile->getClientOriginalExtension();
            $path = $this->generateUploadPath();

            while(file_exists($path.'/'.$filename)) {
                $filename = pathinfo($filename, PATHINFO_FILENAME).'_'.'.'.$extension;
            }

            $uploadedFile->move($path, $filename);

            $file = new File();
            $file->setPath($path);
            $file->setFilename($filename);
            $file->setOriginalName($originalName);
            $file->setMimeType($mimeType);
            $file->setExtension($extension);

            return $file;
        }));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }
}