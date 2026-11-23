<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

#[Fillable(['name', 'email', 'password', 'username', 'bio', 'avatar_initials', 'provinces_visited', 'is_admin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->is_admin;
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(Interest::class);
    }

    /** Total suara "membantu" yang diterima user */
    public function getHelpfulReceivedAttribute(): int
    {
        return (int) $this->reviews()->sum('helpful_count');
    }

    /** Level lencana berdasarkan jumlah review (Pemula Dolan → Legenda Dolan).
     *  Skala disesuaikan cakupan Semarang Raya: satu review per tempat
     *  (unique constraint), jadi ambangnya realistis untuk puluhan tempat. */
    public function getBadgeAttribute(): array
    {
        $n = (int) $this->reviews()->count();
        return match (true) {
            $n >= 25  => ['name' => 'Legenda Dolan', 'level' => 7],
            $n >= 15  => ['name' => 'Jago Dolan', 'level' => 5],
            $n >= 5   => ['name' => 'Rutin Dolan', 'level' => 3],
            default   => ['name' => 'Pemula Dolan', 'level' => 1],
        };
    }
}
