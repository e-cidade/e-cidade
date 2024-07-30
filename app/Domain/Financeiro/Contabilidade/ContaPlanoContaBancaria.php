<?php

namespace App\Domain\Financeiro\Contabilidade;

class ContaPlanoContaBancaria
{
     /**
     * @var integer
     */
    private int $c56_sequencial;

     /**
     * @var integer
     */
    private int $c56_contabancaria;

     /**
     * @var integer
     */
    private int $c56_codcon;

      /**
     * @var integer
     */
    private int $c56_anousu;

    public function getC56Sequencial(): int
    {
        return $this->c56_sequencial;
    }

    public function setC56Sequencial(int $c56_sequencial): self
    {
        $this->c56_sequencial = $c56_sequencial;
        return $this;
    }

    public function getC56Contabancaria(): int
    {
        return $this->c56_contabancaria;
    }

    public function setC56Contabancaria(int $c56_contabancaria): self
    {
        $this->c56_contabancaria = $c56_contabancaria;
        return $this;
    }

    public function getC56Codcon(): int
    {
        return $this->c56_codcon;
    }

    public function setC56Codcon(int $c56_codcon): self
    {
        $this->c56_codcon = $c56_codcon;
        return $this;
    }

    public function getC56Anousu(): int
    {
        return $this->c56_anousu;
    }

    /**
     * @param int $c56_anousu
     * @return self
     */
    public function setC56Anousu(int $c56_anousu): self
    {
        $this->c56_anousu = $c56_anousu;
        return $this;
    }

}
