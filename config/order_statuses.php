<?php

return [
    'canonical' => [
        'pending',
        'confirmed',
        'processing',
        'ready',
        'shipped',
        'out_for_delivery',
        'delivered',
        'done',
        'failed',
        'cancelled',
        'refunded',
        'returned',
    ],
    'terminal' => [
        // Revenue/financial finalization happens on 'done' (completed by CS)
        'done',
    ],
    'aliases' => [
        'in_transit' => 'out_for_delivery',
        'completed' => 'done',
    ],
];
