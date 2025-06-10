<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Spatie\GoogleCalendar\Event;

class GoogleCalendar extends Page
{
    protected static string $view = 'filament.pages.google-calendar';
    protected static ?string $title = 'Google Calendar';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public $events = [];
    public $error;

    public function mount()
    {
        $user = Auth::user();
        $calendarService = $user->getGoogleCalendarService();

        if (!$calendarService) {
            $this->error = 'Google Calendar not connected. Please connect to see your events.';
            return;
        }

        try {
            $this->events = $calendarService->getEvents([
                'maxResults' => 10,
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => now()->toRfc3339String(),
            ]);
        } catch (\Exception $e) {
            $this->error = 'Failed to fetch events: ' . $e->getMessage();
        }
    }
}
