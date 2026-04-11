<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}