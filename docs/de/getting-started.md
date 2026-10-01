---
title: Erste Schritte
locale: de
status: stable
translated_from: ../en/getting-started.md
---

# Erste Schritte

`dskripchenko/laravel-admin-starter` ist ein Sister-Pack von `dskripchenko/laravel-admin`. Einmal installiert, registriert es sich selbst und seine Resourcen erscheinen im Panel.

## Installation

```bash
composer require dskripchenko/laravel-admin-starter
php artisan migrate
```

Das Paket hat keine eigenen Migrationen; `migrate` legt die Tabellen des Cores an, falls sie noch fehlen.

## Konfiguration

```bash
php artisan vendor:publish --tag=admin-starter-config
```

Bearbeiten Sie `config/admin-starter.php`.

## Was es hinzufügt

Nach der Installation erhält das Panel drei Resourcen in der Menügruppe „System“:

| Resource | Slug | Berechtigungen |
|---|---|---|
| Benutzer | `system-users` | `admin.system.users.{view,create,update,delete}` |
| Rollen | `system-roles` | `admin.system.roles.{view,create,update,delete}` |
| Audit-Log (schreibgeschützt) | `system-audit` | `admin.system.audit.view` |

- **Benutzer** — CRUD über `AdminUser` des Cores: Name, E-Mail, Passwort (beim Anlegen), Sprache, Theme und Aktiv-Flag.
- **Rollen** — CRUD über `Role` des Cores. Das Berechtigungsfeld schlägt alle dem Panel bekannten Schlüssel vor, gruppiert nach Resource, sowie Glob-Masken (`admin.*`, `admin.content.*`, `*`). Rollen, deren Slug mit einem Präfix aus `admin.roles.hidden_slug_prefixes` (Core-Konfiguration) beginnt, werden ausgeblendet.
- **Audit-Log** — schreibgeschützte Liste und Detailansicht über `admin_audit_logs`: Ereignis, Akteur, Objekt, IP-Adresse und die aufgezeichneten Änderungen.

Anwendungseinstellungen stellt der Core selbst bereit, nicht dieses Paket.

## Übersetzungen

Oberflächentexte verwenden den russischen Quelltext als Übersetzungsschlüssel. Das Paket liefert `resources/lang/en.json` mit, sodass ein Panel in der Locale `en` englische Beschriftungen zeigt. Für weitere Sprachen fügen Sie eine eigene `lang/{locale}.json` hinzu.

## Siehe auch

- [Verwendung](usage.md)
- [Glossar](https://github.com/dskripchenko/laravel-admin/blob/main/docs/en/glossary.md) (en)
