<?php

namespace App\Repositories\Reports;

require_once 'fpdf151/pdf.php';

use App\Repositories\Contracts\Reports\IReports;
use PDF;

class FpdfBaseReports extends PDF implements IReports
{

    private string $file;
    private bool $showFile = false;
    private bool $base64Format = false;

    public function __construct($orientation='P',$unit='mm',$format='A4')
    {
        parent::FPDF($orientation='P',$unit='mm',$format='A4');
        $this->SetAutoPageBreak(false);
    }

    /**
     * @param bool $showFile
     * @return FpdfBaseReports
     */
    public function setShowFile(bool $showFile): FpdfBaseReports
    {
        $this->showFile = $showFile;
        return $this;
    }

    /**
     * @param bool $base64Format
     * @return FpdfBaseReports
     */
    public function setBase64Format(bool $base64Format): FpdfBaseReports
    {
        $this->base64Format = $base64Format;
        return $this;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function setFile(string $file): IReports
    {
        $this->file = $file;
        return $this;
    }

    public function generate(): void
    {
        $fileName = md5(uniqid().date("Y-m-d H:i:s")).".pdf";
        $filePath = ECIDADE_PATH."tmp/{$fileName}";

        if ($this->showFile) {
            $this->Output($filePath);
        }

        $this->Output($filePath, false, true);

        $file = ECIDADE_REQUEST_PATH."tmp/{$fileName}";

        if ($this->base64Format) {
            $file = base64_encode(file_get_contents($filePath));

            unlink($filePath);
        }

        $this->setFile($file);
    }
}
