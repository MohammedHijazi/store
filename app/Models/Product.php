<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;


    const STATUS_ACTIVE = 'active';
    const STATUS_DRAFT  = 'draft';

    protected $fillable = [
        'name', 'slug', 'description', 'image_path', 'price', 'sale_price',
        'quantity', 'weight', 'width', 'height', 'length', 'status',
        'category_id',
    ];

    protected $casts = [
        'price' => 'float',
        'quantity' => 'int',
    ];

    protected $appends = [
        'image_url',
        'formatted_price',
        'permalink',
    ];

    protected $perPage = 20;

    protected static function booted()
    {
        //static::addGlobalScope(new ActiveStatusScope());

        /*static::addGlobalScope('owner', function(Builder $builder) {
            $user = Auth::user();
            if ($user && $user->type == 'store') {
                $builder->where('products.user_id', '=', $user->id);
            }
        });*/
    }

    public function scopeActive(Builder $builder)
    {
        $builder->where('status', '=', 'active');
    }

    public function scopePrice(Builder $builder, $from, $to = null)
    {
        $builder->where('price', '>=', $from);
        if ($to !== null) {
            $builder->where('price', '<=', $to);
        }
    }

    public static function validateRules()
    {
        return [
            'name' => 'required|max:255',
            'category_id' => 'required|int|exists:categories,id',
            'description' => 'nullable',
            'image' => 'nullable|image|dimensions:min_width=300,min_height=300',
            'price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|int|min:0',
            'sku' => 'nullable|unique:products,sku',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'status' => 'in:' . self::STATUS_ACTIVE . ',' . self::STATUS_DRAFT,
        ];
    }

    // image_url
    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return asset('images/placeholder.png');
        }

        if (stripos($this->image_path, 'http') === 0) {
            return $this->image_path;
        }

        return asset('uploads/' . $this->image_path);
    }



    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')
            ->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->withDefault();
    }


}
