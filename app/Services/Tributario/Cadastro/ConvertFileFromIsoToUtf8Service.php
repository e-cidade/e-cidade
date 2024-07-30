<?php

namespace App\Services\Tributario\Cadastro;

use Exception;

class ConvertFileFromIsoToUtf8Service
{
    /**
     * Converts an ISO-8859-1 encoded file to UTF-8 and returns the path to the new file.
     * @param string $inputFile Path to the input ISO-8859-1 encoded file.
     * @return void
     * @throws Exception If the file cannot be read or written.
     */
    public function execute(string $inputFile): void
    {
        if (!file_exists($inputFile)) {
            throw new Exception("Input file does not exist.");
        }

        $tempOutputFile = tempnam(sys_get_temp_dir(), 'utf8_');

        $inputHandle = fopen($inputFile, 'r');
        if ($inputHandle === false) {
            throw new Exception("Unable to open input file for reading.");
        }

        $outputHandle = fopen($tempOutputFile, 'w');
        if ($outputHandle === false) {
            fclose($inputHandle);
            throw new Exception("Unable to open temporary output file for writing.");
        }

        while (($line = fgets($inputHandle)) !== false) {
            $utf8Line = iconv('ISO-8859-1', 'UTF-8//IGNORE', $line);
            if ($utf8Line === false) {
                fclose($inputHandle);
                fclose($outputHandle);
                unlink($tempOutputFile);
                throw new Exception("Error converting line to UTF-8.");
            }
            fwrite($outputHandle, $utf8Line);
        }

        fclose($inputHandle);
        fclose($outputHandle);

        $convertedContent = file_get_contents($tempOutputFile);
        if ($convertedContent === false) {
            throw new Exception("Unable to read the converted content from the temporary file.");
        }

        if (file_put_contents($inputFile, $convertedContent) === false) {
            throw new Exception("Unable to write the converted content back to the input file.");
        }

        unlink($tempOutputFile);
    }
}
