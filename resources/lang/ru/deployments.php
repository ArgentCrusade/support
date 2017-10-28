<?php

return [
    'notifications' => [
        'success' => "`[:app@:hostname]` *Приложение обновлено!*\n\n*Ревизия:* :revision\n*Среда:* :env.",
        'failed' => "`[:app@:hostname]` *Не удалось обновить приложение!*\n\n*Ревизия:* :revision\n*Среда:* :env.",
        'button_label' => 'Открыть',
    ],
];
