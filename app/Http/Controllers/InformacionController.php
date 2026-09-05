<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\DocenteDomicilio;
use App\Models\DocenteFamiliar;
use App\Models\DocenteFormacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class InformacionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | OBTENER DOCENTE AUTENTICADO
    |--------------------------------------------------------------------------
    */

    private function obtenerDocente()
    {
        $usuario = Auth::user();

        if (!$usuario) {
            abort(401, 'Usuario no autenticado.');
        }

        /*
        |--------------------------------------------------------------------------
        | Buscar docente por email del usuario autenticado
        |--------------------------------------------------------------------------
        */

        $docente = Docente::where('email', $usuario->email)->first();

        /*
        |--------------------------------------------------------------------------
        | Si no existe, crear registro inicial
        |--------------------------------------------------------------------------
        */

        if (!$docente) {

            $codDocente = explode('@', $usuario->email)[0];

            $docente = Docente::create([
                'codDocente' => $codDocente,
                'email' => $usuario->email,
                'nombres' => $usuario->name,
                'estado' => '1',
            ]);
        }

        return $docente;
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER TODA LA INFORMACIÓN DEL DOCENTE
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        try {

            $docente = $this->obtenerDocente();

            $docente->load([
                'domicilios',
                'familiares',
                'formaciones',
            ]);

            /*
            |--------------------------------------------------------------------------
            | IMPORTANTE:
            | Los datos se devuelven directamente.
            |
            | Esto coincide con informacion.js:
            |
            | resultado.docente
            | resultado.domicilios
            | resultado.familiares
            | resultado.formaciones
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,

                'docente' => $docente,

                'domicilios' => $docente->domicilios,

                'familiares' => $docente->familiares,

                'formaciones' => $docente->formaciones,
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo obtener la información del docente.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR DATOS PERSONALES
    |--------------------------------------------------------------------------
    */

    public function actualizarInformacion(Request $request)
    {
        try {

            $docente = $this->obtenerDocente();

            $validated = $request->validate([

                /*
                |--------------------------------------------------------------------------
                | IDENTIFICACIÓN DEL DOCENTE
                |--------------------------------------------------------------------------
                */

                'apePaterno' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'apeMaterno' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'nombres' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'dni' => [
                    'nullable',
                    'string',
                    'max:15',
                ],

                /*
                |--------------------------------------------------------------------------
                | DATOS PERSONALES
                |--------------------------------------------------------------------------
                */

                'sexo' => [
                    'nullable',
                    'string',
                    'max:1',
                ],

                'fecNacimiento' => [
                    'nullable',
                    'date',
                ],

                'telefono' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'celular' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'ruc' => [
                    'nullable',
                    'string',
                    'max:20',
                ],

                'libMilitar' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'breveteCategoria' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'vehiculoInfo' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'estadoCivil' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'fecMatrimonio' => [
                    'nullable',
                    'date',
                ],

                'grupoSanguineo' => [
                    'nullable',
                    'string',
                    'max:10',
                ],

                /*
                |--------------------------------------------------------------------------
                | LUGAR DE NACIMIENTO
                |--------------------------------------------------------------------------
                */

                'paisNacimiento' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'departamentoNacimiento' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'provinciaNacimiento' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'distritoNacimiento' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'direccionExtranjeraNac' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
            ]);


            /*
            |--------------------------------------------------------------------------
            | NO PERMITIR MODIFICAR IDENTIDAD DEL SISTEMA
            |--------------------------------------------------------------------------
            |
            | email y codDocente no vienen del formulario.
            | Se mantienen vinculados al usuario autenticado.
            |--------------------------------------------------------------------------
            */

            unset(
                $validated['email'],
                $validated['codDocente']
            );


            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR MYSQL
            |--------------------------------------------------------------------------
            */

            $docente->update($validated);


            /*
            |--------------------------------------------------------------------------
            | RECARGAR DESDE MYSQL
            |--------------------------------------------------------------------------
            */

            $docente = $docente->fresh();


            return response()->json([
                'success' => true,
                'message' => 'Información personal actualizada correctamente.',
                'docente' => $docente,
                'data' => $docente,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son válidos.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar la información personal.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CREAR DOMICILIO
    |--------------------------------------------------------------------------
    */

    public function guardarDomicilio(Request $request)
    {
        try {

            $docente = $this->obtenerDocente();

            $validated = $request->validate([

                'pais' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'departamento' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'provincia' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'distrito' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'direccionExtranjera' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'tipoVia' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'nombreVia' => [
                    'nullable',
                    'string',
                    'max:150',
                ],

                'tipoInmueble' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'numeroInmueble' => [
                    'nullable',
                    'string',
                    'max:50',
                ],
            ]);


            $validated['docente_id'] = $docente->id;

            $domicilio = DocenteDomicilio::create($validated);


            return response()->json([
                'success' => true,
                'message' => 'Domicilio guardado correctamente.',
                'data' => $domicilio,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Los datos del domicilio no son válidos.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo guardar el domicilio.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR DOMICILIO
    |--------------------------------------------------------------------------
    */

    public function actualizarDomicilio(Request $request, $id)
    {
        try {

            $docente = $this->obtenerDocente();

            $domicilio = DocenteDomicilio::where('id', $id)
                ->where('docente_id', $docente->id)
                ->firstOrFail();


            $validated = $request->validate([

                'pais' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'departamento' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'provincia' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'distrito' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'direccionExtranjera' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'tipoVia' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'nombreVia' => [
                    'nullable',
                    'string',
                    'max:150',
                ],

                'tipoInmueble' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'numeroInmueble' => [
                    'nullable',
                    'string',
                    'max:50',
                ],
            ]);


            $domicilio->update($validated);

            $domicilio = $domicilio->fresh();


            return response()->json([
                'success' => true,
                'message' => 'Domicilio actualizado correctamente.',
                'data' => $domicilio,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Los datos del domicilio no son válidos.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar el domicilio.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ELIMINAR DOMICILIO
    |--------------------------------------------------------------------------
    */

    public function eliminarDomicilio($id)
    {
        try {

            $docente = $this->obtenerDocente();

            $domicilio = DocenteDomicilio::where('id', $id)
                ->where('docente_id', $docente->id)
                ->firstOrFail();


            $domicilio->delete();


            return response()->json([
                'success' => true,
                'message' => 'Domicilio eliminado correctamente.',
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar el domicilio.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CREAR FAMILIAR
    |--------------------------------------------------------------------------
    */

    public function guardarFamiliar(Request $request)
    {
        try {

            $docente = $this->obtenerDocente();

            $validated = $request->validate([

                'apellidosNombres' => [
                    'required',
                    'string',
                    'max:200',
                ],

                'parentesco' => [
                    'required',
                    'string',
                    'max:50',
                ],

                'fecNacimiento' => [
                    'nullable',
                    'date',
                ],

                'estadoCivil' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'nivelEstudios' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'ocupacion' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'celular' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'dependeEconomicamente' => [
                    'required',
                    Rule::in(['Si', 'No']),
                ],
            ]);


            $validated['docente_id'] = $docente->id;

            $familiar = DocenteFamiliar::create($validated);


            return response()->json([
                'success' => true,
                'message' => 'Familiar guardado correctamente.',
                'data' => $familiar,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Los datos del familiar no son válidos.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo guardar el familiar.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR FAMILIAR
    |--------------------------------------------------------------------------
    */

    public function actualizarFamiliar(Request $request, $id)
    {
        try {

            $docente = $this->obtenerDocente();

            $familiar = DocenteFamiliar::where('id', $id)
                ->where('docente_id', $docente->id)
                ->firstOrFail();


            $validated = $request->validate([

                'apellidosNombres' => [
                    'required',
                    'string',
                    'max:200',
                ],

                'parentesco' => [
                    'required',
                    'string',
                    'max:50',
                ],

                'fecNacimiento' => [
                    'nullable',
                    'date',
                ],

                'estadoCivil' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'nivelEstudios' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'ocupacion' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'celular' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'dependeEconomicamente' => [
                    'required',
                    Rule::in(['Si', 'No']),
                ],
            ]);


            $familiar->update($validated);

            $familiar = $familiar->fresh();


            return response()->json([
                'success' => true,
                'message' => 'Familiar actualizado correctamente.',
                'data' => $familiar,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Los datos del familiar no son válidos.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar el familiar.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ELIMINAR FAMILIAR
    |--------------------------------------------------------------------------
    */

    public function eliminarFamiliar($id)
    {
        try {

            $docente = $this->obtenerDocente();

            $familiar = DocenteFamiliar::where('id', $id)
                ->where('docente_id', $docente->id)
                ->firstOrFail();


            $familiar->delete();


            return response()->json([
                'success' => true,
                'message' => 'Familiar eliminado correctamente.',
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar el familiar.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CREAR FORMACIÓN PROFESIONAL
    |--------------------------------------------------------------------------
    */

    public function guardarFormacion(Request $request)
    {
        try {

            $docente = $this->obtenerDocente();

            $validated = $request->validate([

                'formacionProfesional' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'pais' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'universidad' => [
                    'required',
                    'string',
                    'max:200',
                ],

                'especialidad' => [
                    'nullable',
                    'string',
                    'max:200',
                ],

                'fecInicio' => [
                    'nullable',
                    'date',
                ],

                'fecFinal' => [
                    'nullable',
                    'date',
                    'after_or_equal:fecInicio',
                ],

                'gradoObtenido' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'observacion' => [
                    'nullable',
                    'string',
                ],

                'documento' => [
                    'nullable',
                    'file',
                    'mimes:pdf',
                    'max:5120',
                ],
            ]);


            $validated['docente_id'] = $docente->id;


            /*
            |--------------------------------------------------------------------------
            | GUARDAR PDF
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('documento')) {

                $ruta = $request
                    ->file('documento')
                    ->store('documentos/docentes', 'public');

                $validated['rutaDocumento'] = $ruta;
            }


            unset($validated['documento']);


            $formacion = DocenteFormacion::create($validated);


            return response()->json([
                'success' => true,
                'message' => 'Formación profesional guardada correctamente.',
                'data' => $formacion,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Los datos de formación no son válidos.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo guardar la formación.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR FORMACIÓN
    |--------------------------------------------------------------------------
    */

    public function actualizarFormacion(Request $request, $id)
    {
        try {

            $docente = $this->obtenerDocente();

            $formacion = DocenteFormacion::where('id', $id)
                ->where('docente_id', $docente->id)
                ->firstOrFail();


            $validated = $request->validate([

                'formacionProfesional' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'pais' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'universidad' => [
                    'required',
                    'string',
                    'max:200',
                ],

                'especialidad' => [
                    'nullable',
                    'string',
                    'max:200',
                ],

                'fecInicio' => [
                    'nullable',
                    'date',
                ],

                'fecFinal' => [
                    'nullable',
                    'date',
                    'after_or_equal:fecInicio',
                ],

                'gradoObtenido' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'observacion' => [
                    'nullable',
                    'string',
                ],

                'documento' => [
                    'nullable',
                    'file',
                    'mimes:pdf',
                    'max:5120',
                ],
            ]);


            /*
            |--------------------------------------------------------------------------
            | SI SE SUBE UN NUEVO PDF
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('documento')) {

                if ($formacion->rutaDocumento) {

                    Storage::disk('public')
                        ->delete($formacion->rutaDocumento);
                }

                $ruta = $request
                    ->file('documento')
                    ->store('documentos/docentes', 'public');

                $validated['rutaDocumento'] = $ruta;
            }


            unset($validated['documento']);


            $formacion->update($validated);

            $formacion = $formacion->fresh();


            return response()->json([
                'success' => true,
                'message' => 'Formación profesional actualizada correctamente.',
                'data' => $formacion,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Los datos de formación no son válidos.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar la formación.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ELIMINAR FORMACIÓN
    |--------------------------------------------------------------------------
    */

    public function eliminarFormacion($id)
    {
        try {

            $docente = $this->obtenerDocente();

            $formacion = DocenteFormacion::where('id', $id)
                ->where('docente_id', $docente->id)
                ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | ELIMINAR PDF FÍSICO
            |--------------------------------------------------------------------------
            */

            if ($formacion->rutaDocumento) {

                Storage::disk('public')
                    ->delete($formacion->rutaDocumento);
            }


            $formacion->delete();


            return response()->json([
                'success' => true,
                'message' => 'Formación eliminada correctamente.',
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar la formación.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

