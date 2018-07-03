1. Create Admin class

Example: Check Raw\Bundle\UserBundle\Admin\UserAdmin

2. Register admin class

Example:
```
    bundle.admin.your_admin:
        class: Bundle\Namespace\Admin\YourAdmin
        tags:
            - { name: raw.admin, alias: your-alias }
```

3. Go to /crud/your-alias