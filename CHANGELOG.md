# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project follows [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

Entries for releases published before this file existed were reconstructed from
the tagged commit history.

## [1.4.2] — 2026-10-02

### Fixed
- The users, roles and audit log tables had column headers made from the
  column names ("Created at", "Locale", "Is system", "Event", "Actor id",
  "Subject id"), English in every panel language. Every column now carries a
  label, a source string translated per request.

## [1.4.1] — 2026-10-02

### Fixed
- The permission group and its labels were registered as `__()` results,
  translated once at boot: the role matrix showed them in the boot locale
  whatever the request's language, and apart from the "Системные" group of the
  other packs when those register the source string. The group and the labels
  are now passed as source strings, which core translates per request.

### Changed
- Requires `dskripchenko/laravel-admin` ^1.33, the first core to translate
  permission groups and labels per request.

## [1.4.0] — 2026-10-01

### Added
- English translations of every user-facing string in `resources/lang/en.json`, loaded
  as JSON translations by the service provider.
- A weekly scheduled CI run to catch drift against new core and Laravel releases.

### Changed
- The plugin's `version()` reports the installed package version (via
  `Composer\InstalledVersions`) instead of a hardcoded `0.1.0`, falling back to `dev`.
- All user-facing strings, including permission labels and the help text of the role
  form, are wrapped in `__()` with Russian source text as the translation key, matching
  the core. Audit log captions that used English keys (Actor, Subject, ...) now use
  Russian keys too, so a Russian-locale panel no longer shows them in English.
- The minimum supported core is `dskripchenko/laravel-admin` ^1.30.
- README, package description and the documentation in all four languages describe what
  the package actually ships (Users, Roles, Audit Log) and use the real publish tag,
  `admin-starter-config`.

### Removed
- The unused config keys `resources.settings`, `resources.translations`,
  `resources.content_blocks`, `resources.sessions` and `menu_group`; none of them had any
  effect. Published configs that still contain them keep working.

## [v1.3.3] - 2026-07-23

### Changed
- Option and group labels are translated through `__()`, so a panel running in a
  non-English locale no longer shows untranslated system strings.

### Added
- Tests covering `collectPermissionGroups()`.

## [v1.3.2] - 2026-07-23

### Changed
- Labels of the system resources are wrapped in `__()` and follow the panel locale.

## [v1.3.1] - 2026-07-23

### Changed
- Roles belonging to a foreign domain are hidden from the role picker.
- Audit entries name their event in words instead of showing the raw type.

## [v1.3.0] - 2026-07-20

### Added
- Audit log view page built with `infolist()`: event, actor, subject, IP address and the recorded changes.
- Documentation in German, Russian and Chinese alongside the English default.
- GitHub Actions pipeline covering the whole support matrix.

### Changed
- Supported versions moved to the canonical matrix: PHP 8.2-8.5 with Laravel 11, 12 and 13.

## [v1.2.0] - 2026-05-07

### Added
- Permission field on the role resource: suggestions are grouped and searchable.

## [v1.0.0] - 2026-05-01

### Added
- First standalone release, extracted from the laravel-admin monorepo.
- Packagist metadata: description, keywords, authors and support links.
