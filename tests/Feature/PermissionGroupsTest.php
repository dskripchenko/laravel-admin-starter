<?php

declare(strict_types=1);

namespace Dskripchenko\LaravelAdminStarter\Tests\Feature;

use Dskripchenko\LaravelAdminStarter\Resources\RoleResource;
use Dskripchenko\LaravelAdminStarter\Tests\TestCase;

/**
 * collectPermissionGroups() — группировка permission-подсказок для формы
 * ролей. Тестируется через публичный fields(): TagsInput несёт
 * suggestions (flat) + suggestionsByGroup (группы).
 */
final class PermissionGroupsTest extends TestCase
{
    /**
     * @return array{flat: list<string>, groups: list<array{label: string, items: list<string>}>}
     */
    private function collect(): array
    {
        $fields = (new RoleResource)->fields();
        foreach ($fields as $field) {
            $attrs = $field->getAttributes();
            if (isset($attrs['suggestionsByGroup'])) {
                return [
                    'flat' => $attrs['suggestions'] ?? [],
                    'groups' => $attrs['suggestionsByGroup'],
                ];
            }
        }
        $this->fail('TagsInput с suggestionsByGroup не найден в fields()');
    }

    public function test_wildcard_group_first_with_global_masks(): void
    {
        $groups = $this->collect()['groups'];

        $this->assertNotEmpty($groups);
        $first = $groups[0];
        $this->assertContains('*', $first['items']);
        $this->assertContains('admin.*', $first['items']);
        $this->assertContains('admin.*.view', $first['items']);
    }

    public function test_each_registered_resource_gets_group_with_crud_actions(): void
    {
        $data = $this->collect();
        $labels = array_column($data['groups'], 'label');

        // Стандартные starter-ресурсы зарегистрированы плагином.
        $this->assertContains('Пользователи', $labels);
        $this->assertContains('Роли', $labels);

        $roleGroup = null;
        foreach ($data['groups'] as $g) {
            if ($g['label'] === 'Роли') {
                $roleGroup = $g;
            }
        }
        $this->assertNotNull($roleGroup);
        // base.* первым, затем CRUD-actions.
        $this->assertSame('admin.system.roles.*', $roleGroup['items'][0]);
        $this->assertContains('admin.system.roles.view', $roleGroup['items']);
        $this->assertContains('admin.system.roles.delete', $roleGroup['items']);
    }

    public function test_flat_fallback_covers_every_grouped_item(): void
    {
        $data = $this->collect();
        $allGrouped = [];
        foreach ($data['groups'] as $g) {
            foreach ($g['items'] as $item) {
                $allGrouped[] = $item;
            }
        }

        // flat — uniq-надмножество groups (fallback для frontend'а без групп).
        foreach (array_unique($allGrouped) as $item) {
            $this->assertContains($item, $data['flat']);
        }
        $this->assertSame(array_values(array_unique($data['flat'])), $data['flat']);
    }

    public function test_group_wildcards_derived_from_permission_roots(): void
    {
        $groups = $this->collect()['groups'];
        // admin.system.* / admin.system.*.view — из admin.system.{users,roles,audit}.
        $groupMasks = [];
        foreach ($groups as $g) {
            foreach ($g['items'] as $item) {
                if (str_starts_with($item, 'admin.system.*')) {
                    $groupMasks[] = $item;
                }
            }
        }
        $this->assertContains('admin.system.*', $groupMasks);
        $this->assertContains('admin.system.*.view', $groupMasks);
    }
}
