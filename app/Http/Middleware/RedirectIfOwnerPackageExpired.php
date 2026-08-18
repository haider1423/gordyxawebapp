<?php

namespace App\Http\Middleware;

use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\BasicExtended;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RedirectIfOwnerPackageExpired
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = getUser();
        if (is_null(UserPermissionHelper::currentPackage($user->id))) {
            Session::flash('warning', 'Owner account has expired');
            return redirect()->route('front.index');
        }
        $userBe = BasicExtended::query()
            ->where('user_id', $user->id)
            ->select('timezone')
            ->first();
        if($userBe?->timezone){
            date_default_timezone_set($userBe->timezone);
        }    
        return $next($request);
    }
}
