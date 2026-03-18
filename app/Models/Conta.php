<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    use HasFactory;

    // A tabela no banco de dados que este model representa
    protected $table = 'contas';

    // Os campos que podemos preencher em massa (segurança do Laravel)
    protected $fillable = [
        'tipo', 
        'categoria', 
        'descricao', 
        'valor', 
        'data_vencimento', 
        'data_pagamento', 
        'comprovante_path'
    ];
}