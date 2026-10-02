<?php

declare(strict_types=1);

namespace Dskripchenko\LaravelAdminStarter\Tests\Unit;

use Dskripchenko\LaravelAdmin\Table\TableColumn;
use Dskripchenko\LaravelAdminStarter\Resources\AuditLogResource;
use Dskripchenko\LaravelAdminStarter\Resources\RoleResource;
use Dskripchenko\LaravelAdminStarter\Resources\UserResource;
use Dskripchenko\LaravelAdminStarter\Tests\TestCase;

/**
 * Every column carries a label of its own: one made from the column name
 * ("Created at") is English whatever the panel's language.
 */
final class ColumnLabelsTest extends TestCase
{
    /**
     * @return array<string, string>
     */
    private function labels(string $resource): array
    {
        $out = [];
        foreach ((new $resource)->columns() as $column) {
            /** @var TableColumn $column */
            $arr = $column->toArray();
            $out[(string) $arr['name']] = (string) $arr['label'];
        }

        return $out;
    }

    public function test_columns_read_in_russian_in_a_russian_panel(): void
    {
        app()->setLocale('ru');

        $this->assertSame('Создано', $this->labels(UserResource::class)['created_at']);
        $this->assertSame('Язык', $this->labels(UserResource::class)['locale']);
        $this->assertSame('Тип', $this->labels(RoleResource::class)['is_system']);
        $this->assertSame('Событие', $this->labels(AuditLogResource::class)['event']);
        $this->assertSame('ID инициатора', $this->labels(AuditLogResource::class)['actor_id']);
        $this->assertSame('ID объекта', $this->labels(AuditLogResource::class)['subject_id']);
    }

    public function test_columns_read_in_english_in_an_english_panel(): void
    {
        app()->setLocale('en');

        $this->assertSame('Language', $this->labels(UserResource::class)['locale']);
        $this->assertSame('Type', $this->labels(RoleResource::class)['is_system']);
        $this->assertSame('Actor ID', $this->labels(AuditLogResource::class)['actor_id']);
    }

    public function test_no_column_falls_back_to_its_name(): void
    {
        app()->setLocale('ru');

        foreach ([UserResource::class, RoleResource::class, AuditLogResource::class] as $resource) {
            foreach ($this->labels($resource) as $name => $label) {
                $this->assertMatchesRegularExpression('/[А-Яа-яЁё]|^(ID|IP|Email|Slug)$/u', $label, "{$resource}::{$name}");
            }
        }
    }
}
