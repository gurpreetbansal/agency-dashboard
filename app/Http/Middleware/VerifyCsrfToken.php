<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'connect_google_analytics/*',  'postbackAddKeyResponse','fetching_updated_keywords','cron_postbackAddKeyResponse','connect_gmb/*','stripe_webhooks','stripe_postback_webhooks','cron_graph','search_console_graph','connect_search_console/*','search_console_cron'
    ];
}
