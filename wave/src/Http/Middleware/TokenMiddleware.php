<?php

namespace Wave\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Wave\ApiToken;

class TokenMiddleware
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ?string $guard = null)
    {
        if ($request->token && strlen($request->token) <= 60) {
            $api_token = ApiToken::where('token', '=', $request->token)->first();
            if (isset($api_token->id)) {
                $token = JWTAuth::fromUser($api_token->user);
            }

        } else {
            $this->auth->authenticate();
        }

        // Then process the next request if every tests passed.
        return $next($request);
    }
}
