<?php

namespace App\Models;

use App\Models\Scopes\PositionSortingScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
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
        'url',
        'active',
        'new_tab',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'new_tab' => 'boolean'
    ];

    /**
     * The default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'active' => true,
        'new_tab' => false,
    ];

    protected static function booted()
    {
        static::addGlobalScope(new PositionSortingScope);
    }

    public function group()
    {
        return $this->belongsTo(ItemGroup::class, 'item_group_id');
    }

    /**
     * Get the item initials.
     *
     * @return Attribute
     */
    protected function initials(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => preg_filter('/[^A-Z]/', '', ucfirst($attributes['name'])),
        );
    }
}
