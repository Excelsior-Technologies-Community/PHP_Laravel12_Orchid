<?php

namespace App\Orchid\Screens;

use App\Models\ActivityLog;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class ActivityLogScreen extends Screen
{
    public function name(): ?string
    {
        return 'Activity Logs';
    }

    public function query(): iterable
    {
        return [
            'logs' => ActivityLog::query()->latest()->paginate(10),
        ];
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('logs', [
                TD::make('id', 'ID')
                    ->render(fn ($log) => (string) $log->id),

                TD::make('user_name', 'User')
                    ->render(fn ($log) => e($log->user_name ?? '-')),

                TD::make('action', 'Action')
                    ->render(fn ($log) =>
                        "<span style='color:blue;font-weight:600'>{$log->action}</span>"
                    ),

                TD::make('module', 'Module')
                    ->render(fn ($log) => e($log->module ?? '-')),

                TD::make('description', 'Description')
                    ->render(fn ($log) => e($log->description ?? '-')),

                TD::make('ip_address', 'IP Address')
                    ->render(fn ($log) => e($log->ip_address ?? '-')),

                TD::make('created_at', 'Time')
                    ->render(fn ($log) =>
                        optional($log->created_at)->format('d-m-Y H:i') ?? '-'
                    ),
            ]),
        ];
    }
}