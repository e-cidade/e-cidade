<?php

namespace App\Reports\Tributario\ISSQN\Redesim;

use App\Models\ISSQN\InscricaoRedesim;
use App\Repositories\Reports\FpdfBaseReports;
use DBDate;
use Illuminate\Database\Eloquent\Collection;

class RelatorioInscricoes extends FpdfBaseReports
{
    /**
     * @var string
     */
    protected string $dataInicio;
    /**
     * @var string
     */
    protected string $dataFim;
    /**
     * @var string
     */
    private string $descricaoRelatorio = "INSCRIÇÕES GERADAS A PARTIR DA REDESIM";

    /**
     * @var Collection<InscricaoRedesim>
     */
    private Collection $inscricoesRedesim;

    /**
     * @param string $dataInicio
     */
    public function setDataInicio(string $dataInicio)
    {
        $this->dataInicio = $dataInicio;
    }

    /**
     * @param string $dataFim
     */
    public function setDataFim(string $dataFim)
    {
        $this->dataFim = $dataFim;
    }

    /**
     * @param Collection<InscricaoRedesim> $inscricoesRedesim
     * @return RelatorioInscricoes
     */
    protected function setInscricoes(Collection $inscricoesRedesim): RelatorioInscricoes
    {
        $this->inscricoesRedesim = $inscricoesRedesim;
        return $this;
    }

    protected function build()
    {
        $this->globalVariables();
        $this->headerFile();
        $this->bodyFile();

        $this->generate();
    }

    private function globalVariables()
    {
        global $head2;
        $head2 = $this->descricaoRelatorio;

        global $head3;
        $head3 = sprintf(
            "Período: %s até %s",
            db_formatar($this->dataInicio, 'd'),
            db_formatar($this->dataFim, 'd')
        );
    }

    private function headerFile()
    {
        $this->Open();
        $this->AliasNbPages();
        $this->addpage("L");
        $this->setfillcolor(235);
        $this->setfont('arial', 'B', 9);

        $this->setfont('arial', 'B', 10);
        $this->setY(40);
        $this->setX(10);
        $this->Cell(279, 6, $this->descricaoRelatorio, 1, 0, "C", 1);
    }

    private function bodyFile()
    {
        $this->headerTable();
        $this->bodyTable();
    }

    private function headerTable()
    {
        $this->setfont('arial', 'B', 9);

        $this->setY(50);
        $this->setX(10);
        $this->Cell(35, 4, "Número da Inscrição", 1, 0, "C");

        $this->setY(50);
        $this->setX(45);
        $this->Cell(33, 4, "CNPJ", 1, 0, "C");

        $this->setY(50);
        $this->setX(78);
        $this->Cell(171, 4, "Nome / Razão Social", 1, 0, "C");

        $this->setY(50);
        $this->setX(249);
        $this->Cell(40, 4, "Data de inicio da empresa", 1, 0, "C");

        $this->setY(54);
        $this->setX(10);
        $this->Cell(139, 4, "Endereço", 1, 0, "C");

        $this->setY(54);
        $this->setX(149);
        $this->Cell(140, 4, "Atividade Principal", 1, 0, "C");
    }

    private function bodyTable()
    {
        $this->setfont('arial', '', 9);

        $altura = 62;

        /**
         * @var InscricaoRedesim $oInscricaoRedesim
         */
        foreach ($this->inscricoesRedesim as $oInscricaoRedesim) {
            $this->setY($altura);
            $this->setX(10);
            $this->Cell(35, 5, $oInscricaoRedesim->q179_inscricao, 1, 0, "C");

            $this->setY($altura);
            $this->setX(45);
            $this->Cell(33, 5, db_formatar($oInscricaoRedesim->issBase->cgm->z01_cgccpf, "cnpj"), 1, 0, "C");

            $this->setY($altura);
            $this->setX(78);
            $this->Cell(171, 5, substr($oInscricaoRedesim->issBase->cgm->z01_nome, 0, 25), 1, 0, "L");

            $this->setY($altura);
            $this->setX(249);
            $this->Cell(40, 5, DBDate::converter($oInscricaoRedesim->issBase->q02_dtinic), 1, 0, "C");

            $this->setY($altura + 5);
            $this->setX(10);
            $sTipoRua = trim($oInscricaoRedesim->j88_descricao);
            $sNomeRua = trim($oInscricaoRedesim->j14_nome);
            $sNumeroRua = trim($oInscricaoRedesim->q02_numero);
            $sNomeBairro = trim($oInscricaoRedesim->j13_descr);
            $sRua = "{$sTipoRua} {$sNomeRua}, {$sNumeroRua}, {$sNomeBairro}";
            $this->Cell(139, 5, substr($sRua, 0, 65), 1, 0, "L");

            $this->setY($altura + 5);
            $this->setX(149);
            $this->Cell(
                140,
                5,
                substr("{$oInscricaoRedesim->q03_ativ} - {$oInscricaoRedesim->q03_descr}", 0, 70),
                1,
                0,
                "L"
            );

            $altura += 5 + 9;
        }
    }
}
