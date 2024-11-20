<?php

namespace App\Models;

use App\Models\User;
use App\Models\Coach;
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

    /**
     * (Optional) Relationship: Conversation belongs to a coach.
     * If you have a Coach model, define this relationship.
     */
    public function coach()
    {
        return $this->belongsTo(Coach::class, 'coach_type', 'type'); // Assuming 'type' in coaches table
    }
}
