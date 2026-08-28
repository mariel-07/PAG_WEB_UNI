<?php

namespace App\Http\Controllers;

use App\Models\DocenteFamiliar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class DocenteFamiliarController extends Controller {
    
    public function getFamiliares($docenteId) {
        $data = DocenteFamiliar::where('docente_id', $docenteId)->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'docente_id' => 'required|exists:docentes,id',
            'apellidosNombres' => 'required|string|max:200',
            'parentesco' => 'required|string',
            'dependeEconomicamente' => 'required|in:Si,No',
        ], [
            'apellidosNombres.required' => 'El nombre completo es obligatorio.',
            'parentesco.required' => 'Seleccione un parentesco.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $familiar = DocenteFamiliar::create($request->all());
        return response()->json(['success' => true, 'data' => $familiar, 'message' => 'Familiar guardado con éxito']);
    }

    public function destroy($id) {
        DocenteFamiliar::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Familiar eliminado']);
    }

    public function exportPdf($docenteId) {
        $familiares = DocenteFamiliar::where('docente_id', $docenteId)->get();
        $pdf = Pdf::loadView('pdf.familiares', compact('familiares'));
        return $pdf->download("Carga_Familiar_Docente_{$docenteId}.pdf");
    }
}