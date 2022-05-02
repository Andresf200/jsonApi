<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'user_id' => 'integer',
    ];

    //public $resourceType = 'articles';

    public function getRouteKeyName()
    {
       return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeYear(Builder $query, $year)
    {
        $query->whereYear('created_at' ,$year);
    }

    public function scopeMonth(Builder $query, $month)
    {
        $query->whereMonth('created_at',$month);
    }

    /*Esto es parecido a un join*/
    public function scopeCategories(Builder $query, $categories)
    {
        $categorySlugs = explode(',',$categories);
        $query->whereHas('category',function($q) use ($categorySlugs){
//            $q->where('slug',$categories); para buscar por un solo dato
            $q->whereIn('slug',$categorySlugs);
        });
    }
}
