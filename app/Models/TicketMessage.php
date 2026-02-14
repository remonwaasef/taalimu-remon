<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{
    use HasFactory, \App\Traits\IdentifyTenant;

    protected $fillable = [
        'tenant_id',
        'ticket_id',
        'user_id',
        'message',
        'attachment_path',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withoutGlobalScope(\App\Scopes\TenantScope::class);
    }
}
