<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        "user_id",
        "title",
        "category",
        "description",
        "location",
        "status",
        "proof",
        "date_followed_up",
        "information",
        "result"
    ];

    /**
     * Get the user that owns the Complaint
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
