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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('region_id')->constrained('regions')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('issue_category');
            $table->text('problem_statement');
            $table->text('objective');
            $table->text('target_audience');
            $table->text('call_to_action');
            $table->date('planned_start')->nullable();
            $table->date('planned_end')->nullable();
            $table->string('status')->default('draft'); // draft, submitted, in_review, approved, published, completed, rejected
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['region_id', 'status']);
        });

        Schema::create('campaign_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->cascadeOnDelete();
            $table->foreignId('submitted_by')->constrained('users')->cascadeOnDelete();
            $table->date('activity_date');
            $table->text('summary');
            $table->integer('claimed_participants')->default(0);
            $table->integer('verified_participants')->nullable();
            $table->string('evidence_path')->nullable();
            $table->string('verification_status')->default('pending'); // pending, verified, rejected
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('region_id')->constrained('regions')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('location_label')->nullable();
            $table->boolean('is_online')->default(false);
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->integer('capacity')->nullable();
            $table->string('status')->default('published'); // draft, published, cancelled, completed
            $table->timestamps();

            $table->index(['region_id', 'status', 'starts_at']);
        });

        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('registration_status')->default('registered'); // registered, confirmed, cancelled
            $table->string('attendance_status')->default('unmarked'); // unmarked, attended, absent
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
        Schema::dropIfExists('events');
        Schema::dropIfExists('campaign_reports');
        Schema::dropIfExists('campaigns');
    }
};
