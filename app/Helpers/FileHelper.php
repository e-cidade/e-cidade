<?php

namespace App\Helpers;

class FileHelper {

    /**
     * Substitui caracteres acentuados por seus equivalentes não acentuados.
     *
     * @param string $str String com caracteres acentuados.
     * @return string String com caracteres acentuados substituídos.
     */
    public static function replaceSpecialChars($str) {
        $mapaAcentos = array(
            'á' => 'a', 'à' => 'a', 'â' => 'a', 'ã' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e',
            'í' => 'i', 'ì' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ú' => 'u',
            'ç' => 'c',
            'Á' => 'A', 'À' => 'A', 'Â' => 'A', 'Ã' => 'A',
            'É' => 'E', 'È' => 'E', 'Ê' => 'E',
            'Í' => 'I', 'Ì' => 'I',
            'Ó' => 'O', 'Ò' => 'O', 'Ô' => 'O', 'Õ' => 'O',
            'Ú' => 'U',
            'Ç' => 'C'
        );

        return strtr($str, $mapaAcentos);
    }

    /**
     * Sanitiza o nome do arquivo, removendo caracteres não permitidos.
     *
     * @param string $fileName Nome do arquivo para sanitização.
     * @return string Nome do arquivo sanitizado.
     */
    public static function sanitizeFileName($fileName) {
        return preg_replace('/[^a-zA-Z0-9\s.-]/', '', $fileName);
    }
}
