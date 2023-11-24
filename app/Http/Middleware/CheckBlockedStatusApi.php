<?php

namespace App\Http\Middleware;

use App\Helpers\ApiHelper;
use Closure;
use Illuminate\Http\Request;

class CheckBlockedStatusApi extends ApiHelper
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth('api')->check()) {
            $user = auth('api')->user();

            if ($user->active_status === 4) {
                return $this->errorRespond('AccountBlocked', config('constants.CODE.permissionDenied'));
            }
        }
        return $next($request);
    }
}