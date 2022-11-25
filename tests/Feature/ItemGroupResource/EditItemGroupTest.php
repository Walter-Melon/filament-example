<?php

namespace Tests\Feature\ItemGroupResource;

use App\Filament\Resources\ItemGroupResource;
use App\Filament\Resources\ItemGroupResource\Pages\EditItemGroup;
use App\Models\ItemGroup;
use Filament\Pages\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class EditItemGroupTest extends TestCase
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
        $this->get(ItemGroupResource::getUrl('edit', [
            'record' => ItemGroup::factory()->create(),
        ]))->assertSuccessful();
    }

    public function test_can_retrive_data()
    {
        $group = ItemGroup::factory()->create();

        Livewire::test(EditItemGroup::class, [
            'record' => $group->getKey(),
        ])
            ->assertFormSet([
                'name' => $group->name,
                'position' => $group->position,
                'active' => $group->active,
                'only_admin' => $group->only_admin,
            ]);
    }

    public function test_can_save()
    {
        $group = ItemGroup::factory()->create();
        $newData = ItemGroup::factory()->make();

        Livewire::test(EditItemGroup::class, [
            'record' => $group->getKey()
        ])
            ->fillForm([
                'name' => $newData->name,
                'position' => $newData->position,
                'active' => $newData->active,
                'only_admin' => $newData->only_admin,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $group = $group->refresh();
        $this->assertSame($newData->name, $group->name);
        $this->assertSame($newData->position, $group->position);
        $this->assertSame($newData->active, $group->active);
        $this->assertSame($newData->only_admin, $group->only_admin);
    }

    public function test_can_validate_input()
    {
        $group = ItemGroup::factory()->create();

        Livewire::test(EditItemGroup::class, [
            'record' => $group->getKey()
        ])
            ->fillForm([
                'name' => null
            ])
            ->call('save')
            ->assertHasFormErrors(['name' => 'required']);
    }

    public function test_can_delete()
    {
        $group = ItemGroup::factory()->create();

        Livewire::test(EditItemGroup::class, [
            'record' => $group->getKey()
        ])
            ->callPageAction(DeleteAction::class);

        $this->assertModelMissing($group);
    }
}
