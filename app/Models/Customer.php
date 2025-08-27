<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    //public $timestamps = false;

    //TABLE
    public $table = 'customers';

    //FILLABLE
    protected $fillable = [
        'name',
        'mobile',
        'alt_mobile',
        'image_path',
        'reference',
        'city'
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
