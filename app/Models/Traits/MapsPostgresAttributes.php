<?php

namespace App\Models\Traits;

trait MapsPostgresAttributes
{
    /**
     * Normaliza los atributos para compatibilidad automática entre
     * nombres camelCase del frontend y columnas en minúsculas de PostgreSQL.
     */
    public function fill(array $attributes)
    {
        $normalized = [];
        $fillableList = array_map('strtolower', $this->getFillable());

        foreach ($attributes as $key => $value) {
            $lower = strtolower($key);
            if (in_array($lower, $fillableList, true)) {
                $normalized[$lower] = $value;
            } else {
                $normalized[$key] = $value;
            }
        }

        return parent::fill($normalized);
    }

    /**
     * Permite acceder a columnas en minúsculas usando camelCase (ej: $model->apePaterno -> apepaterno).
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if ($value === null) {
            $lower = strtolower($key);
            if ($lower !== $key && array_key_exists($lower, $this->attributes)) {
                return parent::getAttribute($lower);
            }
        }

        return $value;
    }

    /**
     * Permite asignar atributos usando camelCase y los guarda en minúsculas en PostgreSQL.
     */
    public function setAttribute($key, $value)
    {
        $lower = strtolower($key);
        $fillableList = array_map('strtolower', $this->getFillable());

        if ($lower !== $key && in_array($lower, $fillableList, true)) {
            $key = $lower;
        }

        return parent::setAttribute($key, $value);
    }
}

