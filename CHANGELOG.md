# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project follows [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

Entries for releases published before this file existed were reconstructed from
the tagged commit history.

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
