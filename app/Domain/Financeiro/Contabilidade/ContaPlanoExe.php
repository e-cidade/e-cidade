<?php

namespace App\Domain\Financeiro\Contabilidade;

class ContaPlanoExe
{
     /**
     * @var integer
     */
    private int $c62_anousu;

     /**
     * @var integer
     */
    private int $c62_reduz;

     /**
     * @var integer
     */
    private int $c62_codrec;

      /**
     * @var float
     */
    private float $c62_vlrcre;

     /**
     * @var float
     */
    private float $c62_vlrdeb;

    public function getC62Anousu(): int
    {
        return $this->c62_anousu;
    }

    public function setC62Anousu(int $c62_anousu): self
    {
        $this->c62_anousu = $c62_anousu;
        return $this;
    }

    public function getC62Reduz(): int
    {
        return $this->c62_reduz;
    }

    public function setC62Reduz(int $c62_reduz): self
    {
        $this->c62_reduz = $c62_reduz;
        return $this;
    }

    public function getC62Codrec(): int
    {
        return $this->c62_codrec;
    }

    public function setC62Codrec(int $c62_codrec): self
    {
        $this->c62_codrec = $c62_codrec;
        return $this;
    }

    public function getC62Vlrcre(): float
    {
        return $this->c62_vlrcre;
    }

    /**
     * @param float $c62_vlrcre
     * @return self
     */
    public function setC62Vlrcre(float $c62_vlrcre): self
    {
        $this->c62_vlrcre = $c62_vlrcre;
        return $this;
    }

    public function getC62Vlrdeb(): float
    {
        return $this->c62_vlrdeb;
    }

    public function setC62Vlrdeb(float $c62_vlrdeb): self
    {
        $this->c62_vlrdeb = $c62_vlrdeb;
        return $this;
    }

}
