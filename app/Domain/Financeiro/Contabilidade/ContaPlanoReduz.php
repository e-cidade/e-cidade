<?php

namespace App\Domain\Financeiro\Contabilidade;

class ContaPlanoReduz
{
     /**
     * @var integer
     */
    private int $c61_codcon;

     /**
     * @var integer
     */
    private int $c61_anousu;

     /**
     * @var integer
     */
    private int $c61_reduz;

      /**
     * @var integer
     */
    private int $c61_instit;

     /**
     * @var integer
     */
    private int $c61_codigo;

     /**
     * @var integer
     */
    private int $c61_contrapartida;

     /**
     * @var integer
     */
    private int $c61_codtce;

    public function getC61Codcon(): int
    {
        return $this->c61_codcon;
    }

    public function setC61Codcon(int $c61_codcon): self
    {
        $this->c61_codcon = $c61_codcon;
        return $this;
    }

    public function getC61Anousu(): int
    {
        return $this->c61_anousu;
    }

    public function setC61Anousu(int $c61_anousu): self
    {
        $this->c61_anousu = $c61_anousu;
        return $this;
    }

    public function getC61Reduz(): int
    {
        return $this->c61_reduz;
    }

    public function setC61Reduz(int $c61_reduz): self
    {
        $this->c61_reduz = $c61_reduz;
        return $this;
    }

    public function getC61Instit(): int
    {
        return $this->c61_instit;
    }

    /**
     * @param int $c61_instit
     * @return self
     */
    public function setC61Instit(int $c61_instit): self
    {
        $this->c61_instit = mb_convert_encoding($c61_instit,  "UTF-8", "ISO-8859-1");
        return $this;
    }

    public function getC61Codigo(): int
    {
        return $this->c61_codigo;
    }

    public function setC61Codigo(int $c61_codigo): self
    {
        $this->c61_codigo = $c61_codigo;
        return $this;
    }

    public function getC61Contrapartida(): int
    {
        return $this->c61_contrapartida;
    }

    public function setC61Contrapartida(int $c61_contrapartida): self
    {
        $this->c61_contrapartida = $c61_contrapartida;
        return $this;
    }

    public function getC61Codtce(): ?int
    {
        return $this->c61_codtce ?: null;
    }

    public function setC61Codtce(int $c61_codtce): self
    {
        $this->c61_codtce = $c61_codtce;
        return $this;
    }

}
