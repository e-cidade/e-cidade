<?php

namespace App\Helpers;

class StringHelper {
    
    /**
     * Converte uma string de ISO-8859-1 para UTF-8.
     *
     * Esse mйtodo utiliza `mb_convert_encoding` para converter a codificaзгo
     * de uma string de ISO-8859-1 para UTF-8, o que й ъtil para padronizar
     * strings em UTF-8 em casos onde o texto estб em uma codificaзгo diferente.
     *
     * @param string $str A string em ISO-8859-1 que serб convertida.
     * @return string A string convertida para UTF-8.
     */
    public static function toUtf8($str)
    {
        return mb_convert_encoding($str, 'UTF-8', 'ISO-8859-1');
    }

    public static function formatCnpjCpf($value) {
        $CPF_LENGTH = 11;
        $cnpj_cpf = preg_replace("/\D/", '', $value);
        
        if (strlen($cnpj_cpf) === $CPF_LENGTH) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
        } 
        
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }
}
