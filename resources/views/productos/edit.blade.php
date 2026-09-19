@extends('layouts.app')
@section('title','Editar Producto')
@section('page-title','Editar Producto')

@section('content')
<div class="page-header">
    <div><h1>Editar: {{ $producto->nombre }}</h1></div>
    <a href="{{ route('productos.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
</div>

<form method="POST" action="{{ route('productos.update',$producto) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">
        <div>
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header"><div class="card-title">Información del Producto</div></div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre',$producto->nombre) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Categoría *</label>
                        <select name="categoria_id" id="categoriaSelect" class="form-control" required onchange="filtrarSubcategorias()">
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" {{ $producto->categoria_id == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Subcategoría</label>
                        <select name="subcategoria_id" id="subcategoriaSelect" class="form-control" data-actual="{{ old('subcategoria_id', $producto->subcategoria_id) }}">
                            <option value="">Sin subcategoría</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Marca</label>
                        <select name="marca_id" class="form-control">
                            <option value="">Sin marca</option>
                            @foreach($marcas as $m)
                                <option value="{{ $m->id }}" {{ $producto->marca_id == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Proveedor</label>
                        <select name="proveedor_id" class="form-control">
                            <option value="">Sin proveedor</option>
                            @foreach($proveedores as $pv)
                                <option value="{{ $pv->id }}" {{ $producto->proveedor_id == $pv->id ? 'selected' : '' }}>{{ $pv->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Género</label>
                        <select name="genero" class="form-control">
                            @foreach(['unisex','damas','caballeros','ninos'] as $g)
                                <option value="{{ $g }}" {{ $producto->genero == $g ? 'selected' : '' }}>{{ ucfirst($g) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-control">
                            @foreach(['casual','deportivo','formal','otro'] as $t)
                                <option value="{{ $t }}" {{ $producto->tipo == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion',$producto->descripcion) }}</textarea>
                </div>
            </div>
        </div>

        <div>
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header"><div class="card-title">Precios</div></div>
                <div class="form-group">
                    <label class="form-label">Precio Compra</label>
                    <input type="number" name="precio_compra" class="form-control" value="{{ old('precio_compra',$producto->precio_compra) }}" step="0.01">
                </div>
                <div class="form-group">
                    <label class="form-label">Precio Venta *</label>
                    <input type="number" name="precio_venta" class="form-control" value="{{ old('precio_venta',$producto->precio_venta) }}" step="0.01" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Precio Oferta</label>
                    <input type="number" name="precio_oferta" class="form-control" value="{{ old('precio_oferta',$producto->precio_oferta) }}" step="0.01">
                </div>
            </div>
            <div class="card">
                <div class="card-header"><div class="card-title">Opciones</div></div>
                <label style="display:flex;align-items:center;gap:8px;font-size:13px;margin-bottom:12px;cursor:pointer;">
                    <input type="checkbox" name="activo" value="1" {{ $producto->activo ? 'checked' : '' }} style="accent-color:var(--accent);width:16px;height:16px;">
                    Producto activo
                </label>
                <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;">
                    <input type="checkbox" name="destacado" value="1" {{ $producto->destacado ? 'checked' : '' }} style="accent-color:var(--accent);width:16px;height:16px;">
                    Producto destacado
                </label>
            </div>
        </div>
    </div>
    <!-- Variantes (talla / color / stock) -->
    <div class="card" style="margin-top:20px;">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid fa-palette" style="color:var(--accent);margin-right:6px;"></i>Variantes (talla / color / stock)</div>
            <button type="button" class="btn btn-sm btn-primary" onclick="agregarVariante()"><i class="fa-solid fa-plus"></i> Agregar variante</button>
        </div>

        <div class="table-wrap">
            <table>
                <thead><tr><th>Talla</th><th>Color</th><th>Stock</th><th>Stock mínimo</th><th style="width:60px;">Quitar</th></tr></thead>
                <tbody id="variantesBody">
                    @forelse($producto->variantes as $v)
                    <tr data-row>
                        <td><input type="text" name="variantes_existentes[{{ $v->id }}][talla]" class="form-control" value="{{ $v->talla }}" required></td>
                        <td><input type="text" name="variantes_existentes[{{ $v->id }}][color]" class="form-control" value="{{ $v->color }}" required></td>
                        <td><input type="number" name="variantes_existentes[{{ $v->id }}][stock]" class="form-control" value="{{ $v->stock }}" min="0"></td>
                        <td><input type="number" name="variantes_existentes[{{ $v->id }}][stock_minimo]" class="form-control" value="{{ $v->stock_minimo }}" min="0"></td>
                        <td style="text-align:center;">
                            <label style="cursor:pointer;color:#dc2626;" title="Marcar para eliminar">
                                <input type="checkbox" name="variantes_eliminar[]" value="{{ $v->id }}" style="accent-color:#dc2626;" onchange="this.closest('tr').style.opacity=this.checked?'0.4':'1';">
                                <i class="fa-solid fa-trash"></i>
                            </label>
                        </td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
        <p style="font-size:12px;color:var(--text-muted);margin-top:10px;">Marca la papelera para eliminar una variante. Las variantes con ventas o compras registradas no se eliminan (se conservan por integridad).</p>
    </div>

    <div style="margin-top:20px;display:flex;gap:12px;justify-content:flex-end;">
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Actualizar</button>
    </div>
</form>

@push('scripts')
<script>
const SUBCATS = @json($subcategorias);
function filtrarSubcategorias() {
    const catId = document.getElementById('categoriaSelect').value;
    const sel   = document.getElementById('subcategoriaSelect');
    const actual = sel.getAttribute('data-actual');
    sel.innerHTML = '<option value="">Sin subcategoría</option>';
    SUBCATS.filter(s => String(s.categoria_id) === String(catId)).forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id; opt.textContent = s.nombre;
        if (String(actual) === String(s.id)) opt.selected = true;
        sel.appendChild(opt);
    });
}
document.addEventListener('DOMContentLoaded', filtrarSubcategorias);

let nuevaVar = 0;
function agregarVariante() {
    const i = nuevaVar++;
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="text" name="variantes_nuevas[${i}][talla]" class="form-control" placeholder="Ej. M" required></td>
        <td><input type="text" name="variantes_nuevas[${i}][color]" class="form-control" placeholder="Ej. Negro" required></td>
        <td><input type="number" name="variantes_nuevas[${i}][stock]" class="form-control" value="0" min="0"></td>
        <td><input type="number" name="variantes_nuevas[${i}][stock_minimo]" class="form-control" value="5" min="0"></td>
        <td style="text-align:center;">
            <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()"><i class="fa-solid fa-xmark"></i></button>
        </td>`;
    document.getElementById('variantesBody').appendChild(tr);
}
</script>
@endpush
@endsection
