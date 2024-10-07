<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $fillable = ['name'];

    public function personalRecords(): hasMany
    {
        return $this->hasMany(PersonalRecord::class);
    }
}
