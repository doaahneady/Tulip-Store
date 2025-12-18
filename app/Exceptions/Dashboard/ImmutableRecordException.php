<?php

namespace App\Exceptions\Dashboard;

use Exception;

class ImmutableRecordException extends DashboardException
{
    public function __construct(string $message = 'This record cannot be modified')
    {
        parent::__construct($message);
    }
}
