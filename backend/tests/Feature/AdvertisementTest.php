<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Modules\Advertisements\Models\Advertisement;
use Modules\Advertisements\Models\Location;
use Modules\Courses\Models\Field;
use Modules\Courses\Models\Level;
use Modules\Users\Models\User;
use Tests\TestCase;

class AdvertisementTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $otherUser;
    protected Field $field;
    protected Level $level1;
    protected Level $level2;
    protected Location $location1;
    protected Location $location2;

    /**
     * Setup Passport client and test data before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:client', [
            '--personal' => true,
            '--name' => 'Test Personal Access Client',
            '--no-interaction' => true
        ]);

        // Create test users
        $this->user = User::factory()->create();
        $this->otherUser = User::factory()->create();

        // Create test field
        $this->field = Field::create(['name' => 'Matematyka']);

        // Create test levels
        $this->level1 = Level::create(['name' => 'Podstawowy']);
        $this->level2 = Level::create(['name' => 'Średni']);

        // Create test locations
        $this->location1 = Location::create(['name' => 'Online']);
        $this->location2 = Location::create(['name' => 'Stacjonarnie']);
    }

    /**
     * Test listing advertisements returns 200.
     *
     * @test
     */
    public function it_returns_list_of_advertisements()
    {
        Advertisement::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
        ]);

        $response = $this->getJson('/api/advertisements');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'category',
                             'tutor_name',
                             'price',
                             'description',
                             'address',
                             'rating',
                             'rating_count',
                             'levels',
                             'formats',
                             'tutor',
                         ]
                     ],
                     'current_page',
                     'last_page',
                     'per_page',
                     'total',
                 ]);
    }

    /**
     * Test filtering advertisements by field_id.
     *
     * @test
     */
    public function it_filters_advertisements_by_field_id()
    {
        $field2 = Field::create(['name' => 'Fizyka']);

        Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
        ]);

        Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $field2->id,
        ]);

        $response = $this->getJson("/api/advertisements?field_id={$this->field->id}");

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.category', 'Matematyka');
    }

    /**
     * Test filtering advertisements by price_min and price_max.
     *
     * @test
     */
    public function it_filters_advertisements_by_price_range()
    {
        Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
            'price' => 100.00,
        ]);

        Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
            'price' => 200.00,
        ]);

        Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
            'price' => 300.00,
        ]);

        $response = $this->getJson('/api/advertisements?price_min=150&price_max=250');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.price', '200.00');
    }

    /**
     * Test filtering advertisements by level_id.
     *
     * @test
     */
    public function it_filters_advertisements_by_level_id()
    {
        $ad1 = Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
        ]);

        $ad2 = Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
        ]);

        $ad1->levels()->attach($this->level1->id);
        $ad2->levels()->attach($this->level2->id);

        $response = $this->getJson("/api/advertisements?level_id={$this->level1->id}");

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $ad1->id);
    }

    /**
     * Test filtering advertisements by location_id.
     *
     * @test
     */
    public function it_filters_advertisements_by_location_id()
    {
        $ad1 = Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
        ]);

        $ad2 = Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
        ]);

        $ad1->locations()->attach($this->location1->id);
        $ad2->locations()->attach($this->location2->id);

        $response = $this->getJson("/api/advertisements?location_id={$this->location1->id}");

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $ad1->id);
    }

    /**
     * Test pagination works correctly.
     *
     * @test
     */
    public function it_paginates_advertisements()
    {
        Advertisement::factory()->count(25)->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
        ]);

        $response = $this->getJson('/api/advertisements?per_page=10');

        $response->assertStatus(200)
                 ->assertJsonPath('per_page', 10)
                 ->assertJsonPath('total', 25)
                 ->assertJsonPath('last_page', 3)
                 ->assertJsonCount(10, 'data');
    }

    /**
     * Test creating advertisement requires authentication (401).
     *
     * @test
     */
    public function it_returns_401_when_creating_advertisement_without_auth()
    {
        $payload = [
            'price' => 150.00,
            'description' => 'Test description',
            'field_id' => $this->field->id,
            'address' => 'Test address',
        ];

        $response = $this->postJson('/api/advertisements', $payload);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

    /**
     * Test creating advertisement with valid data returns 201.
     *
     * @test
     */
    public function it_creates_advertisement_successfully()
    {
        $payload = [
            'price' => 150.00,
            'description' => 'Oferuję korepetycje z matematyki',
            'field_id' => $this->field->id,
            'address' => 'Rzeszów, ul. Testowa 123',
            'level_ids' => [$this->level1->id, $this->level2->id],
            'location_ids' => [$this->location1->id],
        ];

        $response = $this->actingAs($this->user, 'api')
                         ->postJson('/api/advertisements', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Ogłoszenie zostało utworzone pomyślnie.',
                 ])
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'category',
                         'price',
                         'description',
                         'address',
                         'levels',
                         'formats',
                     ]
                 ]);

        $this->assertDatabaseHas('advertisement__advertisements', [
            'user_id' => $this->user->id,
            'price' => '150.00',
            'description' => 'Oferuję korepetycje z matematyki',
            'field_id' => $this->field->id,
        ]);

        $ad = Advertisement::latest()->first();
        $this->assertEquals(2, $ad->levels()->count());
        $this->assertEquals(1, $ad->locations()->count());
    }

    /**
     * Test creating advertisement with user_id in payload returns 422.
     *
     * @test
     */
    public function it_rejects_user_id_in_create_payload()
    {
        $payload = [
            'user_id' => $this->otherUser->id,
            'price' => 150.00,
            'description' => 'Test description',
            'field_id' => $this->field->id,
            'address' => 'Test address',
        ];

        $response = $this->actingAs($this->user, 'api')
                         ->postJson('/api/advertisements', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }

    /**
     * Test validation errors for required fields (422).
     *
     * @test
     */
    public function it_returns_422_for_missing_required_fields()
    {
        $response = $this->actingAs($this->user, 'api')
                         ->postJson('/api/advertisements', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['price', 'description', 'field_id', 'address']);
    }

    /**
     * Test validation error for invalid field_id (422).
     *
     * @test
     */
    public function it_returns_422_for_invalid_field_id()
    {
        $payload = [
            'price' => 150.00,
            'description' => 'Test description',
            'field_id' => 99999,
            'address' => 'Test address',
        ];

        $response = $this->actingAs($this->user, 'api')
                         ->postJson('/api/advertisements', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['field_id']);
    }

    /**
     * Test validation error for negative price (422).
     *
     * @test
     */
    public function it_returns_422_for_negative_price()
    {
        $payload = [
            'price' => -10,
            'description' => 'Test description',
            'field_id' => $this->field->id,
            'address' => 'Test address',
        ];

        $response = $this->actingAs($this->user, 'api')
                         ->postJson('/api/advertisements', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['price']);
    }

    /**
     * Test showing single advertisement returns 200.
     *
     * @test
     */
    public function it_shows_single_advertisement()
    {
        $ad = Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
        ]);

        $response = $this->getJson("/api/advertisements/{$ad->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'category',
                         'price',
                         'description',
                         'address',
                     ]
                 ])
                 ->assertJsonPath('data.id', $ad->id);
    }

    /**
     * Test showing non-existent advertisement returns 404.
     *
     * @test
     */
    public function it_returns_404_for_nonexistent_advertisement()
    {
        $response = $this->getJson('/api/advertisements/99999');

        $response->assertStatus(404);
    }

    /**
     * Test updating advertisement requires authentication (401).
     *
     * @test
     */
    public function it_returns_401_when_updating_advertisement_without_auth()
    {
        $ad = Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
        ]);

        $response = $this->putJson("/api/advertisements/{$ad->id}", [
            'price' => 200.00,
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test updating own advertisement returns 200.
     *
     * @test
     */
    public function it_updates_own_advertisement_successfully()
    {
        $ad = Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
            'price' => 100.00,
        ]);

        $payload = [
            'price' => 200.00,
            'description' => 'Zaktualizowany opis',
        ];

        $response = $this->actingAs($this->user, 'api')
                         ->putJson("/api/advertisements/{$ad->id}", $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Ogłoszenie zostało zaktualizowane pomyślnie.',
                 ])
                 ->assertJsonPath('data.price', '200.00')
                 ->assertJsonPath('data.description', 'Zaktualizowany opis');

        $this->assertDatabaseHas('advertisement__advertisements', [
            'id' => $ad->id,
            'price' => '200.00',
            'description' => 'Zaktualizowany opis',
        ]);
    }

    /**
     * Test updating other user's advertisement returns 403.
     *
     * @test
     */
    public function it_returns_403_when_updating_other_users_advertisement()
    {
        $ad = Advertisement::factory()->create([
            'user_id' => $this->otherUser->id,
            'field_id' => $this->field->id,
        ]);

        $response = $this->actingAs($this->user, 'api')
                         ->putJson("/api/advertisements/{$ad->id}", [
                             'price' => 200.00,
                         ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'message' => 'Brak uprawnień do edycji tego ogłoszenia.',
                 ]);
    }

    /**
     * Test updating advertisement with user_id in payload returns 422.
     *
     * @test
     */
    public function it_rejects_user_id_in_update_payload()
    {
        $ad = Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
        ]);

        $response = $this->actingAs($this->user, 'api')
                         ->putJson("/api/advertisements/{$ad->id}", [
                             'user_id' => $this->otherUser->id,
                             'price' => 200.00,
                         ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }

    /**
     * Test updating advertisement levels and locations.
     *
     * @test
     */
    public function it_updates_advertisement_levels_and_locations()
    {
        $ad = Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
        ]);

        $ad->levels()->attach($this->level1->id);
        $ad->locations()->attach($this->location1->id);

        $response = $this->actingAs($this->user, 'api')
                         ->putJson("/api/advertisements/{$ad->id}", [
                             'level_ids' => [$this->level2->id],
                             'location_ids' => [$this->location2->id],
                         ]);

        $response->assertStatus(200);

        $ad->refresh();
        $this->assertEquals(1, $ad->levels()->count());
        $this->assertEquals($this->level2->id, $ad->levels()->first()->id);
        $this->assertEquals(1, $ad->locations()->count());
        $this->assertEquals($this->location2->id, $ad->locations()->first()->id);
    }

    /**
     * Test deleting advertisement requires authentication (401).
     *
     * @test
     */
    public function it_returns_401_when_deleting_advertisement_without_auth()
    {
        $ad = Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
        ]);

        $response = $this->deleteJson("/api/advertisements/{$ad->id}");

        $response->assertStatus(401);
    }

    /**
     * Test deleting own advertisement returns 200.
     *
     * @test
     */
    public function it_deletes_own_advertisement_successfully()
    {
        $ad = Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
        ]);

        $response = $this->actingAs($this->user, 'api')
                         ->deleteJson("/api/advertisements/{$ad->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Ogłoszenie zostało usunięte pomyślnie.',
                 ]);

        $this->assertDatabaseMissing('advertisement__advertisements', [
            'id' => $ad->id,
        ]);
    }

    /**
     * Test deleting other user's advertisement returns 403.
     *
     * @test
     */
    public function it_returns_403_when_deleting_other_users_advertisement()
    {
        $ad = Advertisement::factory()->create([
            'user_id' => $this->otherUser->id,
            'field_id' => $this->field->id,
        ]);

        $response = $this->actingAs($this->user, 'api')
                         ->deleteJson("/api/advertisements/{$ad->id}");

        $response->assertStatus(403)
                 ->assertJson([
                     'message' => 'Brak uprawnień do usunięcia tego ogłoszenia.',
                 ]);

        $this->assertDatabaseHas('advertisement__advertisements', [
            'id' => $ad->id,
        ]);
    }

    /**
     * Test sorting advertisements by price ascending.
     *
     * @test
     */
    public function it_sorts_advertisements_by_price_ascending()
    {
        Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
            'price' => 300.00,
        ]);

        Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
            'price' => 100.00,
        ]);

        Advertisement::factory()->create([
            'user_id' => $this->user->id,
            'field_id' => $this->field->id,
            'price' => 200.00,
        ]);

        $response = $this->getJson('/api/advertisements?sort_by=price&sort_order=asc');

        $response->assertStatus(200);
        $prices = collect($response->json('data'))->pluck('price')->map(fn($p) => (float)$p)->toArray();
        $this->assertEquals([100.00, 200.00, 300.00], $prices);
    }
}
