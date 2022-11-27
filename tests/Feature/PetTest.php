<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PetTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @return void
     */
    public function test_service_pets_index(): void
    {
        Pet::factory()
            ->count(100)
            ->create();

        $this->actingAs(User::factory()->create())
            ->json('GET', route('pets.index'))
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has(
                    'data',
                    fn (AssertableJson $json) =>
                    $json->has(config('pagination.per_page'))
                        ->each(fn (AssertableJson $json) => $json
                            ->has('id')
                            ->has('avatar')
                            ->has('type')
                            ->has('description')
                            ->has('created_at')
                            ->has('updated_at')
                            ->etc()
                        )
                )
                    ->etc()
            );
    }

    /**
     * @return void
     */
    public function test_service_pets_store(): void
    {
        Storage::fake('avatars');

        $avatar = UploadedFile::fake()
            ->image('avatar.jpg');

        $this->actingAs(User::factory()->create())
            ->json('POST', route('pets.store'), [
                'avatar' => $avatar,
                'type' => 'dog',
                'name' => 'Doggy',
            ])
            ->assertCreated()
            ->assertJson(
                fn(AssertableJson $json) =>
                $json
                    ->has('id')
                    ->has('avatar')
                    ->has('type')
                    ->has('description')
                    ->has('created_at')
                    ->has('updated_at')
                    ->etc()
            );

        $this->assertDatabaseHas((new Pet)->getTable(), [
            'type' => 'dog',
            'name' => 'Doggy',
        ]);
    }

    /**
     * @return void
     */
    public function test_service_pets_show(): void
    {
        $pet = Pet::factory()
            ->create();

        $this->actingAs(User::factory()->create())
            ->json('GET', route('pets.show', $pet))
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) =>
                $json
                    ->has('id')
                    ->has('avatar')
                    ->has('type')
                    ->has('description')
                    ->has('created_at')
                    ->has('updated_at')
                    ->etc()
            );
    }

    /**
     * @return void
     */
    public function test_service_pets_update(): void
    {
        $pet = Pet::factory()
            ->create([
                'type' => 'cat',
                'name' => 'Catty',
            ]);

        Storage::fake('avatars');

        $avatar = UploadedFile::fake()
            ->image('avatar.jpg');

        $this->actingAs(User::factory()->create())
            ->json('PATCH', route('pets.update', $pet), [
                'avatar' => $avatar,
                'type' => 'dog',
                'name' => 'Doge',
            ])
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) =>
                $json
                    ->has('id')
                    ->has('avatar')
                    ->has('name')
                    ->where('name', 'Doge')
                    ->has('type')
                    ->where('type', 'dog')
                    ->has('description')
                    ->has('created_at')
                    ->has('updated_at')
                    ->etc()
            );

        $this->assertDatabaseMissing($pet->getTable(), [
            'id' => $pet->id,
            'name' => 'Catty',
            'type' => 'cat',
        ]);

        $this->assertDatabaseHas($pet->getTable(), [
            'id' => $pet->id,
            'name' => 'Doge',
            'type' => 'dog',
        ]);
    }

    /**
     * @return void
     */
    public function test_service_pets_destroy(): void
    {
        $pet = Pet::factory()
            ->create();

        $this->actingAs(User::factory()->create())
            ->json('DELETE', route('pets.destroy', $pet))
            ->assertOk();

        $this->assertDatabaseMissing($pet->getTable(), [
           'id' => $pet->id,
        ]);
    }
}
