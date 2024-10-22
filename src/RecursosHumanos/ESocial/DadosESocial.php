<?php

namespace ECidade\RecursosHumanos\ESocial;

use ECidade\RecursosHumanos\ESocial\Model\Formulario\Preenchimentos;
use ECidade\RecursosHumanos\ESocial\Formatter\DadosPreenchimento as DadosPreenchimentoFormatter;
use ECidade\RecursosHumanos\ESocial\Model\Configuracao;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use Exception;

class DadosESocial
{
    private $tipo;
    private $dados = [];
    private $responsavelPreenchimento;

    public function setReponsavelPeloPreenchimento($responsavel)
    {
        $this->responsavelPreenchimento = $responsavel;
    }

    public function getPorTipo(
        $tipo,
        $matricula = null,
        $cgm = null,
        $tipoevento = null,
        $competencia = null,
        $dtpgto = null
    ) {
        $this->tipo = $tipo;
        if ($this->isTipoEvento($tipo)) {
            return $this->buscaPreenchimentos($matricula, $cgm, $tipoevento, $competencia, $dtpgto);
        }

        $preenchimentos = $this->buscaPreenchimentos($matricula, $cgm, $tipoevento, $competencia, $dtpgto);
        $this->buscaRespostas($preenchimentos);

        if ($tipo == Tipo::EMPREGADOR) {
            $this->buscaDadosEmpregador($preenchimentos);
        }

        return $preenchimentos;
    }

    private function isTipoEvento($tipo)
    {
        $tiposEvento = [
            Tipo::AFASTAMENTO_TEMPORARIO,
            Tipo::CADASTRAMENTO_INICIAL,
            Tipo::REMUNERACAO_TRABALHADOR,
            Tipo::RUBRICA,
            Tipo::REMUNERACAO_SERVIDOR,
            Tipo::DESLIGAMENTO,
            Tipo::CADASTRO_BENEFICIO,
            Tipo::ALTERACAODEDADOS,
            Tipo::CD_BENEF_IN,
            Tipo::BENEFICIOS_ENTESPUBLICOS,
            Tipo::PAGAMENTOS_RENDIMENTOS,
            Tipo::TSV_INICIO,
            Tipo::TSV_TERMINO,
            Tipo::ALTERACAO_CONTRATO,
            Tipo::REABERTURA_EVENTOS,
            Tipo::FECHAMENTO_EVENTOS,
        ];

        return in_array($tipo, $tiposEvento);
    }

