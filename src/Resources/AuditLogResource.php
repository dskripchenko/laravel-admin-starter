<?php

declare(strict_types=1);

namespace Dskripchenko\LaravelAdminStarter\Resources;

use Dskripchenko\LaravelAdmin\Audit\AuditLog;
use Dskripchenko\LaravelAdmin\Filter\DateRangeFilter;
use Dskripchenko\LaravelAdmin\Filter\InputFilter;
use Dskripchenko\LaravelAdmin\Filter\OptionsFilter;
use Dskripchenko\LaravelAdmin\Infolist\BadgeEntry;
use Dskripchenko\LaravelAdmin\Infolist\KeyValueEntry;
use Dskripchenko\LaravelAdmin\Infolist\TextEntry;
use Dskripchenko\LaravelAdmin\Resource\Resource;
use Dskripchenko\LaravelAdmin\Table\TableColumn;
use Illuminate\Database\Eloquent\Builder;

/**
 * AuditLogResource — view-only для `admin_audit_logs`.
 *
 * Read-only: list + view; нет create/update/delete (audit-history
 * immutable). UI рендерит inline diff old vs new (через core's
 * AuditTrail layout, не дублируется здесь).
 *
 * Permissions: admin.system.audit.view.
 */
final class AuditLogResource extends Resource
{
    public static string $model = AuditLog::class;

    public static string $icon = 'history';

    public static ?string $group = 'Системные';

    public static function slug(): string
    {
        return 'system-audit';
    }

    public static function permission(): string
    {
        return 'admin.system.audit';
    }

    public static function label(): string
    {
        return __('Журнал аудита');
    }

    public function columns(): array
    {
        return [
            TableColumn::make('id')->sort()->width('60px'),
            TableColumn::make('event')->sort()->asBadge([
                'created' => 'success',
                'updated' => 'info',
                'deleted' => 'danger',
                'restored' => 'warning',
                'login' => 'info',
                'logout' => 'default',
                'login_failed' => 'danger',
            ]),
            // Человекочитаемые ярлыки вместо FQCN (BL-4). Сырой тип остаётся
            // фильтруемым (filters ниже) и виден в detail-view.
            TableColumn::make('actor_label')->label(__('Actor')),
            TableColumn::make('actor_id')->align('right'),
            TableColumn::make('subject_label')->label(__('Subject')),
            TableColumn::make('subject_id')->align('right'),
            TableColumn::make('ip')->copyable(),
            TableColumn::make('created_at')->sort()->asDateTime(),
        ];
    }

    public function filters(): array
    {
        return [
            OptionsFilter::for('event')->label(__('Событие'))->options([
                'created' => 'Создание',
                'updated' => 'Изменение',
                'deleted' => 'Удаление',
                'restored' => 'Восстановление',
                'login' => 'Вход',
                'logout' => 'Выход',
                'login_failed' => 'Неудачный вход',
            ]),
            InputFilter::for('actor_type')->label(__('Actor type')),
            InputFilter::for('subject_type')->label(__('Subject type')),
            DateRangeFilter::for('created_at')->label(__('Период')),
            InputFilter::for('ip')->label(__('IP')),
        ];
    }

    public function indexQuery(): Builder
    {
        return $this->modelQuery()->orderByDesc('created_at');
    }

    public function infolist(): array
    {
        return [
            TextEntry::make('id')->label(__('ID')),
            BadgeEntry::make('event')->label(__('Событие'))->colors([
                'created' => 'success',
                'updated' => 'info',
                'deleted' => 'danger',
                'restored' => 'warning',
                'login' => 'info',
                'logout' => 'default',
                'login_failed' => 'danger',
            ]),
            TextEntry::make('actor_label')->label(__('Actor')),
            TextEntry::make('actor_type')->label(__('Actor type (class)'))->copyable(),
            TextEntry::make('actor_id')->label(__('Actor id')),
            TextEntry::make('subject_label')->label(__('Subject')),
            TextEntry::make('subject_type')->label(__('Subject type (class)'))->copyable(),
            TextEntry::make('subject_id')->label(__('Subject id')),
            TextEntry::make('ip')->label(__('IP'))->copyable(),
            TextEntry::make('user_agent')->label(__('User-Agent')),
            TextEntry::make('url')->label(__('URL'))->copyable(),
            TextEntry::make('created_at')->label(__('Создано'))->asDateTime(),
            KeyValueEntry::make('changes')->label(__('Изменения'))
                ->keyLabel('Поле')->valueLabel('Значение'),
        ];
    }
}
