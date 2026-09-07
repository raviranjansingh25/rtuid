<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightCategory extends Model
{
    use HasFactory;

    protected $appends = ['category_name'];

    public function getCategoryNameAttribute()
    {
        $cat = Category::find($this->category);

        if (!empty($cat)) {
            return $cat->title;
        } else {
            return 'N/A';
        }
    } 
}
    

