<?php

namespace App\Models;

use App\Models\Scopes\PositionSortingScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemGroup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'position',
        'active',
        'only_admin',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'only_admin' => 'boolean'
    ];

    /**
     * The default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'active' => true,
        'only_admin' => false,
    ];

    protected static function booted()
    {
        static::addGlobalScope(new PositionSortingScope);
    }

    /**
     * Query only groups that are active, has items and with active items
     */
    public static function activeGroupWithActiveItemsQuery()
    {
        return self::query()
            ->withWhereHas('items', function (\Illuminate\Contracts\Database\Eloquent\Builder $query) {
                $query->where('active', true);
            })
            ->where('active', true);
    }

    /**
     * Get a collection of all active groups with its active items
     *
     * @return \Illuminate\Database\Eloquent\Collection<self, \Illuminate\Database\Eloquent\Builder>
     */
    public static function getActiveGroupsWithItems()
    {
        return self::activeGroupWithActiveItemsQuery()
            ->where('only_admin', false)
            ->get();
    }

    /**
     * Get a collection of all active admin groups with its active items
     *
     * @return \Illuminate\Database\Eloquent\Collection<self, \Illuminate\Database\Eloquent\Builder>
     */
    public static function getActiveAdminGroupsWithItems()
    {
        return self::activeGroupWithActiveItemsQuery()
            ->where('only_admin', true)
            ->get();
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
