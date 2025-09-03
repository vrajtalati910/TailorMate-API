<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerItem extends Model
{
    use HasFactory;

    //public $timestamps = false;

    //TABLE
    public $table = 'customer_items';

    //FILLABLE
    protected $fillable = ['customer_id', 'item_id'];

    //HIDDEN
    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at'
    ];

    //APPENDS
    protected $appends = [];

    //WITH
    protected $with = [];

    //CASTS
    protected $casts = [];

    //RELATIONSHIPS
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function measurementRecords()
    {
        return $this->hasMany(CustomerItemMeasurement::class)->with('measurement');
    }

    // Get style records with item style name
    public function styleRecords()
    {
        return $this->hasMany(CustomerItemStyle::class)->with('style');
    }

    public function measurements()
    {
        return $this->belongsToMany(
            Measurement::class,
            'customer_item_measurements',
            'customer_item_id',
            'measurement_id'
        )->withPivot('value');
    }

    public function styles()
    {
        return $this->belongsToMany(
            ItemStyle::class,
            'customer_item_styles',
            'customer_item_id',
            'item_style_id'
        );
    }

    //ATTRIBUTES
    //public function getExampleAttribute()
    //{
    //    return $data;
    //}

}
