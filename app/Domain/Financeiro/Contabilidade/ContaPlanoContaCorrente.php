<?php

namespace App\Domain\Financeiro\Contabilidade;

class ContaPlanoContaCorrente
{
     /**
     * @var integer
     */
    private int $c18_sequencial;

     /**
     * @var integer
     */
    private int $c18_contacorrente;

     /**
     * @var integer
     */
    private int $c18_codcon;

      /**
     * @var integer
     */
    private int $c18_anousu;

    public function getC18Sequencial(): int
    {
        return $this->c18_sequencial;
    }

    public function setC18Sequencial(int $c18_sequencial): self
    {
        $this->c18_sequencial = $c18_sequencial;
        return $this;
    }

    public function getC18Contacorrente(): int
    {
        return $this->c18_contacorrente;
    }

    public function setC18Contacorrente(int $c18_contacorrente): self
    {
        $this->c18_contacorrente = $c18_contacorrente;
        return $this;
    }

    public function getC18Codcon(): int
    {
        return $this->c18_codcon;
    }

    public function setC18Codcon(int $c18_codcon): self
    {
        $this->c18_codcon = $c18_codcon;
        return $this;
    }

    public function getC18Anousu(): int
    {
        return $this->c18_anousu;
    }

    /**
     * @param int $c18_anousu
     * @return self
     */
    public function setC18Anousu(int $c18_anousu): self
    {
        $this->c18_anousu = $c18_anousu;
        return $this;
    }

}
