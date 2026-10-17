<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Services\GamificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    /**
     * Display community events calendar.
     */
    public function index(Request $request): Response
    {
        $events = Event::with(['organizer.profile', 'region'])
            ->where('status', 'published')
            ->orderBy('starts_at')
            ->paginate(9);

        $user = $request->user();
        $registeredEventIds = $user ? EventRegistration::where('user_id', $user->id)
            ->pluck('event_id')
            ->toArray() : [];

        return Inertia::render('Community/Events', [
            'events' => $events,
            'registeredEventIds' => $registeredEventIds,
        ]);
    }

    /**
     * Show event detail.
     */
    public function show(Request $request, Event $event): Response
    {
        $user = $request->user();
        $registration = $user ? EventRegistration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first() : null;

        $registrationsCount = $event->registrations()->count();

        return Inertia::render('Community/EventDetail', [
            'event' => $event->load(['organizer.profile', 'region']),
            'isRegistered' => $registration !== null,
            'registrationsCount' => $registrationsCount,
        ]);
    }

    /**
     * Register for an event.
     */
    public function register(Request $request, Event $event): RedirectResponse
    {
        $user = $request->user();

        // Check if event is full
        if ($event->capacity && $event->registrations()->count() >= $event->capacity) {
            return back()->with('error', 'Maaf, kuota pendaftaran untuk kegiatan ini sudah penuh.');
        }

        // Register idempotently
        $reg = EventRegistration::firstOrCreate(
            ['event_id' => $event->id, 'user_id' => $user->id],
            ['registration_status' => 'registered', 'attendance_status' => 'unmarked']
        );

        $this->gamificationService->awardXp(
            user: $user,
            points: 15,
            sourceType: 'event',
            sourceId: $event->id,
            description: "Mendaftar kegiatan: {$event->title}"
        );

        return back()->with('success', 'Selamat, kamu berhasil terdaftar dalam kegiatan ini! +15 XP diperoleh.');
    }
}
