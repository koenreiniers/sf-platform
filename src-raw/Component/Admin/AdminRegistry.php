<?php
namespace Raw\Component\Admin;

class AdminRegistry
{
    /**
     * @var Admin[]
     */
    private $admins = [];

    /**
     * @var array
     */
    private $aliases = [];

    /**
     * @var ResolvedAdmin[]
     */
    private $resolvedAdmins = [];

    /**
     * @var ResolvedAdminFactory
     */
    private $resolvedAdminFactory;

    /**
     * AdminRegistry constructor.
     * @param Admin[] $admins
     * @param ResolvedAdminFactory $resolvedAdminFactory
     */
    public function __construct(array $admins, array $aliases, ResolvedAdminFactory $resolvedAdminFactory)
    {
        $this->admins = $admins;
        $this->aliases = $aliases;
        $this->resolvedAdminFactory = $resolvedAdminFactory;
    }

    private function resolveName($slug)
    {
        return isset($this->aliases[$slug]) ? $this->aliases[$slug] : $slug;
    }

    /**
     * @return ResolvedAdmin[]
     */
    public function getAdmins()
    {
        foreach($this->admins as $admin) {
            $this->getAdmin(get_class($admin));
        }
        return $this->resolvedAdmins;
    }

    /**
     * @param string $slug
     * @return ResolvedAdmin
     * @throws \Exception
     */
    public function getAdmin($slug)
    {
        $alias = $slug;

        $className = $this->resolveName($alias);

        if(!$this->hasAdmin($className)) {
            throw new \Exception(sprintf('No admin found for "%s"', $slug));
        }
        if(isset($this->resolvedAdmins[$className])) {
            return $this->resolvedAdmins[$className];
        }

        $admin = $this->admins[$className];

        $parent = null;
        if($admin->getParent() !== null) {
            $parent = $this->getAdmin($admin->getParent());
        }

        $alias = $className;
        foreach($this->aliases as $k => $v) {
            if($v === $className) {
                $alias = $k;
                break;
            }
        }

        return $this->resolvedAdmins[$className] = $this->resolvedAdminFactory->create($alias, $admin, $parent);
    }

    public function hasAdmin($slug)
    {
        $slug = $this->resolveName($slug);

        return isset($this->admins[$slug]);
    }
}