---
title: Usage
locale: en
status: stable
---

# Usage

```php
// Disable specific resources you don't need:
'starter' => [
    'resources' => [
        'admin_users' => true,
        'admin_roles' => true,
        'audit_log' => true,
        'settings' => true,
        'translations' => false,
        'content_blocks' => false,
    ],
],
```

To customize a resource, extend the starter resource and register your
own:

```php
class MyAdminUserResource extends \Dskripchenko\LaravelAdminStarter\Resources\AdminUserResource
{
    public function fields(): array
    {
        return array_merge(parent::fields(), [
            Input::make('phone'),
        ]);
    }
}

Admin::resources([MyAdminUserResource::class]);
```

