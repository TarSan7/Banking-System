<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $locale)
    {
        $uri = $request->path();
        $uri = explode('/', $uri);
        App::setLocale(Arr::get($uri, 0, 'en'));
        return $next($request);
    }
}
