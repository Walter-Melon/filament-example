<?php

namespace Tests\Feature\ItemResource;

use App\Filament\Resources\ItemResource\Pages\EditItem;
use App\Models\Item;
use App\Models\ItemGroup;
use Filament\Pages\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class EditItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_retrive_data()
    {
        $item = Item::factory()->create();

        Livewire::test(EditItem::class, [
            'record' => $item->getKey(),
        ])
            ->assertFormSet([
                'name' => $item->name,
                'position' => $item->position,
                'active' => $item->active,
                'url' => $item->url,
                'new_tab' => $item->new_tab,
            ]);
    }

    public function test_can_save()
    {
        $item = Item::factory()->create();
        $newData = Item::factory()->make();

        Livewire::test(EditItem::class, [
            'record' => $item->getKey()
        ])
            ->fillForm([
                'name' => $newData->name,
                'position' => $newData->position,
                'active' => $newData->active,
                'url' => $newData->url,
                'new_tab' => $newData->new_tab,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $item = $item->refresh();
        $this->assertSame($newData->name, $item->name);
        $this->assertSame($newData->position, $item->position);
        $this->assertSame($newData->active, $item->active);
        $this->assertSame($newData->url, $item->url);
        $this->assertSame($newData->new_tab, $item->new_tab);
    }

    public function test_can_edit_group()
    {
        $group1 = ItemGroup::factory()->create();
        $group2 = ItemGroup::factory()->create();
        $item = Item::factory()->create([
            'item_group_id' => $group1
        ]);

        $this->assertSame($group1->id, $item->item_group_id);

        Livewire::test(EditItem::class, [
            'record' => $item->getKey()
        ])
            ->fillForm([
                'item_group_id' => $group2->getKey(),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $item = $item->refresh();
        $this->assertEquals($group2->id, $item->item_group_id);
    }

    public function test_can_validate_input()
    {
        $item = Item::factory()->create();

        Livewire::test(EditItem::class, [
            'record' => $item->getKey()
        ])
            ->fillForm([
                'name' => null
            ])
            ->call('save')
            ->assertHasFormErrors(['name' => 'required']);
    }

    public function test_can_delete()
    {
        $item = Item::factory()->create();

        Livewire::test(EditItem::class, [
            'record' => $item->getKey()
        ])
            ->callPageAction(DeleteAction::class);

        $this->assertModelMissing($item);
    }
}
