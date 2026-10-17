<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Greeting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AdminGreetingController extends Controller
{
    /**
     * Display a listing of greetings and testimonials.
     */
    public function index(Request $request): Response
    {
        $query = Greeting::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('role_title', 'like', "%{$search}%")
                    ->orWhere('institution', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        $greetings = $query->latest()->paginate(12)->withQueryString();

        $stats = [
            'total' => Greeting::count(),
            'pending' => Greeting::where('status', 'pending')->count(),
            'approved' => Greeting::where('status', 'approved')->count(),
            'rejected' => Greeting::where('status', 'rejected')->count(),
            'featured' => Greeting::where('is_featured', true)->count(),
        ];

        return Inertia::render('Admin/Greetings/Index', [
            'greetings' => $greetings,
            'stats' => $stats,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Store a newly created greeting.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'role_title' => 'required|string|max:150',
            'institution' => 'nullable|string|max:200',
            'message' => 'required|string|max:2000',
            'photo' => 'nullable|image|max:5120',
            'photo_url' => 'nullable|url|max:1000',
            'status' => 'required|in:pending,approved,rejected',
            'is_featured' => 'boolean',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('greetings', 'public');
            $photoPath = Storage::url($path);
        } elseif (! empty($validated['photo_url'])) {
            $photoPath = $validated['photo_url'];
        }

        Greeting::create([
            'name' => $validated['name'],
            'role_title' => $validated['role_title'],
            'institution' => $validated['institution'] ?? null,
            'message' => $validated['message'],
            'photo_path' => $photoPath,
            'status' => $validated['status'],
            'is_featured' => $request->boolean('is_featured', false),
            'approved_at' => $validated['status'] === 'approved' ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Ucapan baru berhasil ditambahkan!');
    }

    /**
     * Update an existing greeting.
     */
    public function update(Request $request, Greeting $greeting): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'role_title' => 'required|string|max:150',
            'institution' => 'nullable|string|max:200',
            'message' => 'required|string|max:2000',
            'photo' => 'nullable|image|max:5120',
            'photo_url' => 'nullable|url|max:1000',
            'status' => 'required|in:pending,approved,rejected',
            'is_featured' => 'boolean',
        ]);

        $photoPath = $greeting->photo_path;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('greetings', 'public');
            $photoPath = Storage::url($path);
        } elseif ($request->filled('photo_url')) {
            $photoPath = $validated['photo_url'];
        }

        $updateData = [
            'name' => $validated['name'],
            'role_title' => $validated['role_title'],
            'institution' => $validated['institution'] ?? null,
            'message' => $validated['message'],
            'photo_path' => $photoPath,
            'status' => $validated['status'],
            'is_featured' => $request->boolean('is_featured', $greeting->is_featured),
        ];

        if ($validated['status'] === 'approved' && is_null($greeting->approved_at)) {
            $updateData['approved_at'] = now();
        } elseif ($validated['status'] !== 'approved') {
            $updateData['approved_at'] = null;
        }

        $greeting->update($updateData);

        return redirect()->back()->with('success', 'Data ucapan berhasil diperbarui!');
    }

    /**
     * Update greeting moderation status (approve / reject).
     */
    public function updateStatus(Request $request, Greeting $greeting): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $updateData = [
            'status' => $validated['status'],
        ];

        if ($validated['status'] === 'approved' && is_null($greeting->approved_at)) {
            $updateData['approved_at'] = now();
        }

        $greeting->update($updateData);

        return redirect()->back()->with('success', "Status ucapan berhasil diubah menjadi {$validated['status']}!");
    }

    /**
     * Toggle featured status on greeting.
     */
    public function toggleFeatured(Greeting $greeting): RedirectResponse
    {
        $greeting->update(['is_featured' => ! $greeting->is_featured]);

        return redirect()->back()->with('success', 'Status sorotan ucapan berhasil diperbarui!');
    }

    /**
     * Delete greeting.
     */
    public function destroy(Greeting $greeting): RedirectResponse
    {
        $greeting->delete();

        return redirect()->back()->with('success', 'Ucapan berhasil dihapus!');
    }
}
