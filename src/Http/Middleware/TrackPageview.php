<?php

namespace Pirsch\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Pirsch\Facades\Pirsch;

class TrackPageview
{
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        foreach (config('pirsch.excluded_headers') as $header) {
            if ($request->hasHeader($header)) {
                return $response;
            }
        }

        foreach (config('pirsch.excluded_routes') as $route) {
            if (str_starts_with($request->route()->uri, $route)) {
                return $response;
            }
        }

        Pirsch::track();

        return $response;
    }
}
