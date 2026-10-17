<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->integer('version')->default(1);
            $table->string('difficulty')->default('medium'); // easy, medium, hard
            $table->integer('xp_reward')->default(50);
            $table->string('status')->default('draft'); // draft, published, archived
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
        });

        Schema::create('quest_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quest_id')->constrained()->cascadeOnDelete();
            $table->string('stage_key');
            $table->string('title');
            $table->text('story_content');
            $table->boolean('is_ending')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['quest_id', 'stage_key']);
        });

        Schema::create('quest_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained('quest_stages')->cascadeOnDelete();
            $table->text('choice_text');
            $table->foreignId('next_stage_id')->nullable()->constrained('quest_stages')->nullOnDelete();
            $table->text('feedback');
            $table->text('impact_summary')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('quest_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quest_id')->constrained()->cascadeOnDelete();
            $table->foreignId('current_stage_id')->nullable()->constrained('quest_stages')->nullOnDelete();
            $table->integer('quest_version')->default(1);
            $table->string('status')->default('in_progress'); // in_progress, completed, abandoned
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'quest_id', 'status']);
        });

        Schema::create('quest_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('quest_attempts')->cascadeOnDelete();
            $table->foreignId('stage_id')->constrained('quest_stages')->cascadeOnDelete();
            $table->foreignId('choice_id')->constrained('quest_choices')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description');
            $table->string('icon_path')->nullable();
            $table->json('criteria')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('badge_id')->constrained()->cascadeOnDelete();
            $table->timestamp('awarded_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'badge_id']);
        });

        Schema::create('xp_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('source_type'); // lesson, quiz, quest, campaign, event
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('idempotency_key')->unique();
            $table->integer('points');
            $table->string('description')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'source_type', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xp_transactions');
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('badges');
        Schema::dropIfExists('quest_responses');
        Schema::dropIfExists('quest_attempts');
        Schema::dropIfExists('quest_choices');
        Schema::dropIfExists('quest_stages');
        Schema::dropIfExists('quests');
    }
};
