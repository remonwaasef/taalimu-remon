<?php

namespace App\Events\Growth;

use App\Models\PublicProfile;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProfilePublished
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public PublicProfile $profile
    ) {}
}
