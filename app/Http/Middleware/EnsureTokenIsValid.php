<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTokenIsValid
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
        $access_token = $request->header('token');
        
        if ($access_token !== env('WEB_TOKEN')) {
            $response = [
                'success' => false,
                
                'message' => 'token is invalid',
            ];
    
    
            return response()->json($response, 200);
        }
        return $next($request);
    }
}
