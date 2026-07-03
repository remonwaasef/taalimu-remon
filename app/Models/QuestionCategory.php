<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionCategory extends Model
{
    use \App\Traits\BelongsToTenant, HasFactory;

    protected $fillable = ['tenant_id', 'name', 'slug'];

    public function questions()
    {
        return $this->hasMany(Question::class, 'category_id');
    }
}
