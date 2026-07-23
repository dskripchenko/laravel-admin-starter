<?php

declare(strict_types=1);

namespace Dskripchenko\LaravelAdminStarter\Tests\Feature;

use Dskripchenko\LaravelAdmin\Permission\Models\Role;
use Dskripchenko\LaravelAdminStarter\Resources\AuditLogResource;
use Dskripchenko\LaravelAdminStarter\Resources\RoleResource;
use Dskripchenko\LaravelAdminStarter\Tests\TestCase;

/**
 * BL-3 / BL-4 — сервисные системные ресурсы: скрытие ролей иного домена и
 * человекочитаемые типы в аудите.
 */
final class SystemResourceScopingTest extends TestCase
{
    public function test_role_resource_hides_configured_slug_prefixes(): void
    {
        config()->set('admin.roles.hidden_slug_prefixes', ['client-']);

        Role::create(['name' => 'Super Admin', 'slug' => 'super-admin', 'permissions' => ['*']]);
        Role::create(['name' => 'Клиент: администратор', 'slug' => 'client-admin', 'permissions' => ['*']]);
        Role::create(['name' => 'Клиент: наблюдатель', 'slug' => 'client-viewer', 'permissions' => []]);

        $slugs = (new RoleResource)->modelQuery()->pluck('slug')->all();

        $this->assertContains('super-admin', $slugs);
        $this->assertNotContains('client-admin', $slugs);
        $this->assertNotContains('client-viewer', $slugs, 'client-* роли не должны попадать в сервисный список');
    }

    public function test_role_resource_shows_everything_when_no_prefixes_configured(): void
    {
        config()->set('admin.roles.hidden_slug_prefixes', []);

        Role::create(['name' => 'Super Admin', 'slug' => 'super-admin', 'permissions' => ['*']]);
        Role::create(['name' => 'Клиент', 'slug' => 'client-admin', 'permissions' => ['*']]);

        $slugs = (new RoleResource)->modelQuery()->pluck('slug')->all();

        $this->assertContains('super-admin', $slugs);
        $this->assertContains('client-admin', $slugs);
    }

    public function test_audit_resource_renders_human_labels_not_fqcn(): void
    {
        $columnNames = array_map(
            static fn ($c): string => $c->name(),
            (new AuditLogResource)->columns(),
        );

        // Колонки показывают ярлыки, а не сырой FQCN-тип.
        $this->assertContains('actor_label', $columnNames);
        $this->assertContains('subject_label', $columnNames);
        $this->assertNotContains('actor_type', $columnNames);
        $this->assertNotContains('subject_type', $columnNames);
    }
}
