<?php
namespace Raw\Bundle\PlatformBundle\Controller;

use Raw\Bundle\PlatformBundle\Entity\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\VarDumper\VarDumper;

class FileController extends Controller
{
    public function viewAction($path)
    {
        $fullPath = $this->getParameter('raw_platform.uploads_directory').'/'.$path;

        $file = new \SplFileInfo($fullPath);

        $path = $file->getPath();
        $originalName = $file->getFilename();

        $file = $this->getDoctrine()->getRepository(File::class)->findOneBy([
            'path' => $path,
            'originalName' => $originalName,
        ]);

        $realPath = $file->getRealpath();

        return new BinaryFileResponse($realPath);
    }

    public function previewAction($id)
    {
        $file = $this->getDoctrine()->getRepository(File::class)->findOneBy([
            'id' => $id,
        ]);

        $realPath = $file->getRealpath();

        return new BinaryFileResponse($realPath);
    }
}