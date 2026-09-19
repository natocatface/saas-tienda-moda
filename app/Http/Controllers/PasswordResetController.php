<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    private const EXPIRA_MIN = 60;

    /** Formulario para solicitar el enlace de restablecimiento. */
    public function showLinkRequest()
    {
        return view('auth.olvide');
    }

    /** Genera el token y envía (o muestra en local) el enlace de restablecimiento. */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email'], [
            'email.required' => 'Ingresa tu correo.',
            'email.email'    => 'Ingresa un correo válido.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Respuesta genérica para no revelar si el correo existe.
        $mensaje = 'Si el correo está registrado, te enviaremos un enlace para restablecer tu contraseña.';

        if (!$user) {
            return back()->with('status', $mensaje);
        }

        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        $url = route('password.reset', ['token' => $token, 'email' => $user->email]);

        // Envío por correo (best-effort: en local puede no haber servidor SMTP).
        try {
            Mail::raw(
                "Hola {$user->name},\n\n"
                . "Recibimos una solicitud para restablecer tu contraseña.\n"
                . "Ingresa al siguiente enlace (válido por " . self::EXPIRA_MIN . " minutos):\n\n{$url}\n\n"
                . "Si no solicitaste esto, ignora este mensaje.",
                function ($m) use ($user) {
                    $m->to($user->email)->subject('Restablecer contraseña — SaaS Tienda Moda');
                }
            );
        } catch (\Throwable $e) {
            // Silenciar: en desarrollo mostramos el enlace directamente.
        }

        $redirect = back()->with('status', $mensaje);

        // En entorno local mostramos el enlace para poder continuar sin SMTP.
        if (app()->environment('local')) {
            $redirect->with('reset_url', $url);
        }

        return $redirect;
    }

    /** Formulario para definir la nueva contraseña. */
    public function showReset(Request $request, string $token)
    {
        return view('auth.reset', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /** Valida el token y actualiza la contraseña. */
    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $registro = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$registro || !Hash::check($request->token, $registro->token)) {
            return back()->withInput()->with('error', 'El enlace de restablecimiento no es válido.');
        }

        if (Carbon::parse($registro->created_at)->addMinutes(self::EXPIRA_MIN)->isPast()) {
            return back()->with('error', 'El enlace expiró. Solicita uno nuevo.');
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->with('error', 'No encontramos una cuenta con ese correo.');
        }

        $user->update(['password' => Hash::make($request->password)]);
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Tu contraseña fue restablecida. Ya puedes iniciar sesión.');
    }
}
