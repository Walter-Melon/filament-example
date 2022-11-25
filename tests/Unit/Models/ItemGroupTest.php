<?php

namespace Tests\Unit\Models;

use App\Models\Item;
use App\Models\ItemGroup;
use App\Models\Scopes\PositionSortingScope;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemGroupTest extends TestCase
{
    use RefreshDatabase;

    public function test_position_global_scope_is_applied()
    {
        $item = ItemGroup::factory()->create();

        $this->assertInstanceOf(
            PositionSortingScope::class,
            $item->getGlobalScope(PositionSortingScope::class)
        );
    }

    public function test_groups_are_sorted_by_position()
    {
        $group1 = ItemGroup::factory()->create(['position' => 3]);
        $group2 = ItemGroup::factory()->create(['position' => 2]);
        $group3 = ItemGroup::factory()->create(['position' => 1]);

        $groups = ItemGroup::all();
        $this->assertCount(3, $groups);
        $this->assertSame($group3->id, $groups->first()->id);
        $this->assertSame($group1->id, $groups->last()->id);
    }

    public function test_only_returns_active_admin_groups_that_has_active_items()
    {
        $this->createItemGroupCases();

        $groupCount = ItemGroup::query()
            ->where('active', true)
            ->where('only_admin', true)
            ->whereRelation('items', 'active', true)
            ->count();

        $itemGroups = ItemGroup::getActiveAdminGroupsWithItems();

        $this->assertCount($groupCount, $itemGroups);

        $itemsCount = Item::query()
            ->where('item_group_id', $itemGroups->first()->id)
            ->where('active', true)
            ->whereRelation('group', 'active', true)
            ->count();

        $this->assertCount($itemsCount, $itemGroups->first()->items);
    }

    public function test_only_returns_active_groups_that_has_items()
    {
        $this->createItemGroupCases();

        $groupCount = ItemGroup::query()
            ->where('active', true)
            ->where('only_admin', false)
            ->whereRelation('items', 'active', true)
            ->count();

        $itemGroups = ItemGroup::getActiveGroupsWithItems();

        $this->assertCount($groupCount, $itemGroups);

        $firstGroup = $itemGroups->first();
        $itemsCount = Item::query()
            ->where('item_group_id', $firstGroup->id)
            ->where('active', true)
            ->whereRelation('group', 'active', true)
            ->count();

        $this->assertCount($itemsCount, $firstGroup->items);
    }

    private function createItemGroupCases()
    {
        $cases = [
            // [group.active, group.admin, item.active]
            [true, true, true],
            [true, true, false],
            [true, false, false],

            [false, false, false],
            [false, false, true],
            [false, true, true],

            [true, false, true],
            [false, true, false],
        ];

        foreach ($cases as $case) {
            $groupActive = $case[0];
            $groupAdmin = $case[1];
            $itemActive = $case[2];

            $groupFactory = ItemGroup::factory()
                ->count(1)
                ->has(Item::factory(2)->state([
                    'active' => $itemActive
                ]));
            if ($itemActive) {
                $groupFactory->has(Item::factory(2)->state([
                    'active' => !$itemActive
                ]));
            }

            $groupFactory->create([
                'active' => $groupActive,
                'only_admin' => $groupAdmin
            ]);
        }

        $this->assertCount(count($cases), ItemGroup::all());
    }
}
