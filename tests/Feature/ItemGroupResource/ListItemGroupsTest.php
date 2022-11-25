<?php

namespace Tests\Feature\ItemGroupResource;

use App\Filament\Resources\ItemGroupResource;
use App\Filament\Resources\ItemGroupResource\Pages\ListItemGroups;
use App\Models\ItemGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ListItemGroupsTest extends TestCase
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
        $this->get(ItemGroupResource::getUrl('index'))->assertSuccessful();
    }

    public function test_can_list_groups()
    {
        $groups = ItemGroup::factory()->count(10)->create();

        Livewire::test(ListItemGroups::class)
            ->assertCanSeeTableRecords($groups);
    }
}
