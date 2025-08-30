<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemStyle extends Model
{
    use HasFactory;

    //public $timestamps = false;

    //TABLE
    public $table = 'item_styles';

    //FILLABLE
    protected $fillable = ['item_id', 'name'];

    //HIDDEN
    protected $hidden = [
        'created_at',
        'updated_at',
        'item_id',
        'pivot',
    ];

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

    public function customerItemStyles()
    {
        return $this->hasMany(CustomerItemStyle::class);
    }
    //ATTRIBUTES
    //public function getExampleAttribute()
    //{
    //    return $data;
    //}

}
