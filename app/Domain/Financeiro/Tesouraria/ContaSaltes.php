<?php

namespace App\Domain\Financeiro\Tesouraria;

use DateTime;

class ContaSaltes
{
     /**
     * @var integer
     */
    private int $k13_conta;

    /**
     * @var integer
     */
    private int $k13_reduz;


    /**
     * @var string
     */
    private string $k13_descr;

    
    /**
     * @var float
     */
    private float $k13_saldo;

    /**
     * @var string
     */
    private string $k13_ident;

    /**
     * @var float
     */
    private float $k13_vlratu;

    /**
     * @var DateTime
     */
    private ?DateTime $k13_datvlr;

    /**
     * @var DateTime
     */
    private ?DateTime $k13_limite = null;

    /**
     * @var DateTime
     */
    private DateTime $k13_dtimplantacao;

  
    public function getK13Conta(): int
    {
        return $this->k13_conta;
    }
    
    /**
     * @param integer $k13_conta
     * @return self
     */
    public function setK13Conta(int $k13_conta): self
    {
        $this->k13_conta = $k13_conta;
        return $this;
    }

    public function getK13Reduz(): int
    {
        return $this->k13_reduz;
    }
    
    /**
     * @param string $k13_reduz
     * @return self
     */
    public function setK13Reduz(int $k13_reduz): self
    {
        $this->k13_reduz = $k13_reduz;
        return $this;
    }
    
    public function getK13Descr(): string
    {
        return $this->k13_descr;
    }
    
    /**
     * @param string $k13_descr
     * @return self
     */
    public function setK13Descr(string $k13_descr): self
    {
        $this->k13_descr = mb_convert_encoding($k13_descr,  "UTF-8", "ISO-8859-1");
        return $this;
    }
  
    public function getK13Saldo(): float
    {
        return $this->k13_saldo;
    }
    
    /**
     * @param string $k13_saldo
     * @return self
     */
    public function setK13Saldo(float $k13_saldo): self
    {
        $this->k13_saldo = $k13_saldo;
        return $this;
    }
    
    public function getK13Ident(): string
    {
        return $this->k13_ident;
    }
    
    /**
     * @param string $k13_ident
     * @return self
     */
    public function setK13Ident(string $k13_ident): self
    {
        $this->k13_ident = $k13_ident;
        return $this;
    }
    
    public function getK13Vlratu(): float
    {
        return $this->k13_vlratu;
    }
    
    /**
     * @param float $k13_vlratu
     * @return self
     */
    public function setK13Vlratu(float $k13_vlratu): self
    {
        $this->k13_vlratu = $k13_vlratu;
        return $this;
    }
    
    public function getK13Datvlr(): ?DateTime 
    {
        return $this->k13_datvlr;
    }
    
    /**
     * @param string $k13_datvlr
     * @return self
     */
    public function setK13Datvlr(?string $k13_datvlr): self
    {

        if ($k13_datvlr !== null) {
            $dateTime = DateTime::createFromFormat('Y-m-d', $k13_datvlr);
        } else {
            $dateTime = null;
        }
        
        $this->k13_datvlr = $dateTime;
        return $this;
    }
    
    public function getK13Limite(): ?DateTime
    {
        return $this->k13_limite;
    }
    
    /**
     * @param string $k13_limite
     * @return self
     */
    public function setK13Limite(?string $k13_limite): self
    {
       
        if ($k13_limite !== null && $k13_limite != '') {
            $dateTime = DateTime::createFromFormat('Y-m-d', $k13_limite);
        } else {
            $dateTime = null;
        }
;
        $this->k13_limite = $dateTime;
        return $this;
    }
    
    public function getK13Dtimplantacao(): ?DateTime
    {
        return $this->k13_dtimplantacao;
    }
    
    /**
     * @param string $k13_dtimplantacao
     * @return self
     */
    public function setK13Dtimplantacao(?string $k13_dtimplantacao): self
    {
      
        if ($k13_dtimplantacao !== null && $k13_dtimplantacao != '') {
            $dateTime = DateTime::createFromFormat('Y-m-d', $k13_dtimplantacao);
        } else {
            $dateTime = null;
        }
        
        $this->k13_dtimplantacao = $dateTime;
        return $this;
    }
    
}
