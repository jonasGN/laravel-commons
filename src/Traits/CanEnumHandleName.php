<?php

namespace Jonasgn\LaravelCommons\Traits;

use InvalidArgumentException;

/**
 * Contém métodos para lidar com o mapeamento de enum a partir do nome do mesmo
 */
trait CanEnumHandleName
{
    /**
     * Recupera o enumerador a partir do nome do mesmo.
     * Caso não encontre, lança um erro
     */
    public static function fromName(string $name): static
    {
        foreach (self::cases() as $case) {
            if ($case->name === strtoupper($name)) {
                return $case;
            }
        }
        throw new InvalidArgumentException("O nome informado não corresponde a um valor de enum válido");
    }

    /**
     * Recupera o enumerador a partir do nome do mesmo.
     * Caso não encontre, retorna `nulo`
     */
    public static function tryFromName(string $name): ?static
    {
        foreach (self::cases() as $case) {
            if ($case->name === strtoupper($name)) {
                return $case;
            }
        }
        return null;
    }
}
