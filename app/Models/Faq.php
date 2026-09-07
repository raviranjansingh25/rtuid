<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;
    
    protected $appends = ['category'];

    public function getCategoryAttribute(){
        $category = Category::find($this->faq_type);
        if ( $category!='') {
            return $category->title;
        } else {
            return '';
        }   
    }
}
