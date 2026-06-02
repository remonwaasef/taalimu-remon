<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RiskDetected implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $studentName;
    public $riskType;
    public $message;
    public $tenantId;

    /**
     * Create a new event instance.
     */
    public function __construct($studentName, $riskType, $message, $tenantId)
    {
        $this->studentName = $studentName;
        $this->riskType = $riskType;
        $this->message = $message;
        $this->tenantId = $tenantId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('center.' . $this->tenantId),
        ];
    }

    public function broadcastAs()
    {
        return 'risk.detected';
    }
}
