<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $tid = auth()->user()->tienda_id;
        $query = User::where('tienda_id', $tid)->where('es_super_admin', false)->with('rol');
        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->buscar . '%')
                  ->orWhere('email', 'like', '%' . $request->buscar . '%');
            });
        }
        $usuarios = $query->orderBy('name')->get();
        $roles    = Rol::orderBy('id')->get();
        $editar   = $request->filled('edit') ? User::where('tienda_id', $tid)->find($request->edit) : null;
        $tienda   = auth()->user()->tienda;
        return view('usuarios.index', compact('usuarios', 'roles', 'editar', 'tienda'));
    }

    public function store(Request $request)
    {
        $tienda = auth()->user()->tienda;
        if ($tienda && !$tienda->puedeAgregar('usuarios')) {
            return back()->withInput()->with('error',
                'Alcanzaste el límite de usuarios de tu plan ' . ($tienda->plan->nombre ?? '') .
                ' (' . ($tienda->plan->max_usuarios ?? 0) . '). Mejora tu plan para agregar más.');
        }

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'rol_id'   => 'required|exists:roles,id',
            'telefono' => 'nullable|string|max:30',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'tienda_id' => auth()->user()->tienda_id,
            'name'      => $data['name'],
            'email'     => $data['email'],
            'rol_id'    => $data['rol_id'],
            'telefono'  => $data['telefono'] ?? null,
            'password'  => Hash::make($data['password']),
            'activo'    => true,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function update(Request $request, User $usuario)
    {
        $this->autorizar($usuario);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($usuario->id)],
            'rol_id'   => 'required|exists:roles,id',
            'telefono' => 'nullable|string|max:30',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $usuario->name     = $data['name'];
        $usuario->email    = $data['email'];
        $usuario->rol_id   = $data['rol_id'];
        $usuario->telefono = $data['telefono'] ?? null;
        if (!empty($data['password'])) {
            $usuario->password = Hash::make($data['password']);
        }
        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
    }

    public function toggle(User $usuario)
    {
        $this->autorizar($usuario);
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes desactivar tu propia cuenta.');
        }
        $usuario->update(['activo' => !$usuario->activo]);
        return back()->with('success', 'Estado del usuario actualizado.');
    }

    private function autorizar(User $usuario): void
    {
        abort_unless($usuario->tienda_id === auth()->user()->tienda_id && !$usuario->es_super_admin, 403);
    }
}
