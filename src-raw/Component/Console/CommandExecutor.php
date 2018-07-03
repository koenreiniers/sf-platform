<?php
namespace Raw\Component\Console;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\PhpExecutableFinder;

class CommandExecutor
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var PhpExecutableFinder
     */
    private $phpExecutableFinder;

    /**
     * @var string
     */
    private $consolePath;

    /**
     * CommandExecutor constructor.
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->phpExecutableFinder = new PhpExecutableFinder();
    }

    public function executeBackground($command)
    {
        return $this->execute($command, true);
    }

    public function execute($command, $background = false)
    {
        $cmd = sprintf('%s %s %s', $this->getPhpExecutablePath(), $this->getConsolePath(), $command);

        if($background === true) {
            $cmd = $cmd . ' > /dev/null 2>/dev/null &';
        }
        return exec($cmd);
    }


    private function getPhpExecutablePath()
    {
        return $this->phpExecutableFinder->find();
    }

    private function findConsolePath()
    {
        $rootDir = $this->kernel->getRootDir();
        $binDir = realpath($rootDir.'/../bin');

        $paths = [
            $rootDir.'/console',
            $binDir.'/console',
        ];

        foreach($paths as $path) {
            if(file_exists($path)) {
                return $path;
            }
        }

        throw new \Exception(sprintf('Could not locate console. Looked at: %s', implode(',', $paths)));
    }

    public function getConsolePath()
    {
        if($this->consolePath !== null) {
            return $this->consolePath;
        }
        return $this->consolePath = $this->findConsolePath();
    }
}