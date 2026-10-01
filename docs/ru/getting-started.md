---
title: Быстрый старт
locale: ru
status: stable
translated_from: ../en/getting-started.md
---

# Быстрый старт

`dskripchenko/laravel-admin-starter` — sister-pack для `dskripchenko/laravel-admin`. Достаточно установить пакет: он регистрируется сам, и его ресурсы появляются в панели.

## Установка

```bash
composer require dskripchenko/laravel-admin-starter
php artisan migrate
```

Собственных миграций у пакета нет; `migrate` создаёт таблицы ядра, если их ещё нет.

## Настройка

```bash
php artisan vendor:publish --tag=admin-starter-config
```

Отредактируйте `config/admin-starter.php`.

## Что добавляет

После установки в группе меню «Системные» появляются три ресурса:

| Ресурс | Slug | Права |
|---|---|---|
| Пользователи | `system-users` | `admin.system.users.{view,create,update,delete}` |
| Роли | `system-roles` | `admin.system.roles.{view,create,update,delete}` |
| Журнал аудита (только чтение) | `system-audit` | `admin.system.audit.view` |

- **Пользователи** — CRUD над `AdminUser` ядра: имя, email, пароль (при создании), локаль, тема и флаг активности.
- **Роли** — CRUD над `Role` ядра. Поле прав подсказывает все известные панели ключи, сгруппированные по ресурсам, и glob-маски (`admin.*`, `admin.content.*`, `*`). Роли, чей slug начинается с префикса из `admin.roles.hidden_slug_prefixes` (конфиг ядра), скрыты.
- **Журнал аудита** — список и карточка над `admin_audit_logs` только для чтения: событие, инициатор, объект, IP-адрес и записанные изменения.

Настройки приложения предоставляет само ядро, а не этот пакет.

## Переводы

Пользовательские строки используют русский текст как ключ перевода. Пакет поставляет `resources/lang/en.json`, поэтому панель в локали `en` показывает английские подписи. Для других языков добавьте свой `lang/{locale}.json`.

## См. также

- [Использование](usage.md)
- [Глоссарий](https://github.com/dskripchenko/laravel-admin/blob/main/docs/ru/glossary.md)
