<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerItemMeasurement extends Model
{
    use HasFactory;

    //public $timestamps = false;

    //TABLE
    public $table = 'customer_item_measurements';

    //FILLABLE
    protected $fillable = ['customer_item_id', 'measurement_id', 'value'];


    //HIDDEN
    protected $hidden = [];

    //APPENDS
    protected $appends = [];

    //WITH
    protected $with = [];

    //CASTS
    protected $casts = [];

    //RELATIONSHIPS
    public function customerItem()
    {
        return $this->belongsTo(CustomerItem::class);
    }

    public function measurement()
    {
        return $this->belongsTo(Measurement::class);
    }

    //ATTRIBUTES
    //public function getExampleAttribute()
    //{
    //    return $data;
    //}

}
