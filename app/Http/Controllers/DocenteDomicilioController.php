<?php


namespace App\Http\Controllers;

use App\Models\DocenteDomicilio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocenteDomicilioController extends Controller {

    public function getDomicilios($docenteId) {
        $data = DocenteDomicilio::where('docente_id', $docenteId)->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'docente_id' => 'required|exists:docentes,id',
            'pais'       => 'required|string|max:100',
            'tipoVia'    => 'nullable|string|max:50',
            'nombreVia'  => 'nullable|string|max:150',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $domicilio = DocenteDomicilio::create($request->all());
        return response()->json(['success' => true, 'data' => $domicilio, 'message' => 'Domicilio guardado']);
    }

    public function destroy($id) {
        DocenteDomicilio::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Domicilio eliminado']);
    }
}