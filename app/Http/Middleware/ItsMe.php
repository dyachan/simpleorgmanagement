<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class ItsMe
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // maybe add $request->id at end is a bad idea, because sometimes id can be another model.
        $commingId = $request->user_id || $request->userID || $request->userId || $request->user;
        $commingId = intval($commingId);
        if(!$commingId || Auth::id() != $commingId){
            return response('Unauthorized', 401);
        }
        return $next($request);
    }
}
