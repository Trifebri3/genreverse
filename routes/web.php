<?php

use App\Http\Controllers\AcademyController;
use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminAuditLogController;
use App\Http\Controllers\Admin\AdminCampaignController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDocumentController;
use App\Http\Controllers\Admin\AdminGreetingController;
use App\Http\Controllers\Admin\AdminModerationController;
use App\Http\Controllers\Admin\AdminPhotoController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AskDutaController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ContentReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GreetingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImpactMapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');

// Berita & Artikel
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');

// Dokumen & Laporan
Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

// Galeri Kegiatan
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

// Ucapan & Harapan (Wishes)
Route::get('/greetings', [GreetingController::class, 'index'])->name('greetings.index');
Route::post('/greetings', [GreetingController::class, 'store'])->name('greetings.store');

// GenRe Academy
Route::get('/academy', [AcademyController::class, 'index'])->name('academy.index');
Route::get('/academy/{slug}', [AcademyController::class, 'show'])->name('academy.show');
Route::get('/academy/{slug}/lessons/{lesson}', [AcademyController::class, 'lesson'])->name('academy.lesson');
Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');

// LifeQuest
Route::get('/lifequest', [QuestController::class, 'index'])->name('lifequest.index');
Route::get('/lifequest/{quest:slug}', [QuestController::class, 'show'])->name('lifequest.show');
Route::get('/lifequest/{quest:slug}/play', [QuestController::class, 'play'])->name('lifequest.play');

// Advocacy Lab
Route::get('/advocacy', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/advocacy/{campaign:slug}', [CampaignController::class, 'show'])->name('campaigns.show');

// Community Hub
Route::get('/community/events', [EventController::class, 'index'])->name('events.index');
Route::get('/community/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/stories', [StoryController::class, 'index'])->name('stories.index');
Route::get('/ask', [AskDutaController::class, 'index'])->name('ask.index');
Route::get('/impact-map', [ImpactMapController::class, 'index'])->name('impact_map');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Learning & Quizzes Progress
    Route::post('/lessons/{lesson}/complete', [AcademyController::class, 'complete'])->name('lessons.complete');
    Route::post('/quizzes/{quiz}/attempts', [QuizController::class, 'startAttempt'])->name('quizzes.attempts.start');
    Route::post('/quiz-attempts/{attempt}/submit', [QuizController::class, 'submitAttempt'])->name('quiz_attempts.submit');

    // LifeQuest progression
    Route::post('/lifequest/{quest}/attempts', [QuestController::class, 'startAttempt'])->name('lifequest.attempts.start');
    Route::post('/quest-attempts/{attempt}/choose', [QuestController::class, 'choose'])->name('quest_attempts.choose');

    // Advocacy creation & reporting
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::post('/campaigns/{campaign}/submit', [CampaignController::class, 'submitReview'])->name('campaigns.submit_review');
    Route::post('/campaigns/{campaign}/reports', [CampaignController::class, 'submitReport'])->name('campaigns.submit_report');

    // Community interactions
    Route::post('/community/events/{event}/register', [EventController::class, 'register'])->name('events.register');
    Route::post('/stories', [StoryController::class, 'store'])->name('stories.store');
    Route::post('/ask', [AskDutaController::class, 'store'])->name('ask.store');
    Route::post('/ask/{question}/answer', [AskDutaController::class, 'answer'])->name('ask.answer');

    // Safety & Reporting
    Route::post('/content-reports', [ContentReportController::class, 'store'])->name('content_reports.store');
});

/*
|--------------------------------------------------------------------------
| Admin & Moderation Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin_nasional,admin_wilayah,super_admin,moderator'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Berita & Artikel
        Route::get('/articles', [AdminArticleController::class, 'index'])->name('articles.index');
        Route::post('/articles', [AdminArticleController::class, 'store'])->name('articles.store');
        Route::post('/articles/{article}/update', [AdminArticleController::class, 'update'])->name('articles.update');
        Route::post('/articles/{article}/toggle-publish', [AdminArticleController::class, 'togglePublish'])->name('articles.toggle_publish');
        Route::delete('/articles/{article}', [AdminArticleController::class, 'destroy'])->name('articles.destroy');

        // Dokumen & Laporan
        Route::get('/documents', [AdminDocumentController::class, 'index'])->name('documents.index');
        Route::post('/documents', [AdminDocumentController::class, 'store'])->name('documents.store');
        Route::post('/documents/{document}/update', [AdminDocumentController::class, 'update'])->name('documents.update');
        Route::delete('/documents/{document}', [AdminDocumentController::class, 'destroy'])->name('documents.destroy');

        // Galeri Foto
        Route::get('/photos', [AdminPhotoController::class, 'index'])->name('photos.index');
        Route::post('/photos', [AdminPhotoController::class, 'store'])->name('photos.store');
        Route::post('/photos/{photo}/update', [AdminPhotoController::class, 'update'])->name('photos.update');
        Route::post('/photos/{photo}/toggle-featured', [AdminPhotoController::class, 'toggleFeatured'])->name('photos.toggle_featured');
        Route::delete('/photos/{photo}', [AdminPhotoController::class, 'destroy'])->name('photos.destroy');

        // Ucapan & Harapan (Wishes & Testimoni)
        Route::get('/greetings', [AdminGreetingController::class, 'index'])->name('greetings.index');
        Route::post('/greetings', [AdminGreetingController::class, 'store'])->name('greetings.store');
        Route::post('/greetings/{greeting}/update', [AdminGreetingController::class, 'update'])->name('greetings.update');
        Route::post('/greetings/{greeting}/status', [AdminGreetingController::class, 'updateStatus'])->name('greetings.status');
        Route::post('/greetings/{greeting}/toggle-featured', [AdminGreetingController::class, 'toggleFeatured'])->name('greetings.toggle_featured');
        Route::delete('/greetings/{greeting}', [AdminGreetingController::class, 'destroy'])->name('greetings.destroy');

        // Campaign reviews & reports verification
        Route::get('/campaigns', [AdminCampaignController::class, 'index'])->name('campaigns.index');
        Route::post('/campaigns/{campaign}/review', [AdminCampaignController::class, 'review'])->name('campaigns.review');
        Route::get('/campaign-reports', [AdminCampaignController::class, 'reports'])->name('campaign_reports.index');
        Route::post('/campaign-reports/{report}/verify', [AdminCampaignController::class, 'verifyReport'])->name('campaign_reports.verify');

        // Moderation hub
        Route::get('/moderation', [AdminModerationController::class, 'index'])->name('moderation.index');
        Route::post('/moderation/reports/{report}/resolve', [AdminModerationController::class, 'resolveReport'])->name('moderation.resolve_report');
        Route::post('/moderation/stories/{story}/review', [AdminModerationController::class, 'moderateStory'])->name('moderation.story');
        Route::post('/moderation/questions/{question}/review', [AdminModerationController::class, 'moderateQuestion'])->name('moderation.question');

        // System audit
        Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit_logs.index');
    });

require __DIR__.'/auth.php';
