<?php

namespace Jonasgn\LaravelCommons\Traits;

trait CanEnumBeFormatted
{
    /**
     * Retorna o nome do ENUM sem o caractere separador '_'
     */
    public function getFormattedName(): string
    {
        return static::_formatName($this->name);
    }

    /**
     * Retorna os nomes sem o caractere de separação `_`. Ideal para visualização externa
     */
    public static function getFormattedNames(): array
    {
        return array_map(
            fn($item) => static::_formatName($item->name),
            static::cases()
        );
    }

    private static function _formatName(string $value): string
    {
        return str_replace('_', ' ', $value);
    }
}
