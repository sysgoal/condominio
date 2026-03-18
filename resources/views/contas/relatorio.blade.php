@extends('layouts.app')

@section('title', 'Relatório Financeiro')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 pb-12">
    
    <div class="flex flex-col lg:flex-row items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100 print:hidden">
        <form action="{{ route('contas.relatorio') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            <div class="flex items-center bg-slate-50 border border-slate-200 rounded-lg px-3 py-1">
                <i class="fa-regular fa-calendar text-slate-400 mr-2"></i>
                <select name="mes" class="bg-transparent border-none text-sm font-bold text-slate-700 focus:ring-0 cursor-pointer">
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ $m }}" {{ (int)$mes == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="flex items-center bg-slate-50 border border-slate-200 rounded-lg px-3 py-1">
                <select name="ano" class="bg-transparent border-none text-sm font-bold text-slate-700 focus:ring-0 cursor-pointer">
                    @for($a=date('Y')-2; $a<=date('Y')+1; $a++)
                        <option value="{{ $a }}" {{ (int)$ano == $a ? 'selected' : '' }}>{{ $a }}</option>
                    @endfor
                </select>
            </div>

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-bold text-sm transition-all shadow-sm">
                Atualizar Relatório
            </button>
        </form>

        <button onclick="window.print()" class="inline-flex items-center bg-slate-800 hover:bg-black text-white px-6 py-2.5 rounded-lg font-bold text-sm transition-all shadow-md">
            <i class="fa-solid fa-print mr-2"></i> Imprimir / Salvar PDF
        </button>
    </div>

    <div id="print-area" class="bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-slate-100 print:shadow-none print:border-none print:p-0">
        
        <div class="flex flex-col md:flex-row justify-between items-start border-b-2 border-slate-100 pb-8 mb-8 gap-6">
            <div class="space-y-1">
                <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Demonstrativo Financeiro</h1>
                <p class="text-slate-500 font-bold flex items-center">
                    <i class="fa-solid fa-building-user mr-2 text-indigo-500"></i> Condomínio Residencial CondoAdmin
                </p>
                <div class="inline-block bg-indigo-600 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest mt-2">
                    Período: {{ \Carbon\Carbon::create()->month((int)$mes)->translatedFormat('F') }} de {{ $ano }}
                </div>
            </div>
            <div class="text-right flex flex-col items-end">
                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center border border-slate-100 mb-2">
                    <i class="fa-solid fa-chart-pie text-2xl text-slate-300"></i>
                </div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Documento Oficial do Sistema</p>
                <p class="text-[10px] text-slate-400">Gerado: {{ date('d/m/Y - H:i') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="p-6 bg-emerald-50 rounded-2xl border border-emerald-100 relative overflow-hidden">
                <i class="fa-solid fa-arrow-trend-up absolute -right-2 -bottom-2 text-emerald-100 text-6xl"></i>
                <p class="text-xs font-black text-emerald-600 uppercase mb-1 relative z-10">Receitas Totais</p>
                <p class="text-2xl font-black text-emerald-700 relative z-10">R$ {{ number_format($totalReceitas, 2, ',', '.') }}</p>
            </div>
            <div class="p-6 bg-rose-50 rounded-2xl border border-rose-100 relative overflow-hidden">
                <i class="fa-solid fa-arrow-trend-down absolute -right-2 -bottom-2 text-rose-100 text-6xl"></i>
                <p class="text-xs font-black text-rose-600 uppercase mb-1 relative z-10">Despesas Totais</p>
                <p class="text-2xl font-black text-rose-700 relative z-10">R$ {{ number_format($totalDespesas, 2, ',', '.') }}</p>
            </div>
            <div class="p-6 {{ $saldoMes >= 0 ? 'bg-indigo-50 border-indigo-100' : 'bg-amber-50 border-amber-100' }} rounded-2xl border relative overflow-hidden">
                <i class="fa-solid fa-scale-balanced absolute -right-2 -bottom-2 {{ $saldoMes >= 0 ? 'text-indigo-100' : 'text-amber-100' }} text-6xl"></i>
                <p class="text-xs font-black {{ $saldoMes >= 0 ? 'text-indigo-600' : 'text-amber-600' }} uppercase mb-1 relative z-10">Saldo do Período</p>
                <p class="text-2xl font-black {{ $saldoMes >= 0 ? 'text-indigo-700' : 'text-amber-700' }} relative z-10">R$ {{ number_format($saldoMes, 2, ',', '.') }}</p>
            </div>
        </div>

        @if(count($graficoLabels) > 0)
        <div class="mb-12 p-8 bg-slate-50/50 rounded-3xl border border-slate-100">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-lg font-black text-slate-800">Distribuição de Gastos</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Análise por Categoria</p>
                </div>
                <i class="fa-solid fa-chart-column text-slate-200 text-2xl"></i>
            </div>
            <div class="relative h-64">
                <canvas id="graficoDespesas"></canvas>
            </div>
        </div>
        @endif

        <div class="space-y-4">
            <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4 border-b pb-2">Detalhamento de Lançamentos</h3>
            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-50">
                        <th class="py-3 px-2">Data</th>
                        <th class="py-3 px-2">Descrição / Categoria</th>
                        <th class="py-3 px-2 text-right">Valor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($contas as $conta)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="py-4 px-2 text-xs font-bold text-slate-400">{{ \Carbon\Carbon::parse($conta->data_vencimento)->format('d/m') }}</td>
                            <td class="py-4 px-2">
                                <p class="text-sm font-bold text-slate-800 leading-tight">{{ $conta->descricao }}</p>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">{{ $conta->categoria }}</p>
                            </td>
                            <td class="py-4 px-2 text-right font-black text-sm {{ $conta->tipo == 'receita' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $conta->tipo == 'receita' ? '+' : '-' }} R$ {{ number_format($conta->valor, 2, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-10 text-center text-slate-400 italic text-sm">Nenhum lançamento registrado neste período.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-20 pt-10 border-t-2 border-slate-50 flex flex-col md:flex-row justify-between gap-12">
            <div class="flex-1 text-center">
                <div class="border-b border-slate-300 w-48 mx-auto mb-2"></div>
                <p class="text-xs font-black text-slate-800 uppercase tracking-widest">Síndico / Administração</p>
                <p class="text-[10px] text-slate-400">Responsável Legal</p>
            </div>
            <div class="flex-1 text-center">
                <div class="border-b border-slate-300 w-48 mx-auto mb-2"></div>
                <p class="text-xs font-black text-slate-800 uppercase tracking-widest">Conselho Fiscal</p>
                <p class="text-[10px] text-slate-400">Conferência e Aprovação</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('graficoDespesas');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($graficoLabels),
                    datasets: [{
                        data: @json($graficoValores),
                        backgroundColor: ['#6366f1', '#f43f5e', '#22c55e', '#eab308', '#ec4899', '#06b6d4', '#f97316', '#a855f7'],
                        borderWidth: 4,
                        hoverOffset: 20
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                font: { size: 11, weight: 'bold', family: 'Inter' },
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        }
    });
</script>

<style>
    @media print {
        @page { size: portrait; margin: 1cm; }
        nav, aside, .print\:hidden, button { display: none !important; }
        body { background: white !important; padding: 0 !important; }
        .max-w-5xl { max-width: 100% !important; margin: 0 !important; width: 100% !important; }
        #print-area { border: none !important; box-shadow: none !important; padding: 0 !important; }
        .bg-slate-50\/50, .bg-slate-50 { background-color: transparent !important; border: 1px solid #f1f5f9 !important; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    }
</style>
@endsection