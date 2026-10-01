---
title: Использование
locale: ru
status: stable
translated_from: ../en/usage.md
---

# Использование

## Отключение ресурсов

Опубликуйте конфиг (`php artisan vendor:publish --tag=admin-starter-config`) и выставьте переключатель в `false`:

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

Права регистрируются независимо от переключателей, поэтому существующие роли, которые на них ссылаются, остаются валидными.

## Замена ресурса

Ресурсы пакета объявлены как `final`. Чтобы изменить ресурс, отключите его и зарегистрируйте свой над той же моделью:

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
