---
title: Usage
locale: en
status: stable
---

# Usage

## Switching resources off

Publish the config (`php artisan vendor:publish --tag=admin-starter-config`) and set a
toggle to `false`:

```php
// config/admin-starter.php
return [
    'resources' => [
        'users' => true,
        'roles' => true,
        'audit_log' => false, // keep the audit log out of the panel
    ],
];
```

The permissions are registered regardless of the toggles, so existing roles that
reference them stay valid.

## Replacing a resource

The starter resources are `final`. To customize one, switch it off and register your
own resource over the same model:

```php
use Dskripchenko\LaravelAdmin\Facades\Admin;
use Dskripchenko\LaravelAdmin\Field\Input;
use Dskripchenko\LaravelAdmin\Models\AdminUser;
use Dskripchenko\LaravelAdmin\Resource\Resource;

final class MyUserResource extends Resource
{
    public static string $model = AdminUser::class;

    public static function permission(): string
    {
        return 'admin.system.users'; // reuse the starter's permission keys
    }

    public function fields(): array
    {
        return [
            Input::make('name')->required()->title(__('Имя')),
            Input::make('phone'),
        ];
    }
}

// config/admin-starter.php: 'users' => false
Admin::resources([MyUserResource::class]);
```
