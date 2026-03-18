<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Morador;
use App\Models\Conta;
use App\Models\Documento;
use App\Models\Interacao;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class FakeDataSeeder extends Seeder
{
    public function run(): void
    {
        // Iniciamos o Faker traduzido para o Brasil
        $faker = Faker::create('pt_BR');
        
        $this->command->info('Iniciando a Fábrica de Dados do Condomínio... 🏗️');

        // 1. CRIAR 50 MORADORES
        $this->command->info('1/4: Cadastrando 50 moradores fictícios...');
        $userIds = [];
        
        for ($i = 0; $i < 50; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('senha123'),
            ]);
            
            // Dá o papel de morador
            $user->assignRole('morador');

            // Salva os dados do apartamento
            Morador::create([
                'user_id' => $user->id,
                'bloco' => $faker->randomElement(['A', 'B', 'C', 'Torre Única']),
                'apartamento' => $faker->numberBetween(1, 15) . $faker->randomElement(['01', '02', '03', '04']),
                'telefone' => $faker->phoneNumber,
            ]);

            $userIds[] = $user->id; // Guardamos os IDs para usar no Mural depois
        }

        // 2. CRIAR 100 LANÇAMENTOS FINANCEIROS (CONTAS)
        $this->command->info('2/4: Gerando 100 lançamentos no financeiro...');
        for ($i = 0; $i < 100; $i++) {
            $tipo = $faker->randomElement(['receita', 'despesa']);
            
            // Sorteia a data primeiro (pode retornar uma data ou null)
            $dataPagamento = $faker->optional(0.8)->dateTimeBetween('-6 months', 'now');
            
            Conta::create([
                'tipo' => $tipo,
                'categoria' => $tipo == 'receita' ? 'Taxa Condominial' : $faker->randomElement(['Manutenção', 'Limpeza', 'Água', 'Energia Elétrica', 'Seguro', 'Jardinagem']),
                'descricao' => $faker->sentence(4),
                'valor' => $faker->randomFloat(2, 100, 4500),
                'data_vencimento' => $faker->dateTimeBetween('-6 months', '+2 months')->format('Y-m-d'),
                
                // A MÁGICA ESTÁ AQUI: Se tiver data, formata. Se for null, salva como null!
                'data_pagamento' => $dataPagamento ? $dataPagamento->format('Y-m-d') : null,
            ]);
        }

        // 3. CRIAR 20 ATAS E DOCUMENTOS
        $this->command->info('3/4: Redigindo 20 atas e documentos com textos longos...');
        for ($i = 0; $i < 20; $i++) {
            Documento::create([
                'tipo' => $faker->randomElement(['ata', 'regimento', 'convencao']),
                'titulo' => $faker->sentence(5),
                'conteudo_texto' => $faker->paragraphs(6, true), // Gera 6 parágrafos de texto (ótimo para testar a busca)
                'data_documento' => $faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
            ]);
        }

        // 4. CRIAR 30 POSTAGENS NO MURAL SOCIAL
        $this->command->info('4/4: Criando 30 discussões e classificados no mural...');
        for ($i = 0; $i < 30; $i++) {
            $tipo = $faker->randomElement(['discussao', 'venda', 'aviso']);
            
            Interacao::create([
                'user_id' => $faker->randomElement($userIds), // Pega um morador aleatório daqueles 50 que criamos
                'tipo' => $tipo,
                'titulo' => $faker->sentence(4),
                'conteudo' => $faker->paragraphs(2, true),
                'preco' => $tipo == 'venda' ? $faker->randomFloat(2, 50, 2000) : null,
                'status' => true,
            ]);
        }

        $this->command->info('🎉 Pronto! Fábrica de dados executada com sucesso.');
    }
}