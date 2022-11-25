<?php

namespace Tests\Feature\ItemResource;

use App\Filament\Resources\ItemResource\Pages\ListItems;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ListItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_items()
    {
        $items = Item::factory()->count(10)->create();

        Livewire::test(ListItems::class)
            ->assertCanSeeTableRecords($items);
    }
}
