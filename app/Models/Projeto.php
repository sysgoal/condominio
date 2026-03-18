<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projeto extends Model
{
    protected $fillable = [
        'titulo', 'descricao', 'status', 'data_inicio', 'data_conclusao', 'orcamento'
    ];
}

class Parceiro extends Model
{
    protected $fillable = [
        'nome_fantasia', 'razao_social', 'cnpj', 'telefone', 'email', 'servico_prestado'
    ];
}

class Conta extends Model
{
    // Representa receitas e despesas para o saldo em caixa
    protected $fillable = [
        'tipo', // 'receita' ou 'despesa'
        'categoria', 
        'descricao', 
        'valor', 
        'data_vencimento', 
        'data_pagamento', 
        'comprovante_path'
    ];
}