<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentReport;
use App\Models\Question;
use App\Models\Story;
use App\Services\ModerationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminModerationController extends Controller
{
    public function __construct(
        protected ModerationService $moderationService
    ) {}

    /**
     * Display moderation hub.
     */
    public function index(Request $request): Response
    {
        $pendingReports = ContentReport::with(['reporter.profile', 'actions.moderator.profile'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(8, ['*'], 'reports_page');

        $pendingStories = Story::with('author.profile')
            ->where('status', 'under_review')
            ->latest()
            ->paginate(8, ['*'], 'stories_page');

        $pendingQuestions = Question::with('user.profile')
            ->where('status', 'pending_review')
            ->latest()
            ->paginate(8, ['*'], 'questions_page');

        return Inertia::render('Admin/Moderation', [
            'pendingReports' => $pendingReports,
            'pendingStories' => $pendingStories,
            'pendingQuestions' => $pendingQuestions,
        ]);
    }

    /**
     * Resolve a content report.
     */
    public function resolveReport(Request $request, ContentReport $report): RedirectResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'string', 'in:dismiss,approved,hidden,deleted'],
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $this->moderationService->resolveReport(
            report: $report,
            moderator: $request->user(),
            action: $validated['action'],
            reason: $validated['reason']
        );

        return back()->with('success', 'Laporan konten berhasil ditangani.');
    }

    /**
     * Approve or reject a story.
     */
    public function moderateStory(Request $request, Story $story): RedirectResponse
    {
        $validated = $request->validate([
            'approved' => ['required', 'boolean'],
        ]);

        $this->moderationService->moderateStory(
            story: $story,
            moderator: $request->user(),
            approved: $validated['approved']
        );

        $msg = $validated['approved'] ? 'Cerita berhasil disetujui dan dipublikasikan!' : 'Cerita ditolak.';

        return back()->with('success', $msg);
    }

    /**
     * Approve or reject a question from Tanya Duta.
     */
    public function moderateQuestion(Request $request, Question $question): RedirectResponse
    {
        $validated = $request->validate([
            'approved' => ['required', 'boolean'],
        ]);

        $this->moderationService->moderateQuestion(
            question: $question,
            moderator: $request->user(),
            approved: $validated['approved']
        );

        $msg = $validated['approved'] ? 'Pertanyaan disetujui untuk dijawab oleh Duta GenRe!' : 'Pertanyaan ditolak.';

        return back()->with('success', $msg);
    }
}
