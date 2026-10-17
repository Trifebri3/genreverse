<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Greeting;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->admin = User::where('email', 'kingnasar@siyota.org')->first()
            ?? User::factory()->create();

        $adminRole = Role::firstOrCreate(['slug' => 'admin_nasional'], ['name' => 'Admin Nasional']);
        $this->admin->roles()->syncWithoutDetaching([$adminRole->id]);

        $this->regularUser = User::factory()->create();
    }

    public function test_admin_can_view_admin_articles_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.articles.index'));

        $response->assertOk();
    }

    public function test_admin_can_create_article(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post(route('admin.articles.store'), [
            'title' => 'Kegiatan Konselor Sebaya GenRe 2026',
            'category' => 'Kegiatan',
            'excerpt' => 'Ringkasan kegiatan konselor sebaya tingkat nasional.',
            'content' => 'Konten lengkap kegiatan di hotel merdeka jakarta.',
            'author_name' => 'Tim Humas',
            'is_published' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('articles', [
            'title' => 'Kegiatan Konselor Sebaya GenRe 2026',
            'category' => 'Kegiatan',
        ]);
    }

    public function test_admin_can_upload_document(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('laporan_tahunan.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('admin.documents.store'), [
            'title' => 'Laporan Tahunan GenRe 2026',
            'category' => 'Laporan Tahunan',
            'description' => 'Arsip laporan resmi capaian tahunan.',
            'file' => $file,
            'is_public' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('documents', [
            'title' => 'Laporan Tahunan GenRe 2026',
            'file_name' => 'laporan_tahunan.pdf',
        ]);
    }

    public function test_admin_can_upload_photo(): void
    {
        Storage::fake('public');

        $photo = UploadedFile::fake()->image('jambore.jpg');

        $response = $this->actingAs($this->admin)->post(route('admin.photos.store'), [
            'title' => 'Jambore Remaja 2026',
            'album_name' => 'Ajang Kreativitas',
            'caption' => 'Keseruan acara jambore nasional.',
            'photo' => $photo,
            'event_date' => '2026-10-18',
            'is_featured' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('gallery_photos', [
            'title' => 'Jambore Remaja 2026',
            'album_name' => 'Ajang Kreativitas',
            'is_featured' => true,
        ]);
    }

    public function test_admin_can_moderate_greetings(): void
    {
        $greeting = Greeting::create([
            'name' => 'Rizky',
            'role_title' => 'Duta GenRe',
            'message' => 'Semangat terus untuk seluruh remaja!',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.greetings.status', $greeting->id), [
                'status' => 'approved',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('greetings', [
            'id' => $greeting->id,
            'status' => 'approved',
        ]);
    }

    public function test_public_can_submit_greeting_with_photo(): void
    {
        Storage::fake('public');

        $photo = UploadedFile::fake()->image('profil_saya.jpg');

        $response = $this->post(route('greetings.store'), [
            'name' => 'Anisa Rahma',
            'role_title' => 'Konselor Sebaya',
            'institution' => 'SMA Negeri 3 Bandung',
            'message' => 'GENREVERSE keren banget, bermanfaat sekali untuk wadah konsultasi kami!',
            'photo' => $photo,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('greetings', [
            'name' => 'Anisa Rahma',
            'role_title' => 'Konselor Sebaya',
            'status' => 'approved',
        ]);
    }

    public function test_public_can_view_articles_and_documents(): void
    {
        $article = Article::create([
            'title' => 'Berita Pelatihan Duta',
            'slug' => 'berita-pelatihan-duta',
            'category' => 'Edukasi',
            'excerpt' => 'Pelatihan untuk duta.',
            'content' => 'Konten lengkap.',
            'is_published' => true,
        ]);

        $this->get(route('articles.index'))->assertOk();
        $this->get(route('articles.show', $article->slug))->assertOk();
        $this->get(route('documents.index'))->assertOk();
        $this->get(route('gallery.index'))->assertOk();
    }

    public function test_admin_can_update_and_delete_article(): void
    {
        $article = Article::create([
            'title' => 'Judul Awal',
            'slug' => 'judul-awal',
            'category' => 'Kegiatan',
            'excerpt' => 'Ringkasan awal',
            'content' => 'Konten awal',
            'is_published' => false,
        ]);

        $updateResponse = $this->actingAs($this->admin)->post(route('admin.articles.update', $article->id), [
            'title' => 'Judul Baru Diperbarui',
            'category' => 'Advokasi',
            'excerpt' => 'Ringkasan baru',
            'content' => 'Konten baru yang lebih komprehensif',
            'is_published' => true,
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Judul Baru Diperbarui',
            'category' => 'Advokasi',
            'is_published' => true,
        ]);

        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.articles.destroy', $article->id));
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }

    public function test_admin_can_create_update_and_delete_greeting(): void
    {
        Storage::fake('public');

        // 1. Create greeting
        $createResponse = $this->actingAs($this->admin)->post(route('admin.greetings.store'), [
            'name' => 'Nasar King',
            'role_title' => 'Pembina GenRe Nasional',
            'institution' => 'BKKBN RI',
            'message' => 'Maju terus pemuda Indonesia!',
            'status' => 'approved',
            'is_featured' => true,
        ]);

        $createResponse->assertRedirect();
        $this->assertDatabaseHas('greetings', [
            'name' => 'Nasar King',
            'status' => 'approved',
            'is_featured' => true,
        ]);

        $greeting = Greeting::where('name', 'Nasar King')->first();

        // 2. Update greeting
        $updateResponse = $this->actingAs($this->admin)->post(route('admin.greetings.update', $greeting->id), [
            'name' => 'King Nasar Siregar',
            'role_title' => 'Ketua Pembina GenRe',
            'institution' => 'SIYOTA Foundation',
            'message' => 'Pesan harapan yang telah diedit dan diperbarui.',
            'status' => 'approved',
            'is_featured' => false,
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('greetings', [
            'id' => $greeting->id,
            'name' => 'King Nasar Siregar',
            'institution' => 'SIYOTA Foundation',
            'is_featured' => false,
        ]);

        // 3. Delete greeting
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.greetings.destroy', $greeting->id));
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('greetings', ['id' => $greeting->id]);
    }

    public function test_admin_can_view_publication_dashboard_with_all_metrics(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertOk();
    }
}

