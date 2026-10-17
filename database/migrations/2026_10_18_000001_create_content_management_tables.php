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
        // 1. Berita & Artikel
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('Kegiatan');
            $table->text('excerpt');
            $table->longText('content');
            $table->string('cover_image')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('author_name')->default('Humas GenRe Indonesia');
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamps();

            $table->index('category');
            $table->index('is_published');
            $table->index('created_at');
        });

        // 2. Dokumen & Laporan (PDF, Word, Excel, dll)
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('category')->default('Laporan');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('file_type', 30)->default('pdf');
            $table->unsignedInteger('downloads_count')->default(0);
            $table->boolean('is_public')->default(true);
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('category');
            $table->index('is_public');
        });

        // 3. Galeri Foto Kegiatan
        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('album_name')->default('Kegiatan GenRe');
            $table->text('caption')->nullable();
            $table->string('image_path');
            $table->date('event_date')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('album_name');
            $table->index('is_featured');
        });

        // 4. Ucapan & Harapan (Wishes & Testimoni beserta Foto)
        Schema::create('greetings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role_title')->default('Remaja GenRe');
            $table->string('institution')->nullable();
            $table->text('message');
            $table->string('photo_path')->nullable();
            $table->string('status', 20)->default('approved'); // pending, approved, rejected
            $table->boolean('is_featured')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('greetings');
        Schema::dropIfExists('gallery_photos');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('articles');
    }
};
