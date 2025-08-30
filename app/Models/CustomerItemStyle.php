<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerItemStyle extends Model
{
    use HasFactory;

    //public $timestamps = false;

    //TABLE
    public $table = 'customer_item_styles';

    //FILLABLE
    protected $fillable = ['customer_item_id', 'item_style_id'];

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

    public function style()
    {
        return $this->belongsTo(ItemStyle::class, 'item_style_id');
    }

    //ATTRIBUTES
    //public function getExampleAttribute()
    //{
    //    return $data;
    //}

}
