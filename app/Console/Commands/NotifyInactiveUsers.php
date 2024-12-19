<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;
use App\Models\User;
use App\Repositories\Interfaces\NotificationInterface;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class NotifyInactiveUsers extends Command
{
    protected $signature = 'notify:inactive-users';
    protected $description = 'Gửi thông báo cho người dùng có lasted_login quá 3 tháng và chưa được thông báo';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Lấy thời gian 3 tháng trước
        $thresholdDate = Carbon::now()->subMonths(3);

        // Lấy danh sách người dùng chưa được thông báo và không đăng nhập hơn 3 tháng
        $inactiveUsers = User::where('lasted_login', '<', $thresholdDate)
                             ->where('notified_inactive', 0)
                             ->get();

        foreach ($inactiveUsers as $user) {
            Notification::insert([
                'from_id' => 0,
                'to_id' => $user->id,
                'information' => 'Đã 3 tháng bạn chưa đăng nhập vào trang web',
                'from_type' => 'website',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $user->update([
                'notified_inactive' => 1,
            ]);
            Mail::to($user->email)->send(new \App\Mail\InactiveUserNotification($user));
        }
    }
}

