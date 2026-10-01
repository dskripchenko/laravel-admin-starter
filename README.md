# dskripchenko/laravel-admin-starter

> 🌐 **English** · [Русский](docs/ru/README.md) · [Deutsch](docs/de/README.md) · [中文](docs/zh/README.md)

Ready-made system resources for an admin panel: **Users**, **Roles** and a read-only
**Audit Log**, together with their permissions. A drop-in starting point for new panels.

A sister-pack for [`dskripchenko/laravel-admin`](https://github.com/dskripchenko/laravel-admin).
Application settings are not part of this package — they live in the core.

[![Packagist](https://img.shields.io/packagist/v/dskripchenko/laravel-admin-starter)](https://packagist.org/packages/dskripchenko/laravel-admin-starter)
[![License](https://img.shields.io/packagist/l/dskripchenko/laravel-admin-starter)](LICENSE)

## Requirements

- PHP 8.2+
- Laravel 11, 12 or 13
- `dskripchenko/laravel-admin` ^1.30

## Install

```bash
composer require dskripchenko/laravel-admin-starter
php artisan migrate
```

The package has no migrations of its own: the tables (`admin_users`, `admin_roles`,
`admin_audit_logs`) come from the core. The plugin auto-registers via Laravel package
discovery. To publish the config:

```bash
php artisan vendor:publish --tag=admin-starter-config
```

## What it adds

| Resource | Slug | Permissions |
|---|---|---|
| Users | `system-users` | `admin.system.users.{view,create,update,delete}` |
| Roles | `system-roles` | `admin.system.roles.{view,create,update,delete}` |
| Audit Log (read-only) | `system-audit` | `admin.system.audit.view` |

Each resource can be switched off in `config/admin-starter.php`. User-facing strings
use Russian source text as translation keys; an English translation ships in
`resources/lang/en.json`.

## Documentation

- [Getting started](docs/en/getting-started.md)
- [Usage](docs/en/usage.md)

## License

[MIT](LICENSE) © Denis Skripchenko
