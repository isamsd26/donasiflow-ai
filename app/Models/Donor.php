<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }
}
