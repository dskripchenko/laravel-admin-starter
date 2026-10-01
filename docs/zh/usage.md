---
title: 使用
locale: zh
status: stable
translated_from: ../en/usage.md
---

# 使用

## 关闭资源

发布配置（`php artisan vendor:publish --tag=admin-starter-config`），并将开关设为 `false`：

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

无论开关如何，权限都会被注册，因此引用这些权限的现有角色仍然有效。

## 替换资源

本包的资源声明为 `final`。如需自定义，请关闭该资源，并基于同一模型注册您自己的资源：

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
