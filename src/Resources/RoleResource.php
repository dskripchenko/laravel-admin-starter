<?php

declare(strict_types=1);

namespace Dskripchenko\LaravelAdminStarter\Resources;

use Dskripchenko\LaravelAdmin\Field\Input;
use Dskripchenko\LaravelAdmin\Field\Slug;
use Dskripchenko\LaravelAdmin\Field\Switcher;
use Dskripchenko\LaravelAdmin\Field\TagsInput;
use Dskripchenko\LaravelAdmin\Field\Textarea;
use Dskripchenko\LaravelAdmin\Filter\InputFilter;
use Dskripchenko\LaravelAdmin\Permission\Models\Role;
use Dskripchenko\LaravelAdmin\Permission\PermissionRegistry;
use Dskripchenko\LaravelAdmin\Resource\Resource;
use Dskripchenko\LaravelAdmin\Resource\ResourceRegistry;
use Dskripchenko\LaravelAdmin\Table\TableColumn;

/**
 * RoleResource — CRUD над `admin_roles`.
 *
 * Permissions: admin.system.roles.{view,create,update,delete}.
 *
 * Системные роли (`is_system = true`, например Super Admin) защищены от
 * deletion в core'е (через Concerns\GuardsSystemRoles, отдельно от этого
 * Resource'а).
 */
final class RoleResource extends Resource
{
    public static string $model = Role::class;

    public static string $icon = 'shield';

    public static ?string $group = 'Системные';

    public static function slug(): string
    {
        return 'system-roles';
    }

    public static function permission(): string
    {
        return 'admin.system.roles';
    }

    public static function label(): string
    {
        return 'Роли';
    }

    public function fields(): array
    {
        $groups = $this->collectPermissionGroups();
        // flat-fallback для случая когда frontend не понимает groups.
        $flat = [];
        foreach ($groups as $g) {
            foreach ($g['items'] as $item) {
                $flat[] = $item;
            }
        }
        $flat = array_values(array_unique($flat));

        return [
            Input::make('name')->required()->title('Имя'),
            Slug::make('slug')->from('name')->required()->title('Slug'),
            Textarea::make('description')->title('Описание'),
            TagsInput::make('permissions')
                ->required()
                ->default([])
                ->title('Permission keys')
                ->help('Введите ключ и нажмите Enter. Поддерживаются glob-маски: admin.content.* / admin.*.view / *. Список подсказок собран из всех зарегистрированных Resource\'ов и sister-pack\'ов.')
                ->suggestions($flat)
                ->suggestionsByGroup($groups),
            Switcher::make('is_system')->title('Системная роль (read-only после create)'),
        ];
    }

    /**
     * Собрать permission keys, сгруппированные по Resource'у / Plugin'у.
     *
     * Структура:
     *   Group 1 — wildcards (*, admin.*, admin.*.view)
     *   Group 2 — group-wildcards (admin.content.*, admin.shop.*.view, ...)
     *   Group 3..N — по одной группе на каждый зарегистрированный Resource:
     *                label = Resource::label() (русское имя),
     *                items = [base.view, base.create, ..., base.*]
     *   Group N+1..M — по одной группе на ItemPermission (sister-pack'и),
     *                  если их keys ещё не покрыты Resource-группами.
     *
     * Frontend TagsField рендерит группы с sticky-заголовками + filter.
     *
     * @return list<array{label: string, items: list<string>}>
     */
    private function collectPermissionGroups(): array
    {
        $defaultActions = [
            'view', 'create', 'update', 'delete',
            'restore', 'force-delete', 'replicate', 'reorder',
        ];

        $resources = app(ResourceRegistry::class);
        $resourceBases = [];
        $resourceGroups = [];
        $groupRoots = [];
        foreach ($resources->all() as $slug => $class) {
            if (! is_string($class) || ! method_exists($class, 'permission')) {
                continue;
            }
            $base = $class::permission();
            $label = method_exists($class, 'label') ? (string) $class::label() : $slug;
            if (! is_string($base) || $base === '') {
                continue;
            }
            $resourceBases[$base] = $label;
            $items = [$base.'.*'];
            foreach ($defaultActions as $a) {
                $items[] = $base.'.'.$a;
            }
            $resourceGroups[] = [
                'label' => $label,
                'items' => $items,
            ];
            $parts = explode('.', $base);
            if (count($parts) >= 3) {
                $groupRoots[implode('.', array_slice($parts, 0, 2))] = true;
            }
        }

        // Plain-keys из PermissionRegistry: всё что не покрыто стандартными
        // {base}.{action} keys (например admin.system.health.run).
        $covered = [];
        foreach ($resourceBases as $base => $_label) {
            foreach ($defaultActions as $a) {
                $covered[$base.'.'.$a] = true;
            }
            $covered[$base.'.*'] = true;
        }
        $extraByResource = [];
        foreach (app(PermissionRegistry::class)->flat() as $key) {
            if (isset($covered[$key])) continue;
            // Сопоставляем custom-key с ближайшим Resource по prefix'у.
            $matched = null;
            foreach ($resourceBases as $base => $label) {
                if (str_starts_with($key, $base.'.')) {
                    $matched = $label;
                    break;
                }
            }
            if ($matched !== null) {
                $extraByResource[$matched][] = $key;
            } else {
                $extraByResource['Прочие'][] = $key;
            }
        }
        // Добавляем custom-keys в существующие resource-группы либо в "Прочие".
        foreach ($resourceGroups as &$g) {
            if (isset($extraByResource[$g['label']])) {
                $g['items'] = array_values(array_unique(array_merge($g['items'], $extraByResource[$g['label']])));
            }
        }
        unset($g);
        $miscItems = $extraByResource['Прочие'] ?? [];

        // Wildcard-группы сверху для скейл-grant'а.
        $globalWildcards = ['*', 'admin.*', 'admin.*.view', 'admin.*.create', 'admin.*.update', 'admin.*.delete'];
        $groupWildcards = [];
        foreach (array_keys($groupRoots) as $g) {
            $groupWildcards[] = $g.'.*';
            $groupWildcards[] = $g.'.*.view';
        }
        sort($groupWildcards);

        $result = [
            ['label' => 'Все права', 'items' => $globalWildcards],
        ];
        if ($groupWildcards !== []) {
            $result[] = ['label' => 'Группы разделов', 'items' => $groupWildcards];
        }
        // Сортируем resource-группы по label.
        usort($resourceGroups, static fn ($a, $b) => strcmp($a['label'], $b['label']));
        foreach ($resourceGroups as $g) {
            // Items внутри группы — сортируем оставив `{base}.*` сверху.
            $items = $g['items'];
            $wildcardItems = array_values(array_filter($items, static fn ($i) => str_ends_with($i, '.*')));
            $rest = array_values(array_filter($items, static fn ($i) => ! str_ends_with($i, '.*')));
            sort($rest);
            $g['items'] = array_values(array_unique(array_merge($wildcardItems, $rest)));
            $result[] = $g;
        }
        if ($miscItems !== []) {
            sort($miscItems);
            $result[] = ['label' => 'Прочие', 'items' => array_values(array_unique($miscItems))];
        }

        return $result;
    }

    public function columns(): array
    {
        return [
            TableColumn::make('id')->sort()->width('60px'),
            TableColumn::make('name')->sort()->search(),
            TableColumn::make('slug')->sort()->copyable(),
            TableColumn::make('is_system')->asBoolean('Системная', 'Пользовательская'),
            TableColumn::make('created_at')->sort()->asDateTime(),
        ];
    }

    public function filters(): array
    {
        return [
            InputFilter::for('slug')->label('Slug'),
        ];
    }
}
