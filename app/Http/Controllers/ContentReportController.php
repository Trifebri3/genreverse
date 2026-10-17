<?php

namespace App\Http\Controllers;

use App\Services\ModerationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContentReportController extends Controller
{
    public function __construct(
        protected ModerationService $moderationService
    ) {}

    /**
     * Submit a report for inappropriate content.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content_type' => ['required', 'string', 'in:story,question,campaign,comment'],
            'content_id' => ['required', 'integer'],
            'reason' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->moderationService->reportContent(
            reporter: $request->user(),
            contentType: $validated['content_type'],
            contentId: $validated['content_id'],
            reason: $validated['reason'],
            description: $validated['description']
        );

        return back()->with('success', 'Laporan Anda telah diterima dengan aman dan akan ditinjau oleh tim moderasi GenRe.');
    }
}
