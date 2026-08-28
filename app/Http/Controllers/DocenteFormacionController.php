<?php

namespace App\Http\Controllers;

use App\Models\DocenteFormacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocenteFormacionController extends Controller {

    public function getFormaciones($docenteId) {
        $data = DocenteFormacion::where('docente_id', $docenteId)->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'docente_id'           => 'required|exists:docentes,id',
            'formacionProfesional' => 'required|string|max:100',
            'universidad'          => 'required|string|max:200',
            'especialidad'         => 'required|string|max:200',
        ], [
            'formacionProfesional.required' => 'Especifique la formación académica.',
            'universidad.required'          => 'La universidad es obligatoria.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $formacion = DocenteFormacion::create($request->all());
        return response()->json(['success' => true, 'data' => $formacion, 'message' => 'Formación registrada']);
    }

    public function destroy($id) {
        DocenteFormacion::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Formación eliminada']);
    }
}