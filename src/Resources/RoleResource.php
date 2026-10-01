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
use Illuminate\Database\Eloquent\Builder;

/**
 * RoleResource — the CRUD over `admin_roles`.
 *
 * Permissions: admin.system.roles.{view,create,update,delete}.
 *
 * The system roles (`is_system = true`, Super Admin for instance) are protected
 * from deletion in the core (through Concerns\GuardsSystemRoles, separately from
 * this resource).
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
        return __('Роли');
    }

    /**
     * The base query — it hides the roles of another domain (the config
     * `admin.roles.hidden_slug_prefixes`, `client-*` from ADR-017 for
     * instance). A single override covers both the list and a direct
     * read/update/delete by URL, since the base resource reads a single row
     * through modelQuery() (BL-3).
     */
    public function modelQuery(): Builder
    {
        $query = parent::modelQuery();

        foreach ((array) config('admin.roles.hidden_slug_prefixes', []) as $prefix) {
            if (is_string($prefix) && $prefix !== '') {
                $query->where('slug', 'not like', $prefix.'%');
            }
        }

        return $query;
    }

    public function fields(): array
    {
        $groups = $this->collectPermissionGroups();
        // A flat fallback for the case where the frontend does not understand groups.
        $flat = [];
        foreach ($groups as $g) {
            foreach ($g['items'] as $item) {
                $flat[] = $item;
            }
        }
        $flat = array_values(array_unique($flat));

        return [
            Input::make('name')->required()->title(__('Имя')),
            Slug::make('slug')->from('name')->required()->title(__('Slug')),
            Textarea::make('description')->title(__('Описание')),
            TagsInput::make('permissions')
                ->required()
                ->default([])
                ->title(__('Ключи прав'))
                ->help(__('Введите ключ и нажмите Enter. Поддерживаются glob-маски: admin.content.* / admin.*.view / *. Список подсказок собран из всех зарегистрированных Resource\'ов и sister-pack\'ов.'))
                ->suggestions($flat)
                ->suggestionsByGroup($groups),
            Switcher::make('is_system')->title(__('Системная роль (read-only после create)')),
        ];
    }

    /**
     * Assemble the permission keys grouped by resource and by plugin.
     *
     * The structure:
     *   Group 1 — the wildcards (*, admin.*, admin.*.view)
     *   Group 2 — the group wildcards (admin.content.*, admin.shop.*.view, ...)
     *   Groups 3..N — one group per registered resource:
     *                 label = Resource::label() (the human name),
     *                 items = [base.view, base.create, ..., base.*]
     *   Groups N+1..M — one group per ItemPermission (the sister packs), when
     *                   their keys are not already covered by the resource
     *                   groups.
     *
     * The frontend's TagsField renders the groups with sticky headings and a
     * filter.
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
            $base = $class::permission();
            $label = (string) $class::label();
            if ($base === '') {
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

        // The plain keys from the PermissionRegistry: everything not covered by
        // the standard {base}.{action} keys (admin.system.health.run, say).
        $covered = [];
        foreach ($resourceBases as $base => $_label) {
            foreach ($defaultActions as $a) {
                $covered[$base.'.'.$a] = true;
            }
            $covered[$base.'.*'] = true;
        }
        $extraByResource = [];
        foreach (app(PermissionRegistry::class)->flat() as $key) {
            if (isset($covered[$key])) {
                continue;
            }
            // We match a custom key to the nearest resource by its prefix.
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
                $extraByResource[(string) __('Прочие')][] = $key;
            }
        }
        // The custom keys are added to the existing resource groups or to "Other".
        foreach ($resourceGroups as &$g) {
            if (isset($extraByResource[$g['label']])) {
                $g['items'] = array_values(array_unique(array_merge($g['items'], $extraByResource[$g['label']])));
            }
        }
        unset($g);
        $miscItems = $extraByResource[(string) __('Прочие')] ?? [];

        // The wildcard groups go on top, for granting at scale.
        $globalWildcards = ['*', 'admin.*', 'admin.*.view', 'admin.*.create', 'admin.*.update', 'admin.*.delete'];
        $groupWildcards = [];
        foreach (array_keys($groupRoots) as $g) {
            $groupWildcards[] = $g.'.*';
            $groupWildcards[] = $g.'.*.view';
        }
        sort($groupWildcards);

        $result = [
            ['label' => (string) __('Все права'), 'items' => $globalWildcards],
        ];
        if ($groupWildcards !== []) {
            $result[] = ['label' => (string) __('Группы разделов'), 'items' => $groupWildcards];
        }
        // The resource groups are sorted by label.
        usort($resourceGroups, static fn ($a, $b) => strcmp($a['label'], $b['label']));
        foreach ($resourceGroups as $g) {
            // The items inside a group are sorted with `{base}.*` left on top.
            $items = $g['items'];
            $wildcardItems = array_values(array_filter($items, static fn ($i) => str_ends_with($i, '.*')));
            $rest = array_values(array_filter($items, static fn ($i) => ! str_ends_with($i, '.*')));
            sort($rest);
            $g['items'] = array_values(array_unique(array_merge($wildcardItems, $rest)));
            $result[] = $g;
        }
        if ($miscItems !== []) {
            sort($miscItems);
            $result[] = ['label' => (string) __('Прочие'), 'items' => array_values(array_unique($miscItems))];
        }

        return $result;
    }

    public function columns(): array
    {
        return [
            TableColumn::make('id')->sort()->width('60px'),
            TableColumn::make('name')->sort()->search(),
            TableColumn::make('slug')->sort()->copyable(),
            TableColumn::make('is_system')->asBoolean(__('Системная'), __('Пользовательская')),
            TableColumn::make('created_at')->sort()->asDateTime(),
        ];
    }

    public function filters(): array
    {
        return [
            InputFilter::for('slug')->label(__('Slug')),
        ];
    }
}
