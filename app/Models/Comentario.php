<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $fillable = ['user_id', 'interacao_id', 'conteudo'];

public function user()
{
    return $this->belongsTo(User::class);
}
}
