# dskripchenko/laravel-admin-starter

> 🌐 [English](../../README.md) · [Русский](../ru/README.md) · **Deutsch** · [中文](../zh/README.md)

Fertige System-Resourcen für ein Admin-Panel: **Benutzer**, **Rollen** und ein schreibgeschütztes **Audit-Log**, zusammen mit ihren Berechtigungen. Ein Plug-and-Play-Startpunkt für neue Panels.

Ein Sister-Pack für [`dskripchenko/laravel-admin`](https://github.com/dskripchenko/laravel-admin). Anwendungseinstellungen gehören nicht zu diesem Paket — sie befinden sich im Core.

## Voraussetzungen

- PHP 8.2+
- Laravel 11, 12 oder 13
- `dskripchenko/laravel-admin` ^1.30

## Installation

```bash
composer require dskripchenko/laravel-admin-starter
php artisan migrate
```

Das Paket hat keine eigenen Migrationen: Die Tabellen (`admin_users`, `admin_roles`, `admin_audit_logs`) stammen aus dem Core. Das Plugin registriert sich automatisch über Laravel Package Discovery. Konfiguration veröffentlichen:

```bash
php artisan vendor:publish --tag=admin-starter-config
```

## Was es hinzufügt

| Resource | Slug | Berechtigungen |
|---|---|---|
| Benutzer | `system-users` | `admin.system.users.{view,create,update,delete}` |
| Rollen | `system-roles` | `admin.system.roles.{view,create,update,delete}` |
| Audit-Log (schreibgeschützt) | `system-audit` | `admin.system.audit.view` |

Jede Resource lässt sich in `config/admin-starter.php` abschalten. Oberflächentexte verwenden den russischen Quelltext als Übersetzungsschlüssel; eine englische Übersetzung liegt in `resources/lang/en.json`.

## Dokumentation

- [Erste Schritte](getting-started.md)
- [Verwendung](usage.md)

## Lizenz

[MIT](../../LICENSE) © Denis Skripchenko
