<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\BoasVindasMorador;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class Morador extends Model
{
    protected $table = 'moradores';
    
    protected $fillable = [
        'user_id', 
        'bloco', 
        'apartamento', 
        'telefone', 
        'data_nascimento'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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