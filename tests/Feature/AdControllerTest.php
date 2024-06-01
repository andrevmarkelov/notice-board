<?php

namespace Tests\Feature;

use App\Models\Ad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function it_can_get_list_of_ads()
    {
        Ad::factory()->count(5)->create();

        $response = $this->getJson(route('ads.index.api'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'response' => [
                'ads' => [
                    '*' => ['id', 'title', 'price']
                ]
            ]
        ]);
    }

    #[Test]
    public function it_can_get_a_single_ad()
    {
        $ad = Ad::factory()->create();

        $response = $this->getJson(route('ad.show.api', $ad->id));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'response' => [
                'ad' => ['title', 'main_photo', 'price']
            ]
        ]);
    }

    #[Test]
    public function it_can_create_an_ad()
    {
        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'photos' => [
                $this->faker->url,
                $this->faker->url,
                $this->faker->url,
            ]
        ];

        $response = $this->postJson(route('ad.store.api'), $data);

        $response->assertStatus(201);
        $response->assertJson([
            'status' => 'success',
            'response' => [
                'ads' => $response->json('response.ads')
            ]
        ]);

        $this->assertDatabaseHas('ads', [
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => $data['price']
        ]);
    }

    #[Test]
    public function it_validates_required_fields_when_creating_an_ad()
    {
        $response = $this->postJson(route('ad.store.api'));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'description', 'price', 'photos']);
    }
}
