<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConversationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coach_type',
        'user_message',
        'ai_response',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
