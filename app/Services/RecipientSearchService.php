<?php

namespace App\Services;

use App\Models\Docente;
use App\Models\Alumno;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RecipientSearchService
{
    /**
     * Busca receptores entre las 3 tablas (docentes, alumnos, usuarios)
     * en ese estricto orden de prioridad.
     * Compatible con PostgreSQL (Supabase) y MySQL.
     * Limita el número de resultados encontrados por grupo para no saturar la vista.
     *
     * @param string|null $term Término de búsqueda
     * @param int $limitPerGroup Límite máximo de resultados por grupo (por defecto 10)
     * @return array Resultados agrupados y priorizados
     */
    public function search(?string $term = null, int $limitPerGroup = 10): array
    {
        $term = trim((string)$term);
        $currentUserId = auth()->id();
        $currentUserEmail = strtolower((string)(auth()->user()?->email ?? ''));

        // Operador insensible a mayúsculas/minúsculas según el gestor
        $operator = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';

        $results = [
            'docentes' => [],
            'alumnos'  => [],
            'usuarios' => [],
        ];

        // Rastrear correos ya incluidos para evitar duplicados entre tablas y excluir al usuario actual
        $seenEmails = [];
        if (!empty($currentUserEmail)) {
            $seenEmails[$currentUserEmail] = true;
        }

        // =====================================================================
        // PRIORIDAD 1: TABLA DOCENTES
        // =====================================================================
        $docApePat = Schema::hasColumn('docentes', 'apepaterno') ? 'apepaterno' : 'apePaterno';
        $docApeMat = Schema::hasColumn('docentes', 'apematerno') ? 'apematerno' : 'apeMaterno';
        $docCod    = Schema::hasColumn('docentes', 'coddocente') ? 'coddocente' : 'codDocente';

        $docentesQuery = Docente::query()->where('estado', '1');

        if ($term !== '') {
            $docentesQuery->where(function ($q) use ($term, $operator, $docApePat, $docApeMat, $docCod) {
                $q->where('nombres', $operator, "%{$term}%")
                  ->orWhere($docApePat, $operator, "%{$term}%")
                  ->orWhere($docApeMat, $operator, "%{$term}%")
                  ->orWhere($docCod, $operator, "%{$term}%")
                  ->orWhere('dni', $operator, "%{$term}%")
                  ->orWhere('email', $operator, "%{$term}%");
            });
        }

        $docentes = $docentesQuery->orderBy($docApePat)
            ->orderBy('nombres')
            ->take($limitPerGroup)
            ->get();

        foreach ($docentes as $docente) {
            $email = trim((string)($docente->email ?? ''));
            $codDocente = $docente->{$docCod} ?? $docente->codDocente ?? 'DOCENTE';

            if (empty($email)) {
                $email = strtolower($codDocente) . '@uni.edu.pe';
            }
            $emailLower = strtolower($email);

            if (isset($seenEmails[$emailLower])) {
                continue;
            }

            $apePat = $docente->{$docApePat} ?? $docente->apePaterno ?? '';
            $apeMat = $docente->{$docApeMat} ?? $docente->apeMaterno ?? '';
            $nombres = $docente->nombres ?? '';

            $fullName = trim("{$apePat} {$apeMat}, {$nombres}");
            $user = $this->resolveUserForEmail($email, $fullName);

            if ($user && $user->id !== $currentUserId) {
                $seenEmails[$emailLower] = true;
                $results['docentes'][] = [
                    'id'       => $user->id,
                    'text'     => "[Docente] {$fullName} ({$email}) - Cód: {$codDocente}",
                    'name'     => $fullName,
                    'email'    => $email,
                    'category' => 'Docente',
                ];
            }
        }

        // =====================================================================
        // PRIORIDAD 2: TABLA ALUMNOS
        // =====================================================================
        $aluApePat = Schema::hasColumn('alumnos', 'apepaterno') ? 'apepaterno' : 'apePaterno';
        $aluApeMat = Schema::hasColumn('alumnos', 'apematerno') ? 'apematerno' : 'apeMaterno';
        $aluCod    = Schema::hasColumn('alumnos', 'codalumno') ? 'codalumno' : 'codAlumno';

        $alumnosQuery = Alumno::query();

        if ($term !== '') {
            $alumnosQuery->where(function ($q) use ($term, $operator, $aluApePat, $aluApeMat, $aluCod) {
                $q->where('nombres', $operator, "%{$term}%")
                  ->orWhere($aluApePat, $operator, "%{$term}%")
                  ->orWhere($aluApeMat, $operator, "%{$term}%")
                  ->orWhere($aluCod, $operator, "%{$term}%")
                  ->orWhere('email', $operator, "%{$term}%");
            });
        }

        $alumnos = $alumnosQuery->orderBy($aluApePat)
            ->orderBy('nombres')
            ->take($limitPerGroup)
            ->get();

        foreach ($alumnos as $alumno) {
            $email = trim((string)($alumno->email ?? ''));
            $codAlumno = $alumno->{$aluCod} ?? $alumno->codAlumno ?? 'ALUMNO';

            if (empty($email)) {
                $email = strtolower($codAlumno) . '@uni.pe';
            }
            $emailLower = strtolower($email);

            if (isset($seenEmails[$emailLower])) {
                continue;
            }

            $apePat = $alumno->{$aluApePat} ?? $alumno->apePaterno ?? '';
            $apeMat = $alumno->{$aluApeMat} ?? $alumno->apeMaterno ?? '';
            $nombres = $alumno->nombres ?? '';

            $fullName = trim("{$apePat} {$apeMat}, {$nombres}");
            $user = $this->resolveUserForEmail($email, $fullName);

            if ($user && $user->id !== $currentUserId) {
                $seenEmails[$emailLower] = true;
                $results['alumnos'][] = [
                    'id'       => $user->id,
                    'text'     => "[Alumno] {$fullName} ({$email}) - Cód: {$codAlumno}",
                    'name'     => $fullName,
                    'email'    => $email,
                    'category' => 'Alumno',
                ];
            }
        }

        // =====================================================================
        // PRIORIDAD 3: TABLA USUARIOS (USERS)
        // =====================================================================
        $usersQuery = User::query();

        if ($currentUserId) {
            $usersQuery->where('id', '!=', $currentUserId);
        }

        if ($term !== '') {
            $usersQuery->where(function ($q) use ($term, $operator) {
                $q->where('name', $operator, "%{$term}%")
                  ->orWhere('email', $operator, "%{$term}%");
            });
        }

        $users = $usersQuery->orderBy('name')
            ->take($limitPerGroup)
            ->get();

        foreach ($users as $user) {
            $emailLower = strtolower(trim((string)$user->email));

            if (isset($seenEmails[$emailLower])) {
                continue;
            }

            $seenEmails[$emailLower] = true;
            $results['usuarios'][] = [
                'id'       => $user->id,
                'text'     => "[Usuario] {$user->name} ({$user->email})",
                'name'     => $user->name,
                'email'    => $user->email,
                'category' => 'Usuario',
            ];
        }

        return $results;
    }

    /**
     * Formatea los resultados agrupados para el plugin Select2 (con optgroups)
     */
    public function formatForSelect2(array $groupedResults): array
    {
        $select2Results = [];

        if (!empty($groupedResults['docentes'])) {
            $select2Results[] = [
                'text'     => 'Docentes (Prioridad 1)',
                'children' => array_values($groupedResults['docentes']),
            ];
        }

        if (!empty($groupedResults['alumnos'])) {
            $select2Results[] = [
                'text'     => 'Alumnos (Prioridad 2)',
                'children' => array_values($groupedResults['alumnos']),
            ];
        }

        if (!empty($groupedResults['usuarios'])) {
            $select2Results[] = [
                'text'     => 'Usuarios del Sistema (Prioridad 3)',
                'children' => array_values($groupedResults['usuarios']),
            ];
        }

        return $select2Results;
    }

    /**
     * Obtiene o crea la cuenta de usuario ligada al correo para asegurar
     * integridad referencial de clave foránea en la tabla messages.
     */
    public function resolveUserForEmail(string $email, string $name): User
    {
        return User::firstOrCreate(
            ['email' => $email],
            [
                'name'     => $name,
                'password' => Hash::make(Str::random(16)),
            ]
        );
    }
}
