<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interacao extends Model
{
    protected $table = 'interacoes';

    protected $fillable = [
        'user_id', 'tipo', // 'discussao', 'venda', 'aviso'
        'titulo', 'conteudo', 'preco', 'status'
    ];

    public function autor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comentarios() {
        return $this->hasMany(Comentario::class)->latest();
    }

public function user()
{
    return $this->belongsTo(User::class);
}
}