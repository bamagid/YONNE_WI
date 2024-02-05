<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Reseau;
use App\Models\AdminSystem;
use Tests\Feature\UserControllerTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReseauControllerTest extends TestCase
{
    public function testReseauIndex()
    {
    $this->artisan('migrate:fresh');
        Reseau::factory()->create();
        $response = $this->get('/api/reseaus');
        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "reseaux" => $response->json('reseaux')
            ]);
    }

    public function testReseauShow()
    {
        $this->artisan('migrate:fresh');
        $reseau = Reseau::factory()->create();
        $response = $this->get("/api/reseaus/$reseau->id");
        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "reseau" => $response->json('reseau')
            ]);
    }

    public function testReseauStore()
    {
        $this->artisan('migrate:fresh');
        $admin = AdminSystem::factory()->create();
        $response = $this->actingAs($admin, 'admin')->post('/api/reseaus', [
            "nom" => "dakar dem dikk",
        ]);

        $response->assertStatus(201)
            ->assertJson([
                "message" => $response->json('message'),
                "reseau" => $response->json('reseau')
            ]);
    }

    public function testReseauUpdate()
    {
        $this->artisan('migrate:fresh');
        $reseau = Reseau::factory()->create();
        $admin = AdminSystem::factory()->create();
        $response = $this->actingAs($admin, 'admin')->patch("/api/reseaus/$reseau->id", [
            "nom" => "brt",
        ]);

        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "reseau" => $response->json('reseau')
            ]);
    }
    public function Details()
    {
        $this->artisan('migrate:fresh');
        UserControllerTest::createUser();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch("/api/reseaus/details", [
            'description' => "La description de mon reseau",
            "telephone" => 778552211,
            "email" => null,
        ]);
        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "reseau" => $response->json('reseau')
            ]);
    }

    public function testReseauDestroy()
    {
        $this->artisan('migrate:fresh');
        $admin = AdminSystem::factory()->create();
        $reseau = Reseau::factory()->create(["etat" => "actif"]);
        $response = $this->actingAs($admin, 'admin')->delete("/api/reseaus/$reseau->id");
        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "reseau" => $response->json('reseau')
            ]);
    }

    public function testReseauDelete()
    {
        $this->artisan('migrate:fresh');
        $reseau = Reseau::factory()->create(["etat" => "corbeille"]);
        $admin = AdminSystem::factory()->create();
        $response = $this->actingAs($admin, 'admin')->patch("/api/reseaus/delete/{$reseau->id}");

        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "reseau" => $response->json('reseau')
            ]);
    }

    public function testReseauRestore()
    {
        $this->artisan('migrate:fresh');
        $admin = AdminSystem::factory()->create();
        $reseau = Reseau::factory()->create(["etat" => "corbeille"]);
        $response = $this->actingAs($admin, 'admin')->patch("/api/reseaus/restaurer/$reseau->id");

        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "reseau" => $response->json('reseau')
            ]);
    }

    public function testReseauDeleted()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory()->create(["etat" => "corbeille"]);
        $admin = AdminSystem::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get('/api/reseaus/deleted/all');

        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "reseaux" => $response->json('reseaux')
            ]);
    }

    public function testReseauEmptyTrash()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory()->create(["etat" => "corbeille"]);
        $admin = AdminSystem::factory()->create();
        $response = $this->actingAs($admin, 'admin')->post('/api/reseaus/empty-trash');
        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message')
            ]);
    }
}
