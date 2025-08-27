<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMeasurement extends Model
{
    use HasFactory;

    //public $timestamps = false;

    //TABLE
    public $table = 'item_measurements';

    //FILLABLE
    protected $fillable = [
        'item_id',
        'measurement_id'
    ];

    //HIDDEN
    protected $hidden = [];

    //APPENDS
    protected $appends = [];

    //WITH
    protected $with = [];

    //CASTS
    protected $casts = [];

    //RELATIONSHIPS
    public function item()
    {
        return $this->belongsTo(Item::class);
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
