<?php

declare(strict_types=1);

namespace Dskripchenko\LaravelAdminStarter;

use Composer\InstalledVersions;
use Dskripchenko\LaravelAdmin\Admin;
use Dskripchenko\LaravelAdmin\Permission\ItemPermission;
use Dskripchenko\LaravelAdmin\Plugin\AdminPlugin;
use Dskripchenko\LaravelAdminStarter\Resources\AuditLogResource;
use Dskripchenko\LaravelAdminStarter\Resources\RoleResource;
use Dskripchenko\LaravelAdminStarter\Resources\UserResource;

/**
 * AdminStarterPlugin — a ready-made set of system resources.
 *
 * The toggles in config('admin-starter.resources') decide which of the resources
 * reach the Admin manager: users / roles / audit_log, all active by default.
 */
final class AdminStarterPlugin implements AdminPlugin
{
    public function name(): string
    {
        return 'starter';
    }

    public function version(): string
    {
        if (! class_exists(InstalledVersions::class)) {
            return 'dev';
        }

        try {
            return InstalledVersions::getPrettyVersion('dskripchenko/laravel-admin-starter') ?? 'dev';
        } catch (\OutOfBoundsException) {
            return 'dev';
        }
    }

    public function register(): void
    {
        // No-op.
    }

    public function boot(Admin $admin): void
    {
        $resources = $this->resolveActiveResources();
        if ($resources !== []) {
            $admin->resources($resources);
        }

        $admin->permissions($this->buildPermissions());
    }

    /**
     * @return list<class-string>
     */
    private function resolveActiveResources(): array
    {
        /** @var array<string, mixed> $toggles */
        $toggles = (array) config('admin-starter.resources', []);

        $resources = [];
        if ((bool) ($toggles['users'] ?? true)) {
            $resources[] = UserResource::class;
        }
        if ((bool) ($toggles['roles'] ?? true)) {
            $resources[] = RoleResource::class;
        }
        if ((bool) ($toggles['audit_log'] ?? true)) {
            $resources[] = AuditLogResource::class;
        }

        return $resources;
    }

    private function buildPermissions(): ItemPermission
    {
        return ItemPermission::group(__('Системные'))
            ->addPermission('admin.system.users.view', __('Пользователи: просмотр'))
            ->addPermission('admin.system.users.create', __('Пользователи: создание'))
            ->addPermission('admin.system.users.update', __('Пользователи: редактирование'))
            ->addPermission('admin.system.users.delete', __('Пользователи: удаление'))
            ->addPermission('admin.system.roles.view', __('Роли: просмотр'))
            ->addPermission('admin.system.roles.create', __('Роли: создание'))
            ->addPermission('admin.system.roles.update', __('Роли: редактирование'))
            ->addPermission('admin.system.roles.delete', __('Роли: удаление'))
            ->addPermission('admin.system.audit.view', __('Журнал аудита: просмотр'));
    }
}
