<?php

namespace App\Http\Middleware;

use Closure;

class TerminateMiddleware
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        // Store the session data...
        $fileName 	=	json_decode($response->content(), true);
        // $fileName 	=	json_decode(json_encode($response->content()), True);
        // var_dump(public_path($fileName['fileName']));
        \File::delete(public_path($fileName['fileName']));
    }
}