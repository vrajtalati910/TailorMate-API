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
    protected $hidden = [];

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

    public function measurements()
    {
        return $this->hasMany(CustomerItemMeasurement::class);
    }

    public function styles()
    {
        return $this->hasMany(CustomerItemStyle::class);
    }

    //ATTRIBUTES
    //public function getExampleAttribute()
    //{
    //    return $data;
    //}

}
