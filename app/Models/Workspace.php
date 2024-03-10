<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        // Add other workspace attributes as needed
    ];

    /**
     * The users that belong to the workspace.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_workspace_link')
            ->withPivot(['join_date', 'role'])
            ->withTimestamps();
    }

    public function boards()
    {
        return $this->hasMany(Board::class);
    }
}
