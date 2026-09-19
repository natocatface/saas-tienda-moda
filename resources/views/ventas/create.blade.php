@extends('layouts.app')
@section('title','Nueva Venta')
@section('page-title','Nueva Venta - POS')

@push('styles')
<style>
.pos-grid { display: grid; grid-template-columns: 1fr 380px; gap: 20px; }
.pos-search { position: relative; margin-bottom: 16px; }
.pos-search input { width:100%;padding:10px 14px 10px 40px;border:1.5px solid var(--border);border-radius:8px;font-size:14px;font-family:'Poppins',sans-serif; }
.pos-search i { position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#aaa; }
.product-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;max-height:420px;overflow-y:auto;padding-right:4px; }
.product-card {
    background:#fff;border:1.5px solid var(--border);border-radius:12px;padding:12px;cursor:pointer;
    transition:all 0.2s;text-align:center;
}
.product-card:hover { border-color:var(--accent);box-shadow:0 4px 16px rgba(232,57,140,0.15);transform:translateY(-2px); }
.product-card.agotado { opacity:.45;cursor:not-allowed; }
.product-card .prod-img { width:60px;height:60px;border-radius:10px;background:linear-gradient(135deg,#fce7f3,#fbcfe8);display:flex;align-items:center;justify-content:center;margin:0 auto 8px;font-size:24px;color:#be185d; }
.product-card .prod-name { font-size:12px;font-weight:600;color:var(--text-dark);margin-bottom:3px; }
.product-card .prod-price { font-size:14px;font-weight:700;color:var(--accent); }
.product-card .prod-stock { font-size:10px;color:var(--text-muted); }

/* Cart */
.cart-panel { background:var(--card-bg);border-radius:14px;border:1px solid var(--border);display:flex;flex-direction:column;max-height:calc(100vh - 160px);position:sticky;top:80px; }
.cart-header { padding:18px 20px;border-bottom:1px solid var(--border);font-weight:600;font-size:15px; }
.cart-items { flex:1;overflow-y:auto;padding:14px 20px; }
.cart-item { display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--border); }
.cart-item:last-child { border-bottom:none; }
.cart-item-name { flex:1;font-size:13px;font-weight:500; }
.cart-item-name small { display:block;color:var(--text-muted);font-weight:400;font-size:11px; }
.cart-item-qty { display:flex;align-items:center;gap:6px; }
.qty-btn { width:26px;height:26px;border:none;border-radius:6px;background:#f3f4f6;cursor:pointer;font-weight:700;font-size:14px;display:flex;align-items:center;justify-content:center; }
.qty-btn:hover { background:var(--accent);color:#fff; }
.cart-item-price { font-size:13px;font-weight:700;color:var(--accent);width:70px;text-align:right; }
.cart-item-del { color:#ef4444;cursor:pointer;background:none;border:none;font-size:14px; }
.cart-summary { padding:16px 20px;border-top:1px solid var(--border); }
.summary-row { display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px;color:var(--text-muted); }
.summary-total { display:flex;justify-content:space-between;font-size:18px;font-weight:700;color:var(--text-dark);margin:10px 0; }
.cart-footer { padding:16px 20px;border-top:1px solid var(--border); }

/* Modal de variantes (talla / color) */
.var-overlay { position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;z-index:1000;padding:16px; }
.var-overlay.open { display:flex; }
.var-modal { background:#fff;border-radius:16px;max-width:440px;width:100%;padding:22px;box-shadow:0 20px 60px rgba(0,0,0,.25); }
.var-modal h3 { font-size:16px;font-weight:700;margin-bottom:4px;color:var(--text-dark); }
.var-modal p.sub { font-size:12px;color:var(--text-muted);margin-bottom:16px; }
.var-list { display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:10px;max-height:300px;overflow-y:auto; }
.var-chip { border:1.5px solid var(--border);border-radius:10px;padding:10px;cursor:pointer;text-align:center;transition:all .15s; }
.var-chip:hover { border-color:var(--accent);background:#fdf2f8; }
.var-chip.sin { opacity:.4;cursor:not-allowed;background:#f9fafb; }
.var-chip .vc-talla { font-weight:700;font-size:14px;color:var(--text-dark); }
.var-chip .vc-color { font-size:12px;color:var(--text-muted); }
.var-chip .vc-stock { font-size:11px;margin-top:3px;color:#16a34a;font-weight:600; }
.var-chip.sin .vc-stock { color:#dc2626; }
.var-close { margin-top:16px;width:100%;text-align:center; }

/* ===== RESPONSIVE POS ===== */
@media (max-width: 1024px) {
    .pos-grid { grid-template-columns: 1fr; }
    .cart-panel { position: static; max-height: none; }
    .product-grid { max-height: none; }
}
@media (max-width: 640px) {
    .product-grid { grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 10px; }
    .product-card .prod-img { width: 50px; height: 50px; font-size: 20px; }
    .product-card .prod-name { font-size: 11.5px; }
}
@media (max-width: 380px) {
    .product-grid { grid-template-columns: 1fr 1fr; }
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div><h1>Punto de Venta</h1></div>
    <a href="{{ route('ventas.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Historial</a>
</div>

<div class="pos-grid">
    <!-- Products panel -->
    <div>
        <div class="card">
            <div class="pos-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="productSearch" placeholder="Buscar producto por nombre o código...">
            </div>
            <div class="product-grid" id="productGrid">
                @foreach($productos as $p)
                @php $stockP = $p->stockTotal(); @endphp
                <div class="product-card {{ $stockP <= 0 ? 'agotado' : '' }}" onclick="selectProduct({{ $p->id }})">
                    <div class="prod-img"><i class="fa-solid fa-shirt"></i></div>
                    <div class="prod-name">{{ $p->nombre }}</div>
                    <div class="prod-price">S/ {{ number_format($p->precio_venta,2) }}</div>
                    <div class="prod-stock">Stock: {{ $stockP }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Cart panel -->
    <div class="cart-panel">
        <div class="cart-header"><i class="fa-solid fa-cart-shopping" style="color:var(--accent);margin-right:8px;"></i> Carrito de Venta</div>
        <div class="cart-items" id="cartItems">
            <p id="cartEmpty" style="text-align:center;color:var(--text-muted);padding:30px;font-size:13px;">
                <i class="fa-solid fa-bag-shopping" style="font-size:32px;display:block;margin-bottom:8px;"></i>
                Agrega productos al carrito
            </p>
        </div>
        <div class="cart-summary">
            <div class="summary-row"><span>Subtotal</span><span id="sumSubtotal">S/ 0.00</span></div>
            <div class="summary-row"><span>IGV (18%)</span><span id="sumIGV">S/ 0.00</span></div>
            <div class="summary-row"><span>Descuento</span><span id="sumDesc">S/ 0.00</span></div>
            <div class="summary-total"><span>TOTAL</span><span id="sumTotal">S/ 0.00</span></div>
        </div>
        <div class="cart-footer">
            <div class="form-group">
                <label class="form-label">Cliente</label>
                <select id="clienteSelect" class="form-control">
                    <option value="">Cliente directo</option>
                    @foreach($clientes as $c)
                        <option value="{{ $c->id }}">{{ $c->nombre }} {{ $c->apellido }} ({{ $c->codigo }})</option>
                    @endforeach
                </select>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div class="form-group">
                    <label class="form-label">Comprobante</label>
                    <select id="comprobanteSelect" class="form-control">
                        <option value="boleta">Boleta</option>
                        <option value="factura">Factura</option>
                        <option value="ticket">Ticket</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Método pago</label>
                    <select id="pagoSelect" class="form-control">
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="yape">Yape</option>
                        <option value="plin">Plin</option>
                        <option value="transferencia">Transferencia</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Monto pagado (S/)</label>
                <input type="number" id="montoPagado" class="form-control" step="0.01" placeholder="0.00" oninput="calcVuelto()">
            </div>
            <div id="vueltoRow" style="display:none;background:linear-gradient(135deg,#dcfce7,#bbf7d0);border-radius:8px;padding:10px 14px;margin-bottom:12px;justify-content:space-between;font-weight:600;font-size:14px;color:#166534;">
                <span>Vuelto:</span><span id="vueltoAmt">S/ 0.00</span>
            </div>
            <button class="btn btn-primary" style="width:100%;font-size:15px;padding:14px;" onclick="procesarVenta()">
                <i class="fa-solid fa-check-circle"></i> Confirmar Venta
            </button>
        </div>
    </div>
</div>

<!-- Modal selección de variante (talla / color) -->
<div class="var-overlay" id="varOverlay" onclick="if(event.target===this)cerrarVariantes()">
    <div class="var-modal">
        <h3 id="varTitle">Elige talla y color</h3>
        <p class="sub">Selecciona la variante que deseas agregar al carrito.</p>
        <div class="var-list" id="varList"></div>
        <button class="btn btn-secondary var-close" onclick="cerrarVariantes()">Cancelar</button>
    </div>
</div>

<!-- Hidden form -->
<form id="ventaForm" method="POST" action="{{ route('ventas.store') }}" style="display:none;">
    @csrf
    <div id="formItems"></div>
    <input type="hidden" name="cliente_id" id="hCliente">
    <input type="hidden" name="tipo_comprobante" id="hComprobante">
    <input type="hidden" name="metodo_pago" id="hPago">
    <input type="hidden" name="monto_pagado" id="hMontoPagado">
</form>
@endsection

@push('scripts')
@php
    $catalogoProductos = $productos->map(fn ($p) => [
        'id'        => $p->id,
        'nombre'    => $p->nombre,
        'precio'    => (float) $p->precio_venta,
        'variantes' => $p->variantes->map(fn ($v) => [
            'id'    => $v->id,
            'talla' => $v->talla,
            'color' => $v->color,
            'stock' => (int) $v->stock,
        ])->values(),
    ])->values();
@endphp
<script>
// Catálogo con variantes (talla/color/stock) embebido desde el servidor
const PRODUCTOS = @json($catalogoProductos);

let cart = [];

function findProducto(id) { return PRODUCTOS.find(p => p.id === id); }

// Clave única por línea de carrito (variante o producto sin variante)
function lineKey(productoId, varianteId) {
    return varianteId ? 'v' + varianteId : 'p' + productoId;
}

function selectProduct(id) {
    const prod = findProducto(id);
    if (!prod) return;

    // Producto sin variantes: se agrega sin control de stock por talla/color
    if (!prod.variantes || prod.variantes.length === 0) {
        addLine({ producto_id: prod.id, variante_id: null, nombre: prod.nombre,
                  talla: null, color: null, precio_unitario: prod.precio, stock: 999999 });
        return;
    }
    abrirVariantes(prod);
}

function abrirVariantes(prod) {
    document.getElementById('varTitle').textContent = prod.nombre;
    const list = document.getElementById('varList');
    list.innerHTML = prod.variantes.map(v => {
        const sin = v.stock <= 0;
        return `<div class="var-chip ${sin ? 'sin' : ''}" ${sin ? '' : `onclick="agregarVariante(${prod.id}, ${v.id})"`}>
                    <div class="vc-talla">${v.talla}</div>
                    <div class="vc-color">${v.color}</div>
                    <div class="vc-stock">${sin ? 'Agotado' : 'Stock: ' + v.stock}</div>
                </div>`;
    }).join('');
    document.getElementById('varOverlay').classList.add('open');
}

function cerrarVariantes() { document.getElementById('varOverlay').classList.remove('open'); }

function agregarVariante(prodId, varId) {
    const prod = findProducto(prodId);
    const v = prod.variantes.find(x => x.id === varId);
    if (!v || v.stock <= 0) return;
    addLine({ producto_id: prod.id, variante_id: v.id,
              nombre: prod.nombre, talla: v.talla, color: v.color,
              precio_unitario: prod.precio, stock: v.stock });
    cerrarVariantes();
}

function addLine(data) {
    const key = lineKey(data.producto_id, data.variante_id);
    const idx = cart.findIndex(i => i.key === key);
    if (idx >= 0) {
        if (cart[idx].cantidad < cart[idx].stock) cart[idx].cantidad++;
        else { alert('Stock insuficiente para esta variante.'); return; }
    } else {
        cart.push({ key, ...data, cantidad: 1, descuento: 0 });
    }
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cartItems');
    if (cart.length === 0) {
        container.innerHTML = `<p id="cartEmpty" style="text-align:center;color:var(--text-muted);padding:30px;font-size:13px;"><i class="fa-solid fa-bag-shopping" style="font-size:32px;display:block;margin-bottom:8px;"></i>Agrega productos al carrito</p>`;
        updateTotals(); return;
    }
    container.innerHTML = cart.map((item, i) => {
        const detalle = item.talla ? `${item.talla} / ${item.color}` : 'Sin variante';
        return `
        <div class="cart-item">
            <div class="cart-item-name">${item.nombre}<small>${detalle}</small></div>
            <div class="cart-item-qty">
                <button class="qty-btn" onclick="changeQty(${i},-1)">−</button>
                <span style="font-weight:700;font-size:14px;min-width:24px;text-align:center;">${item.cantidad}</span>
                <button class="qty-btn" onclick="changeQty(${i},1)">+</button>
            </div>
            <div class="cart-item-price">S/ ${(item.precio_unitario * item.cantidad).toFixed(2)}</div>
            <button class="cart-item-del" onclick="removeItem(${i})"><i class="fa-solid fa-times"></i></button>
        </div>`;
    }).join('');
    updateTotals();
}

function changeQty(i, delta) {
    const next = cart[i].cantidad + delta;
    if (next > cart[i].stock) { alert('Stock insuficiente para esta variante.'); return; }
    cart[i].cantidad = next;
    if (cart[i].cantidad <= 0) cart.splice(i, 1);
    renderCart();
}

function removeItem(i) { cart.splice(i, 1); renderCart(); }

function updateTotals() {
    const sub = cart.reduce((s, i) => s + i.precio_unitario * i.cantidad, 0);
    const igv = sub * 0.18;
    const total = sub + igv;
    document.getElementById('sumSubtotal').textContent = 'S/ ' + sub.toFixed(2);
    document.getElementById('sumIGV').textContent = 'S/ ' + igv.toFixed(2);
    document.getElementById('sumTotal').textContent = 'S/ ' + total.toFixed(2);
    calcVuelto();
}

function calcVuelto() {
    const total = cart.reduce((s,i) => s + i.precio_unitario*i.cantidad, 0) * 1.18;
    const pagado = parseFloat(document.getElementById('montoPagado').value) || 0;
    const vuelto = pagado - total;
    const row = document.getElementById('vueltoRow');
    if (pagado > 0 && vuelto >= 0) {
        row.style.display = 'flex';
        document.getElementById('vueltoAmt').textContent = 'S/ ' + vuelto.toFixed(2);
    } else {
        row.style.display = 'none';
    }
}

function procesarVenta() {
    if (cart.length === 0) { alert('Agrega al menos un producto'); return; }
    const items = document.getElementById('formItems');
    items.innerHTML = '';
    cart.forEach((item, i) => {
        items.innerHTML += `
            <input type="hidden" name="items[${i}][producto_id]" value="${item.producto_id}">
            <input type="hidden" name="items[${i}][variante_id]" value="${item.variante_id ?? ''}">
            <input type="hidden" name="items[${i}][nombre]" value="${item.nombre}">
            <input type="hidden" name="items[${i}][talla]" value="${item.talla ?? ''}">
            <input type="hidden" name="items[${i}][color]" value="${item.color ?? ''}">
            <input type="hidden" name="items[${i}][cantidad]" value="${item.cantidad}">
            <input type="hidden" name="items[${i}][precio_unitario]" value="${item.precio_unitario}">
            <input type="hidden" name="items[${i}][descuento]" value="0">`;
    });
    document.getElementById('hCliente').value = document.getElementById('clienteSelect').value;
    document.getElementById('hComprobante').value = document.getElementById('comprobanteSelect').value;
    document.getElementById('hPago').value = document.getElementById('pagoSelect').value;
    document.getElementById('hMontoPagado').value = document.getElementById('montoPagado').value || 0;
    document.getElementById('ventaForm').submit();
}

// Search filter
document.getElementById('productSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.product-card').forEach(card => {
        const name = card.querySelector('.prod-name').textContent.toLowerCase();
        card.style.display = name.includes(q) ? '' : 'none';
    });
});
</script>
@endpush
