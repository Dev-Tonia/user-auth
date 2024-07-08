<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Organisation extends Model
{
    use HasFactory, HasUuids;

    public function users()
    {
        return $this->belongsToMany(User::class, 'organisation_user', 'organisation_id', 'user_id');
    }
    protected $primaryKey = "orgId";

    protected $fillable = ['name', 'description'];
}
