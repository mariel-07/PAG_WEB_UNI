<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class DocenteController extends Controller {

    // Obtener la información completa de un docente en JSON
    public function show($id) {
        $docente = Docente::with(['domicilios', 'familiares', 'formaciones'])->find($id);

        if (!$docente) {
            return response()->json(['success' => false, 'message' => 'Docente no encontrado'], 44);
        }

        return response()->json(['success' => true, 'data' => $docente]);
    }

    // Guardar / Actualizar datos principales
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'codDocente' => 'required|string|max:20|unique:docentes,codDocente,' . $request->id,
            'dni'        => 'required|string|max:15|unique:docentes,dni,' . $request->id,
            'apePaterno' => 'required|string|max:100',
            'apeMaterno' => 'required|string|max:100',
            'nombres'    => 'required|string|max:100',
        ], [
            'codDocente.required' => 'El código del docente es obligatorio.',
            'dni.required'        => 'El DNI es obligatorio.',
            'nombres.required'    => 'El nombre es obligatorio.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $docente = Docente::updateOrCreate(['id' => $request->id], $request->all());

        return response()->json([
            'success' => true, 
            'message' => 'Información del docente guardada correctamente', 
            'data'    => $docente
        ]);
    }

    // Exportación a PDF (Fase 5)
    public function exportPdf($id) {
        $docente = Docente::with(['domicilios', 'familiares', 'formaciones'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.docente_ficha', compact('docente'));
        return $pdf->download("Ficha_Docente_{$docente->dni}.pdf");
    }
    // Método para retornar la vista del módulo de información docente
public function index()
{
    return view('docente.informacion'); // Asegúrate de que tu vista exista en resources/views/docente/informacion.blade.php
}
}