<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Pcategory extends Model
{
    protected $fillable = [
        'name',
        'language_id',
        'user_id',
        'status',
        'slug',
        'image',
        'is_feature',
        'tax',
        'indx'
    ];

    public function productInformation()
    {
        return $this->hasMany(ProductInformation::class, 'category_id', 'id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class,'language_id');
    }

    public function subcategories()
    {
        return $this->hasMany(PsubCategory::class, 'category_id');
    }
    public function subcategory()
    {
        return $this->hasOne(PsubCategory::class, 'category_id');
    }
}
