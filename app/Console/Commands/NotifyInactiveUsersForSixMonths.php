<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class NotifyInactiveUsersForSixMonths extends Command
{
    protected $signature = 'notify:inactive-users-6-months';
    protected $description = 'Gửi email cho người dùng không đăng nhập quá 6 tháng và đã được thông báo (notified_inactive = 1)';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Lấy thời gian 6 tháng trước
        $thresholdDate = Carbon::now()->subMonths(6);

        // Lấy danh sách người dùng đã được thông báo (notified_inactive = 1) và không đăng nhập quá 6 tháng
        $inactiveUsers = User::where('lasted_login', '<', $thresholdDate)
                             ->where('notified_inactive', 1)
                             ->get();

        // Duyệt qua các người dùng và gửi email
        foreach ($inactiveUsers as $user) {
            // Gửi email cho người dùng
            Mail::to($user->email)->send(new \App\Mail\DeleteUserNotification($user));

            User::where('email', $user->email)->delete();
        }

    }
}
