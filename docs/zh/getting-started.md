---
title: 快速开始
locale: zh
status: stable
translated_from: ../en/getting-started.md
---

# 快速开始

`dskripchenko/laravel-admin-starter` 是 `dskripchenko/laravel-admin` 的姐妹包。安装一次即可——它会自动注册，其资源会出现在面板中。

## 安装

```bash
composer require dskripchenko/laravel-admin-starter
php artisan migrate
```

本包没有自己的迁移；如果核心的数据表尚不存在，`migrate` 会创建它们。

## 配置

```bash
php artisan vendor:publish --tag=admin-starter-config
```

编辑 `config/admin-starter.php`。

## 新增内容

安装后，面板侧边栏的“系统”分组中会出现三个资源：

| 资源 | Slug | 权限 |
|---|---|---|
| 用户 | `system-users` | `admin.system.users.{view,create,update,delete}` |
| 角色 | `system-roles` | `admin.system.roles.{view,create,update,delete}` |
| 审计日志（只读） | `system-audit` | `admin.system.audit.view` |

- **用户** — 基于核心 `AdminUser` 的 CRUD：名称、邮箱、密码（仅创建时）、语言、主题和启用标志。
- **角色** — 基于核心 `Role` 的 CRUD。权限字段会按资源分组提示面板已知的所有键，以及 glob 掩码（`admin.*`、`admin.content.*`、`*`）。slug 以 `admin.roles.hidden_slug_prefixes`（核心配置）中某个前缀开头的角色会被隐藏。
- **审计日志** — 基于 `admin_audit_logs` 的只读列表和详情页：事件、操作者、对象、IP 地址以及记录的变更。

应用设置由核心本身提供，而不是本包。

## 翻译

界面字符串以俄语原文作为翻译键。本包附带 `resources/lang/en.json`，因此在 `en` 语言环境下运行的面板会显示英文标签。如需其他语言，请添加您自己的 `lang/{locale}.json`。

## 另请参阅

- [使用](usage.md)
- [术语表](https://github.com/dskripchenko/laravel-admin/blob/main/docs/en/glossary.md) (en)
