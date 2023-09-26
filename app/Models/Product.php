<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['product_code', 'oem_code', 'name', 'description', 'image', 'motor_model', 'stock_entry_date', 'category_id', 'brand_id', 'vehicle_type_id','image_url'];

    protected $appends = ['brand_name', 'category_name', 'vehicle_type_name'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function vehicle_type()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function getBrandNameAttribute()
    {
        return $this->brand->name;
    }

    public function getCategoryNameAttribute()
    {
        return $this->category->name;
    }

    public function getVehicleTypeNameAttribute()
    {
        return $this->vehicle_type->type;
    }
}
