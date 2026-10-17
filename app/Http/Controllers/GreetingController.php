<?php

namespace App\Http\Controllers;

use App\Models\Greeting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class GreetingController extends Controller
{
    /**
     * Display public greetings & wishes wall.
     */
    public function index(Request $request): Response
    {
        $greetings = Greeting::where('status', 'approved')
            ->latest()
            ->paginate(12);

        $featuredGreetings = Greeting::where('status', 'approved')
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        return Inertia::render('Greetings/Index', [
            'greetings' => $greetings,
            'featuredGreetings' => $featuredGreetings,
        ]);
    }

    /**
     * Store a public wish/greeting with photo upload.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'role_title' => 'required|string|max:100',
            'institution' => 'nullable|string|max:150',
            'message' => 'required|string|max:1000',
            'photo' => 'nullable|image|max:5120', // max 5MB
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('greetings', 'public');
            $photoPath = Storage::url($path);
        }

        Greeting::create([
            'name' => $request->input('name'),
            'role_title' => $request->input('role_title'),
            'institution' => $request->input('institution'),
            'message' => $request->input('message'),
            'photo_path' => $photoPath,
            'status' => 'approved', // Auto-approved for lively engagement, admin can moderate anytime
            'is_featured' => false,
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Ucapan dan fotomu telah terpasang di Semesta Remaja GENREVERSE.');
    }
}
