<?php

namespace Tests\Unit\Models;

use App\Models\Item;
use App\Models\Scopes\PositionSortingScope;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_position_global_scope_is_applied()
    {
        $item = Item::factory()->create();

        $this->assertInstanceOf(
            PositionSortingScope::class,
            $item->getGlobalScope(PositionSortingScope::class)
        );
    }

    public function test_items_are_sorted_by_position()
    {
        $item1 = Item::factory()->create([ 'position' => 3 ]);
        $item2 = Item::factory()->create([ 'position' => 2 ]);
        $item3 = Item::factory()->create([ 'position' => 1 ]);

        $items = Item::all();
        $this->assertCount(3, $items);
        $this->assertSame($item3->id, $items->first()->id);
        $this->assertSame($item1->id, $items->last()->id);
    }

    public function test_item_has_initials_from_name()
    {
        $item1 = Item::factory()->create([ 'name' => 'Test Name' ]);
        $item2 = Item::factory()->create([ 'name' => 'name' ]);

        $this->assertSame('TN', $item1->initials);
        $this->assertSame('N', $item2->initials);
    }
}
