<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class BackendUser extends Authenticatable implements FilamentUser, HasName
{
    use HasRoles;

    protected $table = 'backend_users';

    protected $fillable = [
        'first_name',
        'last_name',
        'login',
        'email',
        'password',
        'super_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getGuardName(): string
    {
        return 'admin';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function balance(): HasMany
    {
        return $this->hasMany(Balance::class);
    }
}
