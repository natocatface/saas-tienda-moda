@extends('layouts.app')
@section('title','Nuevo Producto')
@section('page-title','Nuevo Producto')

@section('content')
<div class="page-header">
    <div>
        <h1>Nuevo Producto</h1>
        <div class="breadcrumb"><a href="{{ route('dashboard') }}">Inicio</a><span class="breadcrumb-sep">›</span><a href="{{ route('productos.index') }}">Productos</a><span class="breadcrumb-sep">›</span>Nuevo</div>
    </div>
    <a href="{{ route('productos.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
</div>

<form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data">
    @csrf
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">
        <!-- Info principal -->
        <div>
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header"><div class="card-title">Información del Producto</div></div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nombre del Producto *</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                        @error('nombre')<small style="color:#e8398c;">{{ $message }}</small>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Categoría *</label>
                        <select name="categoria_id" id="categoriaSelect" class="form-control" required onchange="filtrarSubcategorias()">
                            <option value="">Seleccionar...</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Subcategoría</label>
                        <select name="subcategoria_id" id="subcategoriaSelect" class="form-control" data-actual="{{ old('subcategoria_id') }}">
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
                                <option value="{{ $m->id }}" {{ old('marca_id') == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Proveedor</label>
                        <select name="proveedor_id" class="form-control">
                            <option value="">Sin proveedor</option>
                            @foreach($proveedores as $pv)
                                <option value="{{ $pv->id }}" {{ old('proveedor_id') == $pv->id ? 'selected' : '' }}>{{ $pv->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Género *</label>
                        <select name="genero" class="form-control" required>
                            <option value="unisex">Unisex</option>
                            <option value="damas">Damas</option>
                            <option value="caballeros">Caballeros</option>
                            <option value="ninos">Niños</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tipo *</label>
                        <select name="tipo" class="form-control" required>
                            <option value="casual">Casual</option>
                            <option value="deportivo">Deportivo</option>
                            <option value="formal">Formal</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
                </div>
            </div>

            <!-- Variantes -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Tallas y Colores (Variantes)</div>
                    <button type="button" class="btn btn-outline btn-sm" id="addVariante"><i class="fa-solid fa-plus"></i> Agregar</button>
                </div>
                <div id="variantes-container">
                    <div class="variante-row form-row" style="align-items:flex-end;border-bottom:1px solid var(--border);padding-bottom:14px;margin-bottom:14px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Talla</label>
                            <input type="text" name="variantes[0][talla]" class="form-control" placeholder="S, M, L, 28...">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Color</label>
                            <input type="text" name="variantes[0][color]" class="form-control" placeholder="Rojo, Azul...">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Stock</label>
                            <input type="number" name="variantes[0][stock]" class="form-control" value="0" min="0">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Stock Mín.</label>
                            <input type="number" name="variantes[0][stock_minimo]" class="form-control" value="5" min="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Precios e imagen -->
        <div>
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header"><div class="card-title">Precios</div></div>
                <div class="form-group">
                    <label class="form-label">Precio de Compra</label>
                    <input type="number" name="precio_compra" class="form-control" value="{{ old('precio_compra','0') }}" step="0.01" min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Precio de Venta *</label>
                    <input type="number" name="precio_venta" class="form-control" value="{{ old('precio_venta') }}" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Precio Oferta</label>
                    <input type="number" name="precio_oferta" class="form-control" value="{{ old('precio_oferta') }}" step="0.01" min="0">
                </div>
            </div>

            <div class="card" style="margin-bottom:20px;">
                <div class="card-header"><div class="card-title">Imagen del Producto</div></div>
                <div style="text-align:center;">
                    <div id="img-preview" style="width:100%;height:160px;border:2px dashed var(--border);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:12px;overflow:hidden;cursor:pointer;">
                        <div id="img-placeholder"><i class="fa-solid fa-image" style="font-size:32px;color:#ccc;"></i><br><small style="color:#aaa;">Click para subir imagen</small></div>
                        <img id="img-shown" src="" style="display:none;width:100%;height:100%;object-fit:cover;">
                    </div>
                    <input type="file" name="imagen" id="imagenInput" accept="image/*" style="display:none;">
                    <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('imagenInput').click()"><i class="fa-solid fa-upload"></i> Seleccionar</button>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><div class="card-title">Opciones</div></div>
                <label style="display:flex;align-items:center;gap:8px;font-size:13px;margin-bottom:12px;cursor:pointer;">
                    <input type="checkbox" name="activo" value="1" checked style="accent-color:var(--accent);width:16px;height:16px;">
                    Producto activo
                </label>
                <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;">
                    <input type="checkbox" name="destacado" value="1" style="accent-color:var(--accent);width:16px;height:16px;">
                    Producto destacado
                </label>
            </div>
        </div>
    </div>

    <div style="margin-top:20px;display:flex;gap:12px;justify-content:flex-end;">
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Guardar Producto</button>
    </div>
</form>
@endsection

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

let varIdx = 1;
document.getElementById('addVariante').addEventListener('click', () => {
    const html = `
        <div class="variante-row form-row" style="align-items:flex-end;border-bottom:1px solid var(--border);padding-bottom:14px;margin-bottom:14px;">
            <div class="form-group" style="margin-bottom:0;"><label class="form-label">Talla</label><input type="text" name="variantes[${varIdx}][talla]" class="form-control" placeholder="S, M, L..."></div>
            <div class="form-group" style="margin-bottom:0;"><label class="form-label">Color</label><input type="text" name="variantes[${varIdx}][color]" class="form-control" placeholder="Rojo..."></div>
            <div class="form-group" style="margin-bottom:0;"><label class="form-label">Stock</label><input type="number" name="variantes[${varIdx}][stock]" class="form-control" value="0" min="0"></div>
            <div class="form-group" style="margin-bottom:0;"><label class="form-label">Mín.</label><input type="number" name="variantes[${varIdx}][stock_minimo]" class="form-control" value="5" min="0"></div>
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.variante-row').remove()"><i class="fa-solid fa-trash"></i></button>
        </div>`;
    document.getElementById('variantes-container').insertAdjacentHTML('beforeend', html);
    varIdx++;
});
document.getElementById('imagenInput').addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('img-shown').src = e.target.result;
            document.getElementById('img-shown').style.display = 'block';
            document.getElementById('img-placeholder').style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
