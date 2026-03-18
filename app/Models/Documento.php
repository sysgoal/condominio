<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $fillable = [
        'tipo', // 'regimento', 'convencao', 'ata'
        'titulo', 
        'conteudo_texto', // Campo crucial para a busca por termos nas atas
        'arquivo_path', 
        'data_documento'
    ];

    // Escopo para facilitar a busca de atas por palavras-chave
    public function scopeBuscarAtasPorTermo($query, $termo)
    {
        return $query->where('tipo', 'ata')
                     ->where(function($q) use ($termo) {
                         $q->where('titulo', 'LIKE', "%{$termo}%")
                           ->orWhere('conteudo_texto', 'LIKE', "%{$termo}%");
                     });
    }
}