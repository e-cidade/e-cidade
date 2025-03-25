<?php

namespace App\Models\Sicom;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class AdesaoRegPrecos extends LegacyModel
{
  use LegacyAccount;

  public $timestamps = false;

  protected $table = 'sicom.adesaoregprecos';

  protected $primaryKey = 'si06_sequencial';

  public $incrementing = false;

  protected string $sequenceName = 'sic_adesaoregprecos_si06_sequencial_seq';

  protected $fillable = [
    'si06_sequencial',
    'si06_orgaogerenciador',
    'si06_modalidade',
    'si06_numeroprc',
    'si06_numlicitacao',
    'si06_dataadesao',
    'si06_dataata',
    'si06_datavalidade',
    'si06_publicacaoaviso',
    'si06_objetoadesao',
    'si06_orgarparticipante',
    'si06_cgm',
    'si06_descontotabela',
    'si06_numeroadm',
    'si06_dataabertura',
    'si06_processocompra',
    'si06_fornecedor',
    'si06_tipodocumento',
    'si06_numerodocumento',
    'si06_processoporlote',
    'si06_instit',
    'si06_anoproc',
    'si06_edital',
    'si06_cadinicial',
    'si06_exercicioedital',
    'si06_anocadastro',
    'si06_leidalicitacao',
    'si06_anomodadm',
    'si06_nummodadm',
    'si06_departamento',
    'si06_codunidadesubant',
    'si06_regimecontratacao',
    'si06_criterioadjudicacao',
    'si06_statusenviosicom',
    'si06_descrcriterioutilizado'
  ];

}
