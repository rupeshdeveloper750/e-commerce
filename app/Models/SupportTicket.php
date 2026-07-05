<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupportTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'email', 'subject', 'message',
        'priority', 'status', 'admin_reply', 'replied_at',
    ];

    protected function casts(): array
    {
        return [
            'replied_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
