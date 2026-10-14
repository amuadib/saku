<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasName
{
    use HasUuids, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'authable_type',
        'authable_id',
        'magic_token_salt',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getMagicToken(): string
    {
        $payload = $this->magic_token_salt
            ? "magic-login:{$this->id}:{$this->username}:{$this->magic_token_salt}"
            : "magic-login:{$this->id}:{$this->username}";

        $secret = config('custom.api_akademik.token') ?: config('app.key');

        return substr(hash_hmac('sha256', $payload, $secret), 0, 16);
    }

    public function isValidMagicToken(string $token): bool
    {
        return hash_equals($this->getMagicToken(), $token);
    }

    public function regenerateMagicToken(): string
    {
        $this->forceFill([
            'magic_token_salt' => Str::random(16),
        ])->save();

        return $this->getMagicToken();
    }

    public function getMagicLoginUrl(?string $baseUrl = null): string
    {
        $identifier = $this->username ?: $this->id;
        $path = '/m/'.$identifier.'/'.$this->getMagicToken();

        return rtrim(config('custom.host_qr_magic_link'), '/').$path;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->username != '';
    }

    public function authable()
    {
        return $this->morphTo();
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->authable->foto && Storage::disk('public')->exists($this->authable->foto) ? url('/storage/'.$this->authable->foto) : env('AVATAR_PROVIDER', 'https://ui-avatars.com/api/?rounded=true&background=09090b&color=fbac1e&name=').urlencode($this->authable->nama);
    }

    public function getFilamentName(): string
    {
        return $this->authable->nama;
    }

    public function isAdmin(): bool
    {
        return $this->role_id === 1;
    }

    public function isKepala(): bool
    {
        return $this->role_id === 3;
    }

    public function isPetugas(): bool
    {
        return $this->role_id <= 4;
    }

    public function isTataUsaha(): bool
    {
        return in_array($this->role_id, [1, 4]);
    }

    public function canImpersonate()
    {
        return $this->role_id === 1;
    }
}
