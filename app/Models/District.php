<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function ulbs(): HasMany
    {
        return $this->hasMany(Ulb::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
