<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Which resources to register
    |--------------------------------------------------------------------------
    | Every key is a toggle. The full list is implemented in the plugin; the
    | keys of this array act as the gate. The core set (users / roles /
    | audit_log) is on by default. The optional resources (translations /
    | content_blocks / sessions) are not implemented in v0.1 and will be added
    | later.
    */

    'resources' => [
        'users' => true,
        'roles' => true,
        'audit_log' => true,
        'settings' => false, // not implemented in v0.1
        'translations' => false,
        'content_blocks' => false,
        'sessions' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | The menu group
    |--------------------------------------------------------------------------
    | The resources are grouped under this label in the sidebar (through
    | Resource::$group). The core reads the icon and the order from
    | Resource::$icon and Resource::menuOrder().
    */

    'menu_group' => 'Системные',
];
