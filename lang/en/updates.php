<?php

return [
    'title' => 'System Information & Version',
    'sub' => 'Local installation version details and official YUKelola distribution information.',
    'sections' => [
        'status' => 'Version & Environment Information',
        'status_sub' => 'Details of the application version currently active on this device.',
        'distribution' => 'Official Distribution & Updates',
        'distribution_sub' => 'Official distribution channels and update policy for YUKelola.',
    ],
    'fields' => [
        'current_version' => 'Current Application Version',
        'app_name' => 'Application Name',
        'php_version' => 'PHP Runtime Version',
        'laravel_version' => 'Framework Version',
        'database_driver' => 'Database Driver',
        'channel' => 'Distribution Channel',
        'channel_value' => 'Official Standalone Installer Release',
        'distribution_note' => 'YUKelola application updates are officially delivered through manual standalone installer packages (Windows Desktop .exe setup and Android .apk files). The system does not utilize third-party online update feeds or in-place file replacement in order to safeguard local store data sovereignty and runtime stability.',
    ],
];
