# dskripchenko/laravel-admin-starter

> 🌐 **English** · [Русский](README.ru.md) · [Deutsch](README.de.md) · [中文](README.zh.md)

Out-of-the-box admin resources: Users, Roles, AuditLog, Settings, Translations, ContentBlocks. Drop-in starting point for new admins.

A sister-pack for [`dskripchenko/laravel-admin`](https://github.com/dskripchenko/laravel-admin).

[![Packagist](https://img.shields.io/packagist/v/dskripchenko/laravel-admin-starter)](https://packagist.org/packages/dskripchenko/laravel-admin-starter)
[![License](https://img.shields.io/packagist/l/dskripchenko/laravel-admin-starter)](LICENSE)

## Install

```bash
composer require dskripchenko/laravel-admin-starter
php artisan migrate
```

The plugin auto-registers via Laravel package discovery. To publish the
config:

```bash
php artisan vendor:publish --tag=starter-config
```

## Documentation

- [Getting started](docs/en/getting-started.md)
- [Usage](docs/en/usage.md)

## License

[MIT](LICENSE) © Denis Skripchenko
