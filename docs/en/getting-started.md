---
title: Getting Started
locale: en
status: stable
---

# Getting Started

`dskripchenko/laravel-admin-starter` is a sister-pack of `dskripchenko/laravel-admin`.
Install it once — it auto-registers and its resources appear in the panel.

## Install

```bash
composer require dskripchenko/laravel-admin-starter
php artisan migrate
```

The package has no migrations of its own; `migrate` creates the core's tables if they
are not there yet.

## Configure

```bash
php artisan vendor:publish --tag=admin-starter-config
```

Edit `config/admin-starter.php`.

## What it adds

After install, the panel gets three resources in the "System" sidebar group:

| Resource | Slug | Permissions |
|---|---|---|
| Users | `system-users` | `admin.system.users.{view,create,update,delete}` |
| Roles | `system-roles` | `admin.system.roles.{view,create,update,delete}` |
| Audit Log | `system-audit` | `admin.system.audit.view` |

- **Users** — CRUD over the core's `AdminUser`: name, email, password (on create), locale,
  theme and the active flag.
- **Roles** — CRUD over the core's `Role`. The permission field suggests every key known
  to the panel, grouped by resource, plus glob masks (`admin.*`, `admin.content.*`, `*`).
  Roles whose slug starts with a prefix from `admin.roles.hidden_slug_prefixes` (core
  config) are hidden.
- **Audit Log** — a read-only list and detail view over `admin_audit_logs`: event, actor,
  subject, IP address and the recorded changes.

Application settings are provided by the core itself, not by this package.

## Translations

User-facing strings use Russian source text as translation keys. The package ships
`resources/lang/en.json`, so a panel running in the `en` locale shows English labels.
Add your own `lang/{locale}.json` to translate into other languages.

## See also

- [Usage](usage.md)
- [Glossary](https://github.com/dskripchenko/laravel-admin/blob/main/docs/en/glossary.md)
