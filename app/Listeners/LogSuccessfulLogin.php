<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        ActivityLog::create([
            'user_name'   => $event->user->name ?? 'Unknown',
            'action'      => 'Login',
            'module'      => 'Authentication',
            'description' => 'User logged in successfully',
            'ip_address'  => Request::ip(),
        ]);
    }
}