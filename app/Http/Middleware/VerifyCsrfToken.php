<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',
    ];

    public function __construct(
        \Illuminate\Contracts\Foundation\Application $app,
        \Illuminate\Contracts\Encryption\Encrypter $encrypter
    ) {
        parent::__construct($app, $encrypter);

        if ($app->environment('local')) {
            $this->except = array_values(array_unique(array_merge($this->except, [
                'trader/login',
                'trader/logout',
            ])));
        }
    }
}
