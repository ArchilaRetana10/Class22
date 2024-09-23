<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactosController extends Controller
{
    public function index()
    {
        $contactos = Contact::orderBy('created_at', 'desc')->get();
        return view('contactos.index', compact('contactos'));

    }

    public function store(Request $request)
    {
    $request->validate([
        'nombre' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'publicidad' => 'boolean',
        'mensaje' => 'required|string',
    ]);

    $con = new mysqli("localhost", "root", "", "tienda");

    if ($con->connect_errno) {
        return response()->json(['error' => 'Failed to connect to MySQL: ' . $con->connect_error]);
    }

    $result = $con->query("SELECT MIN(id) + 1 AS next_id FROM contact WHERE id NOT IN (SELECT id FROM contact)");
    $row = $result->fetch_assoc();
    $next_id = $row['next_id'] ?? 1; 

    $nombre = $request->input('nombre');
    $email = $request->input('email');
    $publicidad = $request->input('publicidad');
    $mensaje = $request->input('mensaje');
    
    $stmt = $con->prepare("INSERT INTO contact (id, nombre, email, publicidad, mensaje) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issis", $next_id, $nombre, $email, $publicidad, $mensaje);
    $stmt->execute();

    $stmt->close();
    $con->close();

    return redirect()->route('contactos.index')->with('success', 'Contacto agregado exitosamente.');
}

}
