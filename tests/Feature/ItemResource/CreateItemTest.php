<?php

namespace Tests\Feature\ItemResource;

use App\Filament\Resources\ItemResource\Pages\CreateItem;
use App\Models\Item;
use App\Models\ItemGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CreateItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_item()
    {
        $newData = Item::factory()->make();

        Livewire::test(CreateItem::class)
            ->fillForm([
                'name' => $newData->name,
                'position' => $newData->position,
                'active' => $newData->active,
                'url' => $newData->url,
                'new_tab' => $newData->new_tab,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Item::class, [
            'name' => $newData->name,
            'position' => $newData->position,
            'active' => $newData->active,
            'url' => $newData->url,
            'new_tab' => $newData->new_tab,
        ]);
    }

    public function test_can_create_group_relation()
    {
        $group = ItemGroup::factory()->create();
        $newData = Item::factory()->make();

        Livewire::test(CreateItem::class)
            ->fillForm([
                'item_group_id' => $group->getKey(),
                'name' => $newData->name,
                'position' => $newData->position,
                'active' => $newData->active,
                'url' => $newData->url,
                'new_tab' => $newData->new_tab,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        /** @var Item */
        $item = Item::with('group')->first();
        $this->assertEquals($group->id, $item->group->id);
    }

    public function test_can_validate_input()
    {
        Livewire::test(CreateItem::class)
            ->fillForm([
                'name' => null
            ])
            ->call('create')
            ->assertHasFormErrors(['name' => 'required']);
    }
}
