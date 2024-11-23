<?php 

declare(strict_types=1);

namespace App\Helpers;

use Cache;

class CacheHelper implements CacheHelperInterface
{
    /**
     * Salva o valor de um item em cache pela chave.  
     * @param string $key Nome da chave.
     * @param string $value Valor a ser salvo.
     * @param string $drive Define o drive de cache. Padrao: 'apc'. Mais opcoes em config/cache.php
     * @return bool True se salvo com sucesso, do contrario, false.
     */
    public static function set(string $key, string $value, ?string $drive = 'apc'): bool
    {
        return Cache::store($drive)->put($key, $value);;
    }

    /**
     * Busca o valor de um item em cache pela chave.  
     * @param string $key Nome da chave.
     * @param string $drive Define o drive de cache. Padrao: 'apc'. Mais opcoes em config/cache.php
     * @return string|false O valor do item. False caso a chave nao exista.
     */
    public static function get(string $key, ?string $drive = 'apc')
    {
        return Cache::store($drive)->get($key, false);
    }

    /**
     * Remove o valor de um item em cache pela chave.  
     * @param string $key Nome da chave.
     * @param string $drive Define o drive de cache. Padrao: apc. Mais opcoes em config/cache.php
     * @return bool True se removido com sucesso, do contrario, false.
     */
    public static function remove(string $key, ?string $drive = 'apc'): bool
    {
        return Cache::store($drive)->forget($key);
    }
}
