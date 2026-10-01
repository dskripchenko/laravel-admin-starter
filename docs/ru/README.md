# dskripchenko/laravel-admin-starter

> 🌐 [English](../../README.md) · **Русский** · [Deutsch](../de/README.md) · [中文](../zh/README.md)

Готовые системные ресурсы для админ-панели: **Пользователи**, **Роли** и **Журнал аудита** (только чтение) вместе с их правами. Отправная точка для новой панели.

Sister-pack для [`dskripchenko/laravel-admin`](https://github.com/dskripchenko/laravel-admin). Настройки приложения в пакет не входят — они есть в ядре.

## Требования

- PHP 8.2+
- Laravel 11, 12 или 13
- `dskripchenko/laravel-admin` ^1.30

## Установка

```bash
composer require dskripchenko/laravel-admin-starter
php artisan migrate
```

Собственных миграций у пакета нет: таблицы (`admin_users`, `admin_roles`, `admin_audit_logs`) создаёт ядро. Плагин регистрируется автоматически через Laravel package discovery. Публикация конфига:

```bash
php artisan vendor:publish --tag=admin-starter-config
```

## Что добавляет

| Ресурс | Slug | Права |
|---|---|---|
| Пользователи | `system-users` | `admin.system.users.{view,create,update,delete}` |
| Роли | `system-roles` | `admin.system.roles.{view,create,update,delete}` |
| Журнал аудита (только чтение) | `system-audit` | `admin.system.audit.view` |

Каждый ресурс отключается в `config/admin-starter.php`. Пользовательские строки используют русский текст как ключ перевода; английский перевод лежит в `resources/lang/en.json`.

## Документация

- [Быстрый старт](getting-started.md)
- [Использование](usage.md)

## Лицензия

[MIT](../../LICENSE) © Denis Skripchenko
