<?php

namespace ECidade\RecursosHumanos\ESocial\Model\Formulario;

/**
 * Interface respons�vel por definir classes de eventoCarga
 * @package ECidade\RecursosHumanos\ESocial\Model\Formulario
 */
interface EventoCargaInterface
{
   /**
    * Executa o sql da carga
    * @param integer|null $matricula
    * @return resource
    */
   public function execute($matricula = null, $ano, $mes);
}