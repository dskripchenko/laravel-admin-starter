<?php

declare(strict_types=1);

namespace Dskripchenko\LaravelAdminStarter\Tests\Unit;

use Dskripchenko\LaravelAdminStarter\AdminStarterPlugin;
use Dskripchenko\LaravelAdminStarter\Resources\AuditLogResource;
use Dskripchenko\LaravelAdminStarter\Resources\RoleResource;
use Dskripchenko\LaravelAdminStarter\Resources\UserResource;
use Dskripchenko\LaravelAdminStarter\Tests\TestCase;

final class PluginMetadataTest extends TestCase
{
    public function test_version_is_a_non_empty_string(): void
    {
        $version = (new AdminStarterPlugin)->version();

        $this->assertNotSame('', $version);
        $this->assertNotSame('0.1.0', $version);
    }

    public function test_labels_are_translated_to_english(): void
    {
        app()->setLocale('en');

        $this->assertSame('Users', UserResource::label());
        $this->assertSame('Roles', RoleResource::label());
        $this->assertSame('Audit log', AuditLogResource::label());
        $this->assertSame('System', __('Системные'));
    }

    public function test_labels_keep_russian_source_text(): void
    {
        app()->setLocale('ru');

        $this->assertSame('Пользователи', UserResource::label());
    }

    public function test_every_english_translation_is_non_empty(): void
    {
        /** @var array<string, string> $translations */
        $translations = json_decode(
            (string) file_get_contents(__DIR__.'/../../resources/lang/en.json'),
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        $this->assertNotEmpty($translations);
        foreach ($translations as $key => $value) {
            $this->assertNotSame('', trim($value), "empty translation for '$key'");
        }
    }
}
