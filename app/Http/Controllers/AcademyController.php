<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\LearningModule;
use App\Models\Lesson;
use App\Models\UserLessonProgress;
use App\Services\LearningService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AcademyController extends Controller
{
    public function __construct(
        protected LearningService $learningService
    ) {}

    /**
     * Display public academy catalogue with category filters and search.
     */
    public function index(Request $request): Response
    {
        $categorySlug = $request->input('category');
        $search = $request->input('q');

        $query = LearningModule::with(['category', 'lessons' => fn ($q) => $q->select('id', 'module_id', 'estimated_minutes')])
            ->where('status', 'published');

        if ($categorySlug) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        $modules = $query->latest('published_at')->paginate(9)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        // User lesson completion map if logged in
        $completedLessonIds = [];
        if ($user = $request->user()) {
            $completedLessonIds = UserLessonProgress::where('user_id', $user->id)
                ->where('status', 'completed')
                ->pluck('lesson_id')
                ->toArray();
        }

        return Inertia::render('Academy/Index', [
            'modules' => $modules,
            'categories' => $categories,
            'selectedCategory' => $categorySlug,
            'searchQuery' => $search,
            'completedLessonIds' => $completedLessonIds,
        ]);
    }

    /**
     * Display module detail and syllabus.
     */
    public function show(Request $request, string $slug): Response
    {
        $module = LearningModule::with([
            'category',
            'author.profile',
            'lessons' => fn ($q) => $q->orderBy('sort_order'),
            'quizzes',
        ])
            ->where('slug', $slug)
            ->firstOrFail();

        $user = $request->user();
        $progress = $user ? $this->learningService->getModuleProgress($user, $module) : null;
        $completedLessonIds = $user ? UserLessonProgress::where('user_id', $user->id)
            ->where('status', 'completed')
            ->pluck('lesson_id')
            ->toArray() : [];

        return Inertia::render('Academy/Show', [
            'module' => $module,
            'progress' => $progress,
            'completedLessonIds' => $completedLessonIds,
        ]);
    }

    /**
     * Display single lesson content viewer.
     */
    public function lesson(Request $request, string $slug, Lesson $lesson): Response
    {
        $module = LearningModule::with([
            'lessons' => fn ($q) => $q->orderBy('sort_order'),
            'quizzes',
        ])
            ->where('slug', $slug)
            ->firstOrFail();

        // Ensure lesson belongs to module
        abort_unless($lesson->module_id === $module->id, 404);

        $user = $request->user();
        $isCompleted = false;

        if ($user) {
            $isCompleted = UserLessonProgress::where('user_id', $user->id)
                ->where('lesson_id', $lesson->id)
                ->where('status', 'completed')
                ->exists();
        }

        // Find previous and next lessons
        $allLessons = $module->lessons;
        $currentIndex = $allLessons->search(fn ($l) => $l->id === $lesson->id);
        $previousLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;

        return Inertia::render('Academy/Lesson', [
            'module' => $module,
            'lesson' => $lesson,
            'isCompleted' => $isCompleted,
            'previousLesson' => $previousLesson,
            'nextLesson' => $nextLesson,
            'quiz' => $module->quizzes->first(),
        ]);
    }

    /**
     * Mark a lesson completed and award XP.
     */
    public function complete(Request $request, Lesson $lesson): RedirectResponse
    {
        $user = $request->user();
        $this->learningService->completeLesson($user, $lesson);

        return back()->with('success', 'Materi berhasil diselesaikan! +20 XP ditambahkan ke profilmu.');
    }
}
