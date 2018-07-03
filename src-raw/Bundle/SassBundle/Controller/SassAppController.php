<?php
namespace Raw\Bundle\SassBundle\Controller;

use Raw\Component\Sass\SassCompiler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SassAppController extends Controller
{
    public function viewAction(Request $request, $name)
    {
        $app = $this->get('raw_sass.sass')->getApp($name);

        $result = [
            'name' => $app->getName(),
            'variables' => $app->getVariables(),
        ];

        return new JsonResponse($result);
    }
}