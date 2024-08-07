<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'img'
    ];

    // Accessor for name
    public function getNameAttribute($value)
    {
        return ucfirst(strtolower($value));
    }

    // Accessor for description
    public function getDescriptionAttribute($value)
    {
        return ucfirst($value);
    }

    // Mutator for name
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst(strtolower($value));
    }

    // Mutator for description
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = ucfirst($value);
    }

    // Accessor for image default value
    public function getImgAttribute($value)
    {
        return $value ?: 'item_default.png';
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
