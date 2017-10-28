<?php

return [
    'notifications' => [
        'success' => "`[:app@:hostname]` *Deployment succeeded!*\n\n*Revision:* :revision\n*Environment:* :env.",
        'failed' => "`[:app@:hostname]` *Deployment failed!*\n\n*Revision:* :revision\n*Environment:* :env.",
        'button_label' => 'Open App',
    ],
];
