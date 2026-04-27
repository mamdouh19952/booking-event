<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends Model  implements HasMedia
{
        use InteractsWithMedia;

    //
    protected $fillable = [
        'title',
        'description',
        'location',
        'start_time',
        'end_time',
        'image',
        'available_seats',
        // 'user_id',
        'category_id'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
        public function bookings()
        {
            return $this->hasMany(Booking::class);
        }
        
}
