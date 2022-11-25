<?php

namespace Tests\Feature\ItemGroupResource;

use App\Filament\Resources\ItemGroupResource;
use App\Filament\Resources\ItemGroupResource\Pages\CreateItemGroup;
use App\Models\ItemGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CreateItemGroupTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var \App\Models\User */
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
    }

    public function test_can_render_page()
    {
        $this->get(ItemGroupResource::getUrl('create'))->assertSuccessful();
    }

    public function test_can_create_group()
    {
        $newData = ItemGroup::factory()->make();

        Livewire::test(CreateItemGroup::class)
            ->fillForm([
                'name' => $newData->name,
                'position' => $newData->position,
                'active' => $newData->active,
                'only_admin' => $newData->only_admin,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(ItemGroup::class, [
            'name' => $newData->name,
            'position' => $newData->position,
            'active' => $newData->active,
            'only_admin' => $newData->only_admin,
        ]);
    }

    public function test_can_validate_input()
    {
        Livewire::test(CreateItemGroup::class)
            ->fillForm([
                'name' => null,
            ])
            ->call('create')
            ->assertHasFormErrors(['name' => 'required']);
    }
}
