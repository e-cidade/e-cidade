<?php

namespace App\Services\Tributario\ISSQN\Redesim;

use App\Models\ISSQN\InscricaoRedesim;
use App\Reports\Tributario\ISSQN\Redesim\RelatorioInscricoes;
use LogicException;

class RelatorioInscricoesService extends RelatorioInscricoes
{
    public function execute()
    {
        $this->getData();
        parent::build();
    }

    private function getData()
    {
        $this->validate();

        $aInscricoesRedesim = InscricaoRedesim::query()
            ->join('issbase', 'q02_inscr', 'q179_inscricao')
            ->join('cgm', 'z01_numcgm', 'q02_numcgm')
            ->join("ativprinc", "q88_inscr", "q179_inscricao")
            ->join("tabativ", function ($join) {
                $join->on("tabativ.q07_inscr", "ativprinc.q88_inscr");
                $join->on("tabativ.q07_seq", "ativprinc.q88_seq");
            })
            ->join("ativid", "q03_ativ", "q07_ativ")
            ->join("issruas", "issruas.q02_inscr", "q179_inscricao")
            ->join("ruas", "ruas.j14_codigo", "issruas.j14_codigo")
            ->leftJoin("ruastipo", "j88_codigo", "j14_tipo")
            ->join("issbairro", "q13_inscr", "q179_inscricao")
            ->join("bairro", "j13_codi", "q13_bairro")
            ->join('redesim_log', 'z01_cgccpf', 'q190_cpfcnpj')
            ->whereRaw("TO_CHAR(q190_data, 'YYYY-MM-DD') BETWEEN ? AND ?", [$this->dataInicio, $this->dataFim])
            ->get();

        $this->setInscricoes($aInscricoesRedesim);
    }

    private function validate()
    {
        if (empty($this->dataInicio) && empty($this->dataFim)) {
            throw new LogicException("Informe algum filtro.");
        }
    }
}
