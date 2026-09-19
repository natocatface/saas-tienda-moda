<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::query();
        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('apellido', 'like', '%' . $request->buscar . '%')
                  ->orWhere('dni', 'like', '%' . $request->buscar . '%')
                  ->orWhere('email', 'like', '%' . $request->buscar . '%');
            });
        }
        $clientes = $query->orderByDesc('created_at')->paginate(15);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email'    => 'nullable|email|unique:clientes,email',
            'dni'      => 'nullable|string|unique:clientes,dni',
        ]);

        $data = $request->all();
        // Código único a nivel de plataforma: incluye el id de la tienda para
        // evitar colisiones con el índice global UNIQUE de "codigo" entre tiendas.
        $tid = auth()->user()->tienda_id ?? 0;
        $data['codigo'] = 'CLI-' . $tid . '-' . str_pad(Cliente::count() + 1, 5, '0', STR_PAD_LEFT);

        Cliente::create($data);
        return redirect()->route('clientes.index')->with('success', 'Cliente registrado exitosamente.');
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email'    => 'nullable|email|unique:clientes,email,' . $cliente->id,
        ]);
        $cliente->update($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado.');
    }

    public function show(Cliente $cliente)
    {
        $cliente->load('ventas');
        return view('clientes.show', compact('cliente'));
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->update(['activo' => false]);
        return back()->with('success', 'Cliente desactivado.');
    }
}
