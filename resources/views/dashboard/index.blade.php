@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
/* KPI Cards */
.kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 24px; }
.kpi-card {
    background: var(--card-bg);
    border-radius: 16px;
    padding: 22px 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    border: 1px solid var(--border);
    display: flex; align-items: center; gap: 18px;
    transition: transform 0.2s;
}
.kpi-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
.kpi-icon {
    width: 56px; height: 56px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
}
/* Paneles a todo color (estilo degradado vibrante) */
.kpi-card.c-blue   { background: linear-gradient(135deg,#3b50dd 0%,#5b2fc9 100%); }
.kpi-card.c-green  { background: linear-gradient(135deg,#0ea5a4 0%,#16c0b0 100%); }
.kpi-card.c-purple { background: linear-gradient(135deg,#7b2ff7 0%,#9b2fda 100%); }
.kpi-card.c-pink   { background: linear-gradient(135deg,#c026c9 0%,#e0245e 100%); }
.kpi-card.c-orange { background: linear-gradient(135deg,#f97316 0%,#ef4444 100%); }
.kpi-card.c-blue, .kpi-card.c-green, .kpi-card.c-purple,
.kpi-card.c-pink, .kpi-card.c-orange { border: none; box-shadow: 0 8px 22px rgba(0,0,0,0.18); }
/* Texto blanco e icono translúcido sobre el color */
.kpi-card.c-blue .kpi-label,  .kpi-card.c-green .kpi-label,
.kpi-card.c-purple .kpi-label,.kpi-card.c-pink .kpi-label,
.kpi-card.c-orange .kpi-label { color: rgba(255,255,255,0.85); }
.kpi-card.c-blue .kpi-value,  .kpi-card.c-green .kpi-value,
.kpi-card.c-purple .kpi-value,.kpi-card.c-pink .kpi-value,
.kpi-card.c-orange .kpi-value { color: #fff; }
.kpi-card.c-blue .kpi-change,  .kpi-card.c-green .kpi-change,
.kpi-card.c-purple .kpi-change,.kpi-card.c-pink .kpi-change,
.kpi-card.c-orange .kpi-change { color: rgba(255,255,255,0.9) !important; }
.kpi-card.c-blue .kpi-icon,  .kpi-card.c-green .kpi-icon,
.kpi-card.c-purple .kpi-icon,.kpi-card.c-pink .kpi-icon,
.kpi-card.c-orange .kpi-icon { background: rgba(255,255,255,0.20); color: #fff; }
.kpi-label { font-size: 12px; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
.kpi-value { font-size: 26px; font-weight: 700; color: var(--text-dark); line-height: 1.1; }
.kpi-change { font-size: 12px; margin-top: 4px; }
.kpi-change.up   { color: #16a34a; }
.kpi-change.down { color: #dc2626; }

/* Charts grid */
.charts-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; align-items: start; }
.chart-box { position: relative; height: 250px; }
.chart-box.sm { height: 210px; }
.bottom-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

/* Top products table */
.rank-item {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
}
.rank-item:last-child { border-bottom: none; }
.rank-num {
    width: 26px; height: 26px;
    background: linear-gradient(135deg,var(--accent),var(--accent-dark));
    color: #fff;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700; flex-shrink: 0;
}
.rank-name { flex: 1; font-size: 13px; font-weight: 500; }
.rank-qty  { font-size: 13px; font-weight: 700; color: var(--accent); }

/* Recent sales */
.sale-row {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
}
.sale-row:last-child { border-bottom: none; }
.sale-icon {
    width: 36px; height: 36px;
    background: linear-gradient(135deg,#fce7f3,#fbcfe8);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: var(--accent); font-size: 14px; flex-shrink: 0;
}
.sale-info { flex: 1; }
.sale-num { font-size: 13px; font-weight: 600; }
.sale-client { font-size: 11px; color: var(--text-muted); }
.sale-total { font-size: 14px; font-weight: 700; color: var(--text-dark); }

@media (max-width: 1100px) {
    .charts-grid, .bottom-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <div class="breadcrumb"><i class="fa-solid fa-house"></i> Inicio <span class="breadcrumb-sep">›</span> Dashboard</div>
    </div>
    @if(auth()->user()->puede('ventas'))
    <a href="{{ route('ventas.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Nueva Venta
    </a>
    @endif
</div>

<!-- KPIs -->
<div class="kpi-grid">
    <div class="kpi-card c-pink">
        <div class="kpi-icon pink"><i class="fa-solid fa-sack-dollar"></i></div>
        <div>
            <div class="kpi-label">Ventas de Hoy</div>
            <div class="kpi-value">S/ {{ number_format($ventasHoy, 2) }}</div>
            <div class="kpi-change up"><i class="fa-solid fa-arrow-trend-up"></i> Hoy</div>
        </div>
    </div>
    <div class="kpi-card c-purple">
        <div class="kpi-icon purple"><i class="fa-solid fa-chart-line"></i></div>
        <div>
            <div class="kpi-label">Ventas del Mes</div>
            <div class="kpi-value">S/ {{ number_format($ventasMes, 2) }}</div>
            <div class="kpi-change up"><i class="fa-solid fa-calendar"></i> Este mes</div>
        </div>
    </div>
    <div class="kpi-card c-blue">
        <div class="kpi-icon blue"><i class="fa-solid fa-users"></i></div>
        <div>
            <div class="kpi-label">Clientes</div>
            <div class="kpi-value">{{ number_format($totalClientes) }}</div>
            <div class="kpi-change up">Registrados</div>
        </div>
    </div>
    <div class="kpi-card c-green">
        <div class="kpi-icon green"><i class="fa-solid fa-tags"></i></div>
        <div>
            <div class="kpi-label">Productos</div>
            <div class="kpi-value">{{ number_format($totalProductos) }}</div>
            <div class="kpi-change">En catálogo</div>
        </div>
    </div>
    <div class="kpi-card c-orange">
        <div class="kpi-icon orange"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div>
            <div class="kpi-label">Stock Bajo</div>
            <div class="kpi-value">{{ $stockBajo }}</div>
            <div class="kpi-change down">Requieren reposición</div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="charts-grid">
    <!-- Line Chart -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid fa-chart-line" style="color:var(--accent);margin-right:8px;"></i> Ventas Últimos 7 Días</div>
        </div>
        <div class="chart-box"><canvas id="ventasChart"></canvas></div>
    </div>

    <!-- Donut -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid fa-chart-pie" style="color:var(--accent);margin-right:8px;"></i> Ventas por Categoría</div>
        </div>
        <div class="chart-box sm"><canvas id="categoriaChart"></canvas></div>
        <div style="margin-top:14px;">
            @foreach($ventasPorCategoria as $i => $vc)
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;font-size:12px;">
                <span style="width:10px;height:10px;border-radius:50%;background:{{ ['#e8398c','#7c3aed','#2563eb','#16a34a','#ea580c'][$i] ?? '#ccc' }};display:inline-block;"></span>
                <span style="flex:1;">{{ $vc->nombre }}</span>
                <span style="font-weight:600;">S/ {{ number_format($vc->total,0) }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Charts row 2 -->
<div class="charts-grid">
    <!-- Stock por categoría (barras) -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid fa-warehouse" style="color:var(--accent);margin-right:8px;"></i> Inventario por Categoría</div>
        </div>
        <div class="chart-box"><canvas id="stockChart"></canvas></div>
    </div>

    <!-- Métodos de pago (dona) -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid fa-credit-card" style="color:var(--accent);margin-right:8px;"></i> Métodos de Pago</div>
        </div>
        <div class="chart-box"><canvas id="metodoChart"></canvas></div>
    </div>
</div>

<!-- Bottom grid -->
<div class="bottom-grid">
    <!-- Top Productos -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid fa-ranking-star" style="color:var(--accent);margin-right:8px;"></i> Top Productos del Mes</div>
        </div>
        @forelse($topProductos as $i => $prod)
        <div class="rank-item">
            <div class="rank-num">{{ $i+1 }}</div>
            <div class="rank-name">{{ $prod->nombre }}</div>
            <div class="rank-qty">{{ $prod->total_vendido }} uds.</div>
        </div>
        @empty
        <p style="color:var(--text-muted);font-size:13px;text-align:center;padding:20px;">Sin ventas este mes</p>
        @endforelse
    </div>

    <!-- Últimas ventas -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid fa-clock-rotate-left" style="color:var(--accent);margin-right:8px;"></i> Últimas Ventas</div>
            @if(auth()->user()->puede('ventas'))<a href="{{ route('ventas.index') }}" class="btn btn-outline btn-sm">Ver todas</a>@endif
        </div>
        @forelse($ultimasVentas as $v)
        <div class="sale-row">
            <div class="sale-icon"><i class="fa-solid fa-receipt"></i></div>
            <div class="sale-info">
                <div class="sale-num">{{ $v->numero_venta }}</div>
                <div class="sale-client">{{ $v->cliente ? $v->cliente->nombre . ' ' . $v->cliente->apellido : 'Cliente directo' }}</div>
            </div>
            <div>
                <div class="sale-total">S/ {{ number_format($v->total, 2) }}</div>
                <span class="badge {{ $v->estado === 'completada' ? 'badge-success' : ($v->estado === 'anulada' ? 'badge-danger' : 'badge-warning') }}">
                    {{ ucfirst($v->estado) }}
                </span>
            </div>
        </div>
        @empty
        <p style="color:var(--text-muted);font-size:13px;text-align:center;padding:20px;">Sin ventas aún</p>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
const labels = @json($labels);
const data   = @json($data);

// Ventas line chart
new Chart(document.getElementById('ventasChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Ventas (S/)',
            data,
            borderColor: '#e8398c',
            backgroundColor: 'rgba(232,57,140,0.08)',
            borderWidth: 2.5,
            pointBackgroundColor: '#e8398c',
            pointRadius: 5,
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f0f0f5' }, ticks: { font: { family: 'Poppins', size: 11 } } },
            x: { grid: { display: false }, ticks: { font: { family: 'Poppins', size: 11 } } }
        }
    }
});

// Categoría donut
const catLabels = @json($ventasPorCategoria->pluck('nombre'));
const catData   = @json($ventasPorCategoria->pluck('total'));
new Chart(document.getElementById('categoriaChart'), {
    type: 'doughnut',
    data: {
        labels: catLabels.length ? catLabels : ['Sin datos'],
        datasets: [{
            data: catData.length ? catData : [1],
            backgroundColor: ['#e8398c','#7c3aed','#2563eb','#16a34a','#ea580c'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: { legend: { display: false } }
    }
});

// Inventario por categoría (barras)
const stockLabels = @json($stockCatLabels);
const stockData   = @json($stockCatData);
new Chart(document.getElementById('stockChart'), {
    type: 'bar',
    data: {
        labels: stockLabels.length ? stockLabels : ['Sin datos'],
        datasets: [{
            label: 'Unidades en stock',
            data: stockData.length ? stockData : [0],
            backgroundColor: 'rgba(124,58,237,0.75)',
            hoverBackgroundColor: '#7c3aed',
            borderRadius: 6,
            maxBarThickness: 38,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f0f0f5' }, ticks: { font: { family: 'Poppins', size: 11 } } },
            x: { grid: { display: false }, ticks: { font: { family: 'Poppins', size: 10 } } }
        }
    }
});

// Métodos de pago (barras horizontales)
const metodoLabels = @json($metodoLabels);
const metodoData   = @json($metodoData);
new Chart(document.getElementById('metodoChart'), {
    type: 'bar',
    data: {
        labels: metodoLabels.length ? metodoLabels : ['Sin datos'],
        datasets: [{
            label: 'Total (S/)',
            data: metodoData.length ? metodoData : [0],
            backgroundColor: ['#e8398c','#7c3aed','#2563eb','#16a34a','#ea580c'],
            borderRadius: 6,
            maxBarThickness: 26,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, grid: { color: '#f0f0f5' }, ticks: { font: { family: 'Poppins', size: 11 } } },
            y: { grid: { display: false }, ticks: { font: { family: 'Poppins', size: 12 } } }
        }
    }
});
</script>
@endpush
