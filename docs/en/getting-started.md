---
title: Getting Started
locale: en
status: stable
---

# Getting Started

`dskripchenko/laravel-admin-starter` is a sister-pack of `dskripchenko/laravel-admin`.
Install once — it auto-registers and surfaces in your admin.

## Install

```bash
composer require dskripchenko/laravel-admin-starter
php artisan migrate
```

## Configure

```bash
php artisan vendor:publish --tag=starter-config
```

Edit `config/starter.php`.


## What it adds

After install, your admin gets:

| Resource | URL | Permission |
|---|---|---|
| Admin Users | `/admin/r/admin-users` | `admin.admin-users.*` |
| Roles | `/admin/r/admin-roles` | `admin.admin-roles.*` |
| Audit log | `/admin/r/audit-log` | `admin.audit-log.view` |
| Settings | `/admin/settings_app` | `admin.settings.app.*` |
| Translations | `/admin/r/translations` | `admin.translations.*` |
| Content blocks | `/admin/r/content-blocks` | `admin.content-blocks.*` |

A default super-admin role is seeded on first migration.

## See also

- [Usage](usage.md)
- [Glossary](https://github.com/dskripchenko/laravel-admin/blob/main/docs/en/glossary.md)
