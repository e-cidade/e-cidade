<?php

namespace App\Helpers;

class FileHelper {

    /**
     * Substitui caracteres acentuados por seus equivalentes n�o acentuados.
     *
     * @param string $str String com caracteres acentuados.
     * @return string String com caracteres acentuados substitu�dos.
     */
    public static function replaceSpecialChars($str) {
        $mapaAcentos = array(
            '�' => 'a', '�' => 'a', '�' => 'a', '�' => 'a',
            '�' => 'e', '�' => 'e', '�' => 'e',
            '�' => 'i', '�' => 'i',
            '�' => 'o', '�' => 'o', '�' => 'o', '�' => 'o',
            '�' => 'u',
            '�' => 'c',
            '�' => 'A', '�' => 'A', '�' => 'A', '�' => 'A',
            '�' => 'E', '�' => 'E', '�' => 'E',
            '�' => 'I', '�' => 'I',
            '�' => 'O', '�' => 'O', '�' => 'O', '�' => 'O',
            '�' => 'U',
            '�' => 'C'
        );

        return strtr($str, $mapaAcentos);
    }

    /**
     * Sanitiza o nome do arquivo, removendo caracteres n�o permitidos.
     *
     * @param string $fileName Nome do arquivo para sanitiza��o.
     * @return string Nome do arquivo sanitizado.
     */
    public static function sanitizeFileName($fileName) {
        return preg_replace('/[^a-zA-Z0-9\s.-]/', '', $fileName);
    }
}
