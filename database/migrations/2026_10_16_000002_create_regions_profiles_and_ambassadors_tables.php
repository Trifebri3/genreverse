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
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('level'); // national, province, regency
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('user_region_scopes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('region_id')->constrained()->cascadeOnDelete();
            $table->string('scope_type')->default('primary');
            $table->timestamps();
            $table->unique(['user_id', 'region_id', 'scope_type']);
        });

        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('display_name');
            $table->string('avatar_path')->nullable();
            $table->text('bio')->nullable();
            $table->integer('birth_year')->nullable();
            $table->string('preferred_language')->default('id');
            $table->json('privacy_settings')->nullable();
            $table->timestamps();
        });

        Schema::create('ambassadors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('region_id')->constrained()->cascadeOnDelete();
            $table->string('organization_name')->nullable();
            $table->string('verification_status')->default('pending'); // pending, verified, rejected
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->date('valid_until')->nullable();
            $table->timestamps();
            $table->index(['region_id', 'verification_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ambassadors');
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('user_region_scopes');
        Schema::dropIfExists('regions');
    }
};
