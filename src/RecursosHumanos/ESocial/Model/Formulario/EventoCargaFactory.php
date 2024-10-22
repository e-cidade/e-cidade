<?php

namespace ECidade\RecursosHumanos\ESocial\Model\Formulario;

use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\EventoCargaInterface;

/**
 * Classe responsável por criar uma instância  específica do evento para
 * retorno dos dados da carga
 * @package ECidade\RecursosHumanos\ESocial\Model\Formulario
 */
class EventoCargaFactory
{
	/**
	 * @var ECidade\RecursosHumanos\ESocial\Model\Formulario\EventoCargaInterface
	 */
	private $eventoCarga;

	/**
	 * Cria uma instância de EventoCargaInterface específico
	 * @param $anoCompetencia int
	 * @param $mesCompetencia int
	 * @return ECidade\RecursosHumanos\ESocial\Model\Formulario\EventoCargaInterface
	 */
	public function __construct($tipo, $anoCompetencia, $mesCompetencia)
	{
		$eventoClassName = "ECidade\RecursosHumanos\ESocial\Model\Formulario\EventoCarga" . Tipo::getVinculacaoTipo($tipo);
		$this->eventoCarga = new $eventoClassName($anoCompetencia, $mesCompetencia);
	}

	/**
	 * Cria uma instancia de EventoCargaInterface específico
	 * @return ECidade\RecursosHumanos\ESocial\Model\Formulario\EventoCargaInterface
	 */
	public function getEvento()
	{
		return $this->eventoCarga;
	}
}