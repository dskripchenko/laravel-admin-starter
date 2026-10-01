---
title: Verwendung
locale: de
status: stable
translated_from: ../en/usage.md
---

# Verwendung

## Resourcen abschalten

Veröffentlichen Sie die Konfiguration (`php artisan vendor:publish --tag=admin-starter-config`) und setzen Sie einen Schalter auf `false`:

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

Die Berechtigungen werden unabhängig von den Schaltern registriert, sodass bestehende Rollen, die darauf verweisen, gültig bleiben.

## Eine Resource ersetzen

Die Starter-Resourcen sind `final`. Um eine anzupassen, schalten Sie sie ab und registrieren eine eigene Resource über demselben Modell:

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
