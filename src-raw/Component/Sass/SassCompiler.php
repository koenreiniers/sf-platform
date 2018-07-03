<?php
namespace Raw\Component\Sass;

class SassCompiler
{
    public function compile(SassApp $app)
    {
        global $kernel;
        $out = '';
        foreach($app->getImports() as $import) {
            $import = $kernel->locateResource($import);
            $out .= sprintf("@import '%s';", $import);
        }
        return $out;
    }
}