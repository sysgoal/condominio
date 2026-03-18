<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquete extends Model
{
    protected $fillable = [
        'titulo', 'descricao', 'data_abertura', 'data_encerramento', 'status'
    ];

    public function opcoes()
    {
        return $this->hasMany(EnqueteOpcao::class);
    }

    public function votos()
    {
        return $this->hasManyThrough(Voto::class, EnqueteOpcao::class);
    }
}

class EnqueteOpcao extends Model
{
    protected $fillable = ['enquete_id', 'texto'];

    public function enquete()
    {
        return $this->belongsTo(Enquete::class);
    }

    public function votos()
    {
        return $this->hasMany(Voto::class);
    }
}

class Voto extends Model
{
    protected $fillable = ['user_id', 'enquete_opcao_id'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function opcao()
    {
        return $this->belongsTo(EnqueteOpcao::class, 'enquete_opcao_id');
    }
}