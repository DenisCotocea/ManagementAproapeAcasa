<x-filament::page>
    <h2 class="text-xl font-bold mb-4">Your Upcoming Google Calendar Events</h2>

    @if ($error)
        <div class="text-red-600 font-semibold mb-4">{{ $error }}</div>
    @endif

    @if (!$error && count($events) === 0)
        <p>No upcoming events found.</p>
    @endif

    @if (count($events) > 0)
        <ul class="list-disc pl-5 space-y-2">
            @foreach ($events as $event)
                <li>
                    <strong>{{ $event->name }}</strong><br>
                    Start: {{ \Carbon\Carbon::parse($event->startDateTime ?? $event->startDate)->toDayDateTimeString() }}<br>
                    End: {{ \Carbon\Carbon::parse($event->endDateTime ?? $event->endDate)->toDayDateTimeString() }}<br>
                    @if($event->description)
                        <em>{{ $event->description }}</em>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    @if (!$error)
        <a href="{{ route('google.calendar.connect') }}" class="mt-6 inline-block text-blue-600 hover:underline">
            @if(Auth::user()->getGoogleCalendarToken())
                Reconnect Google Calendar
            @else
                Connect Google Calendar
            @endif
        </a>
    @endif
</x-filament::page>
