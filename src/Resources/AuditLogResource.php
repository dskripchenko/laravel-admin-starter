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
        return 'Журнал аудита';
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
            TableColumn::make('actor_label')->label('Actor'),
            TableColumn::make('actor_id')->align('right'),
            TableColumn::make('subject_label')->label('Subject'),
            TableColumn::make('subject_id')->align('right'),
            TableColumn::make('ip')->copyable(),
            TableColumn::make('created_at')->sort()->asDateTime(),
        ];
    }

    public function filters(): array
    {
        return [
            OptionsFilter::for('event')->label('Событие')->options([
                'created' => 'Создание',
                'updated' => 'Изменение',
                'deleted' => 'Удаление',
                'restored' => 'Восстановление',
                'login' => 'Вход',
                'logout' => 'Выход',
                'login_failed' => 'Неудачный вход',
            ]),
            InputFilter::for('actor_type')->label('Actor type'),
            InputFilter::for('subject_type')->label('Subject type'),
            DateRangeFilter::for('created_at')->label('Период'),
            InputFilter::for('ip')->label('IP'),
        ];
    }

    public function indexQuery(): Builder
    {
        return $this->modelQuery()->orderByDesc('created_at');
    }

    public function infolist(): array
    {
        return [
            TextEntry::make('id')->label('ID'),
            BadgeEntry::make('event')->label('Событие')->colors([
                'created' => 'success',
                'updated' => 'info',
                'deleted' => 'danger',
                'restored' => 'warning',
                'login' => 'info',
                'logout' => 'default',
                'login_failed' => 'danger',
            ]),
            TextEntry::make('actor_label')->label('Actor'),
            TextEntry::make('actor_type')->label('Actor type (class)')->copyable(),
            TextEntry::make('actor_id')->label('Actor id'),
            TextEntry::make('subject_label')->label('Subject'),
            TextEntry::make('subject_type')->label('Subject type (class)')->copyable(),
            TextEntry::make('subject_id')->label('Subject id'),
            TextEntry::make('ip')->label('IP')->copyable(),
            TextEntry::make('user_agent')->label('User-Agent'),
            TextEntry::make('url')->label('URL')->copyable(),
            TextEntry::make('created_at')->label('Создано')->asDateTime(),
            KeyValueEntry::make('changes')->label('Изменения')
                ->keyLabel('Поле')->valueLabel('Значение'),
        ];
    }
}
