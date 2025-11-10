<?php

namespace App\Http\Middleware;

use App\Constants\ApiEndpoints;
use App\Service\ProfileCacheService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('token')) {
            app(ProfileCacheService::class)->forget();
            return redirect()->route('login');
        }

        try {
            $headers = [
                "Authorization" => "Bearer " . session()->get('token'),
                "Accept" => "application/json"
            ];

            $response = Http::withHeaders($headers)->get(ApiEndpoints::BASE_URL . ApiEndpoints::CHECK_AUTH);

            $responseData = $response->json();

            if (!$response->successful()) {
                noty()->error($responseData['message']);
                app(ProfileCacheService::class)->forget();
                return redirect()->route('login');
            }

        } catch (\Exception $e) {
            Log::error('Check Auth Error: ' . $e->getMessage());
        }

        return $next($request);
    }
}
