<?php

namespace Si6\Base\Utils;

use Illuminate\Support\Facades\Route;

class MonitoringUtil
{
    public function services($service)
    {
        Route::get("monitoring/services/$service", function () {
            $content = json_decode(file_get_contents(base_path('composer.json')));

            $name    = $content->name ?? 'Unknown';
            $version = $content->version ?? 'Unknown';

            return response()->json(compact('name', 'version'));
        });
    }
}
