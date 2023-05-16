<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'name', 'is_public', 'del_flag',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
    ];

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->where('del_flag', 0);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
