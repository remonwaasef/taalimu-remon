<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant;
use App\Models\Subscription;

class Invoice extends Model
{
    use \App\Traits\IdentifyTenant;
}
