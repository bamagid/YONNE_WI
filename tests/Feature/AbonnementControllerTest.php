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
    public function testAbonnementIndex()
    {
        $this->artisan('migrate:fresh');
        $response = $this->get('/api/abonnements');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnements" => $response->json('abonnements')
        ]);
    }

    public function testAbonnementSubscribe()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory()->create();
        $abonnement = Abonnement::factory()->create();
        $response = $this->get('/api/abonnements/subscribe/1');
        $numeroWhatsApp = $abonnement->reseau->telephone;
        $response->assertRedirect("https://api.whatsapp.com/send?phone=$numeroWhatsApp");
    }
    public function testMesAbonnements()
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

    public function testAbonnementShow()
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

    public function testAbonnementStore()
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

    public function testAbonnementUpdate()
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

    public function testAbonnementDestroy()
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

    public function testAbonnementDelete()
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

    public function testAbonnementRestore()
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

    public function testAbonnementDeleted()
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

    public function testAbonnementEmptyTrash()
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
