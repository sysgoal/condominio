<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Morador;
use App\Notifications\BoasVindasMorador;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

#[Fillable(['name', 'email', 'password', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

/*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Retorna o relacionamento com o morador.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
/*******  67376aff-4e39-4e84-a2a6-eb34f3a7f2c8  *******/
    public function morador(){
        return $this->hasOne(Morador::class);
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
    ]);

    // Geramos uma senha aleatória para o morador
    $senhaTemporaria = Str::random(8);

    $morador = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($senhaTemporaria),
        'is_active' => true,
    ]);

    // Atribui a role de morador (Spatie)
    $morador->assignRole('morador');

    // DISPARA O E-MAIL
    $morador->notify(new BoasVindasMorador($senhaTemporaria));

    return redirect()->route('moradores.index')->with('success', 'Morador cadastrado e e-mail de boas-vindas enviado!');
}
}
