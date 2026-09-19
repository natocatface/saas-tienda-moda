@extends('layouts.app')
@section('title','Nueva Compra')
@section('page-title','Nueva Compra')

@section('content')
<div class="page-header">
    <div>
        <h1>Registrar Compra</h1>
        <div class="breadcrumb"><a href="{{ route('compras.index') }}">Compras</a><span class="breadcrumb-sep">›</span>Nueva</div>
    </div>
    <a href="{{ route('compras.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
</div>

<form method="POST" action="{{ route('compras.store') }}">
    @csrf
    <div class="card" style="margin-bottom:20px;">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Proveedor</label>
                <select name="proveedor_id" class="form-control" required>
                    <option value="">Selecciona un proveedor</option>
                    @foreach($proveedores as $p)
                        <option value="{{ $p->id }}" {{ old('proveedor_id')==$p->id?'selected':'' }}>{{ $p->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" class="form-control" value="{{ old('fecha', date('Y-m-d')) }}" required>
            </div>
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Observaciones</label>
            <input type="text" name="observaciones" class="form-control" value="{{ old('observaciones') }}" placeholder="Opcional">
        </div>
    </div>

    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fa-solid fa-boxes-stacked" style="color:var(--accent);margin-right:6px;"></i>Productos a ingresar</div></div>
        <div class="table-wrap">
            <table id="itemsTable">
                <thead><tr><th style="width:45%;">Producto / Variante</th><th>Cantidad</th><th>Precio compra</th><th>Subtotal</th><th></th></tr></thead>
                <tbody id="itemsBody"></tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right;font-weight:600;">Total compra:</td>
                        <td style="font-weight:700;color:var(--accent);" id="totalCell">S/ 0.00</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <button type="button" class="btn btn-outline btn-sm" id="addRow" style="margin-top:12px;"><i class="fa-solid fa-plus"></i> Agregar producto</button>
    </div>

    <div style="margin-top:20px;text-align:right;">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Registrar compra y sumar stock</button>
    </div>
</form>

<script>
    const variantes = @json($variantes);
    let idx = 0;

    function optionsHtml() {
        let html = '<option value="">Selecciona...</option>';
        variantes.forEach(v => { html += `<option value="${v.id}" data-precio="${v.precio}">${v.texto}</option>`; });
        return html;
    }

    function addRow() {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><select name="items[${idx}][variante_id]" class="form-control sel-var" style="padding:7px;">${optionsHtml()}</select></td>
            <td><input type="number" name="items[${idx}][cantidad]" min="1" value="1" class="form-control inp-cant" style="width:90px;padding:7px;"></td>
            <td><input type="number" step="0.01" name="items[${idx}][precio]" min="0" value="0" class="form-control inp-prec" style="width:110px;padding:7px;"></td>
            <td class="sub" style="font-weight:600;">S/ 0.00</td>
            <td><button type="button" class="btn btn-sm btn-danger del-row"><i class="fa-solid fa-trash"></i></button></td>`;
        document.getElementById('itemsBody').appendChild(tr);
        idx++;
        bindRow(tr);
    }

    function bindRow(tr) {
        const sel = tr.querySelector('.sel-var');
        const cant = tr.querySelector('.inp-cant');
        const prec = tr.querySelector('.inp-prec');
        const recalc = () => {
            const sub = (parseFloat(cant.value)||0) * (parseFloat(prec.value)||0);
            tr.querySelector('.sub').textContent = 'S/ ' + sub.toFixed(2);
            total();
        };
        sel.addEventListener('change', () => {
            const op = sel.options[sel.selectedIndex];
            prec.value = op.dataset.precio || 0;
            recalc();
        });
        cant.addEventListener('input', recalc);
        prec.addEventListener('input', recalc);
        tr.querySelector('.del-row').addEventListener('click', () => { tr.remove(); total(); });
    }

    function total() {
        let t = 0;
        document.querySelectorAll('#itemsBody tr').forEach(tr => {
            const c = parseFloat(tr.querySelector('.inp-cant').value)||0;
            const p = parseFloat(tr.querySelector('.inp-prec').value)||0;
            t += c * p;
        });
        document.getElementById('totalCell').textContent = 'S/ ' + t.toFixed(2);
    }

    document.getElementById('addRow').addEventListener('click', addRow);
    addRow(); // primera fila
</script>
@endsection
