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

        // Tìm lượt xem theo IP
        $view = View::where('ip_address', $ipAddress)->first();

        if ($view) {
            // Nếu đã có, cập nhật thời gian truy cập
            $view->last_visited_at = now();
            $view->save();
        } else {
            // Nếu chưa có, tạo mới bản ghi
            $view = new View();
            $view->ip_address = $ipAddress;
            $view->last_visited_at = now();
            $view->save();
        }

        return $next($request);
    }
}
