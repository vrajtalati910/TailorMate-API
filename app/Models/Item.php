<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    //public $timestamps = false;

    //TABLE
    public $table = 'items';

    //FILLABLE
    protected $fillable = [
        'name'
    ];

    //HIDDEN
    protected $hidden = [
        'pivot'
    ];

    //APPENDS
    protected $appends = [];

    //WITH
    protected $with = [];

    //CASTS
    protected $casts = [];

    // RELATIONSHIPS
    public function measurements()
    {
        return $this->belongsToMany(
            Measurement::class,
            'item_measurements',
            'item_id',
            'measurement_id'
        );
    }

    public function styles()
    {
        return $this->hasMany(ItemStyle::class);
    }

    public function customerItems()
    {
        return $this->hasMany(CustomerItem::class);
    }
    //ATTRIBUTES
    //public function getExampleAttribute()
    //{
    //    return $data;
    //}

}
