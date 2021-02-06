<?php

return [
    'default' => 'local',
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => \Phar::running() ? dirname(\Phar::running(false)) : base_path(),
        ],
    ],
];
