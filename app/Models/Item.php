<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'category_id', 'lesson_id', 'text_source', 'text_destination', 'audio_path', 'audio_name', 'del_flag', 'is_crawl'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
    ];

    public function Lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
