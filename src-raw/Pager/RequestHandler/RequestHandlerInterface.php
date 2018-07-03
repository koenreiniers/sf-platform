<?php
namespace Raw\Pager\RequestHandler;

use Raw\Pager\Pager;

interface RequestHandlerInterface
{
    /**
     * @param Pager $pager
     * @param mixed $request
     */
    public function handle(Pager $pager, $request = null);

    /**
     * @param Pager $pager
     * @param null $request
     */
    public function terminate(Pager $pager, $request = null);
}