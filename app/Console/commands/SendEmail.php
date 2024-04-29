<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MainModel;
use Illuminate\Support\Facades\Mail;
use App\Mail\LikesCountEmail;

class SendEmail extends Command
{
    protected $signature = 'sendemail:cron';
    protected $description = 'Send email to admin';
    public function handle()
    {
        $users = MainModel::fetchUsersForEmail();
        $names = $users->pluck('name')->toArray();
        Mail::to('admin@example.com')->send(new LikesCountEmail($names));
    }
}
