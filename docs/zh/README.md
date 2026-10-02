# dskripchenko/laravel-admin-starter

> 🌐 [English](../../README.md) · [Русский](../ru/README.md) · [Deutsch](../de/README.md) · **中文**

管理面板的现成系统资源：**用户**、**角色** 和只读的 **审计日志**，以及它们的权限。新管理面板的即插即用起点。

[`dskripchenko/laravel-admin`](https://github.com/dskripchenko/laravel-admin) 的姐妹包。应用设置不属于本包——它们位于核心中。

## 要求

- PHP 8.2+
- Laravel 11、12 或 13
- `dskripchenko/laravel-admin` ^1.33

## 安装

```bash
composer require dskripchenko/laravel-admin-starter
php artisan migrate
```

本包没有自己的迁移：数据表（`admin_users`、`admin_roles`、`admin_audit_logs`）来自核心。插件通过 Laravel package discovery 自动注册。发布配置：

```bash
php artisan vendor:publish --tag=admin-starter-config
```

## 新增内容

| 资源 | Slug | 权限 |
|---|---|---|
| 用户 | `system-users` | `admin.system.users.{view,create,update,delete}` |
| 角色 | `system-roles` | `admin.system.roles.{view,create,update,delete}` |
| 审计日志（只读） | `system-audit` | `admin.system.audit.view` |

每个资源都可以在 `config/admin-starter.php` 中关闭。界面字符串以俄语原文作为翻译键；英文翻译位于 `resources/lang/en.json`。

## 文档

- [快速开始](getting-started.md)
- [使用](usage.md)

## 许可证

[MIT](../../LICENSE) © Denis Skripchenko
