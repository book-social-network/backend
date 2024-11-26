<?php

namespace App\Http\Middleware;

use App\Models\View;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecordView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $ipAddress = $request->ip();
        $todayStart = now()->startOfDay();
        // Tìm lượt xem theo IP
        $view = View::where('ip_address', $ipAddress)->where('last_visited_at',$todayStart)->first();

        if (!$view) {
            $view = new View();
            $view->ip_address = $ipAddress;
            $view->last_visited_at = now();
            $view->save();
        }

        return $next($request);
    }
}
