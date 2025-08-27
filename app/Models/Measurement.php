<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

    //public $timestamps = false;

    //TABLE
    public $table = 'measurements';

    //FILLABLE
    protected $fillable = [
        'name'
    ];

    //HIDDEN
    protected $hidden = [];

    //APPENDS
    protected $appends = [];

    //WITH
    protected $with = [];

    //CASTS
    protected $casts = [];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_measurements');
    }

    public function customerItemMeasurements()
    {
        return $this->hasMany(CustomerItemMeasurement::class);
    }

    //ATTRIBUTES
    //public function getExampleAttribute()
    //{
    //    return $data;
    //}

}