    private function buscaPreenchimentos($matricula = null, $cgm = null, $tipoevento = null, $competencia = null, $dtpgto = null)
    {
        $configuracao = new Configuracao();
        $formularioId = $configuracao->getFormulario($this->tipo);
        $preenchimento = new Preenchimentos();
        $preenchimento->setReponsavelPeloPreenchimento($this->responsavelPreenchimento);

        switch ($this->tipo) {
            case Tipo::SERVIDOR:
                return $preenchimento->buscarUltimoPreenchimentoServidor($formularioId);
            case Tipo::EMPREGADOR:
                return $preenchimento->buscarUltimoPreenchimentoEmpregador($formularioId);
            case Tipo::LOTACAO_TRIBUTARIA:
                return $preenchimento->buscarUltimoPreenchimentoLotacao($formularioId);
            case Tipo::RUBRICA:
                return $preenchimento->buscarPreenchimentoS1010($formularioId, $matricula);
            case Tipo::CARGO:
            case Tipo::CARREIRA:
            case Tipo::FUNCAO:
            case Tipo::HORARIO:
            case Tipo::AMBIENTE:
            case Tipo::PROCESSOSAJ:
            case Tipo::PORTUARIO:
            case Tipo::ESTABELECIMENTOS:
            case Tipo::ALTERACAODEDADOS:
            case Tipo::ALTERACAO_CONTRATO:
                return $preenchimento->buscarPreenchimentoS2206($competencia, $formularioId, $matricula);
            case Tipo::TSV_ALT_CONTR:
                return $preenchimento->buscarUltimoPreenchimentoInstituicao($formularioId, $matricula);
            case Tipo::CADASTRAMENTO_INICIAL:
                return $preenchimento->buscarPreenchimentoS2200($competencia, $formularioId, $matricula);
            case Tipo::REMUNERACAO_TRABALHADOR:
                return $preenchimento->buscarPreenchimentoS1200($competencia, $formularioId, $matricula, $cgm, $tipoevento);
            case Tipo::REMUNERACAO_SERVIDOR:
                return $preenchimento->buscarPreenchimentoS1202($competencia, $formularioId, $matricula);
            case Tipo::BENEFICIOS_ENTESPUBLICOS:
                return $preenchimento->buscarPreenchimentoS1207($competencia, $formularioId, $matricula);
            case Tipo::PAGAMENTOS_RENDIMENTOS:
                return $preenchimento->buscarPreenchimentoS1210($competencia, $formularioId, $matricula, $cgm, $tipoevento, $dtpgto);
            case Tipo::FECHAMENTO_EVENTOS:
            case Tipo::REABERTURA_EVENTOS:
                return $preenchimento->buscarPreenchimentoS1299($competencia, $formularioId, $matricula, $cgm, $tipoevento);
            case Tipo::AFASTAMENTO_TEMPORARIO:
                return $preenchimento->buscarPreenchimentoS2230($competencia, $formularioId, $matricula);
            case Tipo::CADASTRO_BENEFICIO:
                return $preenchimento->buscarPreenchimentoS2410($competencia, $formularioId, $matricula);
            case Tipo::DESLIGAMENTO:
            case Tipo::TSV_INICIO:
            case Tipo::TSV_TERMINO:
            case Tipo::CD_BENEF_IN:
                return $preenchimento->buscarPreenchimento($competencia, $this->tipo, $matricula);
            default:
                throw new Exception('Tipo não encontrado.');
        }
    }

    private function buscaRespostas($preenchimentos)
    {
        $dadosPreenchimentoFormatter = new DadosPreenchimentoFormatter();
        foreach ($preenchimentos as $preenchimento) {
            $this->dados[] = $dadosPreenchimentoFormatter->formatar(
                $this->tipo,
                $this->identificaResponsavel($preenchimento),
                $preenchimento->inscricao_empregador,
                Preenchimentos::buscaRespostas($preenchimento->preenchimento)
            );
        }
    }

    private function buscaDadosEmpregador($preenchimentos)
    {
        // Implementar a lógica de busca dos dados do empregador
        // Exemplo: $this->repository->buscarDadosEmpregador($preenchimentos);
    }

    private function identificaResponsavel($preenchimento)
    {
        switch ($this->tipo) {
            case Tipo::SERVIDOR:
                return $preenchimento->matricula;
            case Tipo::EMPREGADOR:
                return $preenchimento->cgm;
            case Tipo::RUBRICA:
            case Tipo::LOTACAO_TRIBUTARIA:
            case Tipo::CARGO:
            case Tipo::CARREIRA:
            case Tipo::FUNCAO:
            case Tipo::HORARIO:
            case Tipo::AMBIENTE:
            case Tipo::PROCESSOSAJ:
            case Tipo::PORTUARIO:
            case Tipo::ESTABELECIMENTOS:
            case Tipo::ALTERACAODEDADOS:
            case Tipo::ALTERACAO_CONTRATO:
            case Tipo::TSV_INICIO:
            case Tipo::TSV_TERMINO:
            case Tipo::TSV_ALT_CONTR:
            case Tipo::CD_BENEF_IN:
            case Tipo::CADASTRAMENTO_INICIAL:
                return $preenchimento->pk;
            default:
                throw new Exception('Tipo não encontrado.');
        }
    }
}