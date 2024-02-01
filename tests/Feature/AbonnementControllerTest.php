<?php

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Abonnement;
use App\Models\Reseau;
use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AbonnementControllerTest extends TestCase
{
    public function testIndex()
    {
        $this->artisan('migrate:fresh');
        $response = $this->get('/api/abonnements');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnements" => $response->json('abonnements')
        ]);
    }
    public function testMesabonnements()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        Abonnement::factory(3)->create();
        $response = $this->actingAs($user)->get('/api/mesabonnements');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnements" => $response->json('abonnements')
        ]);
    }

    public function testShow()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        $abonnement = Abonnement::factory()->create();
        $response = $this->get("/api/abonnements/$abonnement->id");
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnement" => $response->json('abonnement')
        ]);
    }

    public function testStore()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/abonnements', [
            "prix" => 10000,
            "type" => "luxe",
            "duree" => "annuel",
            "description" => "Descritption d'un abonnement annuel",
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnement" => $response->json('abonnement')
        ]);
    }

    public function testUpdate()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        $abonnement = Abonnement::factory()->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch("/api/abonnements/$abonnement->id", [
            "prix" => 10000,
            "type" => "luxe modifier",
            "duree" => "annuel modifié",
            "description" => "Descritption d'un abonnement annuel modifié",
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnement" => $response->json('abonnement')
        ]);
    }

    public function testDestroy()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $abonnement = Abonnement::factory()->create(["etat" => "actif"]);
        $response = $this->actingAs($user)->delete("/api/abonnements/$abonnement->id");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnement" => $response->json('abonnement')
        ]);
    }

    public function testDelete()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        $abonnement = Abonnement::factory()->create(["etat" => "corbeille"]);
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch("/api/abonnements/delete/{$abonnement->id}");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnement" => $response->json('abonnement')
        ]);
    }

    public function testRestore()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $abonnement = Abonnement::factory()->create(["etat" => "corbeille"]);
        $response = $this->actingAs($user)->patch("/api/abonnements/restaurer/$abonnement->id");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnement" => $response->json('abonnement')
        ]);
    }

    public function testDeleted()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Abonnement::factory(3)->create(["etat" => "corbeille"]);
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/abonnements/deleted/all');

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnements" => $response->json('abonnements')
        ]);
    }

    public function testEmptyTrash()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Abonnement::factory(3)->create(["etat" => "corbeille"]);
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/abonnements/empty-trash');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message')
        ]);
    }
}
