<?php
$altura = 3.5;
$this->objpdf->AddPage();
//$this->objpdf->settopmargin(5);
$iY1 = 16;
$iY2 = 19;
$iY3 = 25;

$iPreencheFundo = 1;
for ($i = 0; $i < 2; $i++){


   if ( $i == 1 ){
      $iY1 =163;
      $iY2 =166;
      $iY3 =172;
   }

   if (!$this->lLiberado) {

     $this->objpdf->SetFont('Arial','B',78);
     $this->objpdf->SetFillColor(178);
     $this->objpdf->TextWithRotation(12,$iY1+105,"N�O LIBERADA",20,0);
     $this->objpdf->SetFillColor(235);
     $iPreencheFundo = 0;
   }

   $this->objpdf->SetFillColor(235);
   $y = $this->objpdf->gety() - 2;
   $this->objpdf->Image('imagens/files/'.$this->logoitbi,10,$y,14);
   $this->objpdf->SetFont('Arial','B',10);
   $this->objpdf->setx(30);
   $this->objpdf->Cell(100,3,$this->nomeinst,0,1,"L",0);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->setx(30);
   $this->objpdf->Cell(100,3,'Imposto Sobre Transmiss�o de Bens Im�veis (ITBI)',0,0,"L",0);
   $this->objpdf->SetFont('Arial','B',12);
   $this->objpdf->cell(100,3,'Vencimento : '.db_formatar($this->datavencimento,'d'),0,0,"L",0);
   $this->objpdf->SetFont('Arial','',8);

   $this->objpdf->sety($iY1);
   $this->objpdf->setx(30);
   $this->objpdf->MultiCell(100,3,'Tipo de Transmiss�o : '.$this->it04_descr,0,'L',0,0);
   $this->objpdf->sety($iY1);
   $this->objpdf->setx(130);
   $this->objpdf->cell(50,3,'Recibo Emitido em: '.db_formatar($this->dataemissao,'d'),0,1,"L",0);

   $this->objpdf->sety($iY2);
   $this->objpdf->setx(30);
   $this->objpdf->Cell(100,3,'',0,0,"L",0);
   $this->objpdf->cell(50,3,'C�digo de Arrecada��o : '.$this->numpreitbi,0,1,"L",0);

   $this->objpdf->sety($iY3);
   $this->objpdf->setx(30);
   $this->objpdf->SetFont('Arial','B',10);
   $this->objpdf->Cell(100,3,'Guia de Recolhimento N'.chr(176).' SMF/'.db_formatar($this->itbi,'s','0',5).'/'.$this->ano,0,0,"L",0);

   if ($this->tipoitbi == "urbano") {
     $this->objpdf->Cell(50,5,"ITBI URBANO",0,1,"C",0);
   } else {
     $this->objpdf->Cell(50,5,"ITBI RURAL",0,1,"C",0);
   }

   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(20,$altura,'',1,0,"C",$iPreencheFundo);
   $this->objpdf->cell(80,$altura,'Identifica��o do Transmitente',1,0,"C",$iPreencheFundo);
   $this->objpdf->cell(97,$altura,'Identifica��o do Adquirente',1,1,"C",$iPreencheFundo);
   $this->objpdf->cell(20,$altura,'Nome : ',1,0,"L",0);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(80,$altura,$this->z01_nome.$this->outrostransmitentes,1,0,"L",0);    //nome do transmitente

   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(97,$altura,$this->nomecompprinc.$this->outroscompradores,1,1,"L",0);   //nome do comprador
   $this->objpdf->cell(20,$altura,'CNPJ/CPF:',1,0,"L",0);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(30,$altura,$this->z01_cgccpf,1,0,"L",0);

   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(20,$altura,'Fone:',1,0,"L",0);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(30,$altura,$this->fonetransmitente ,1,0,"L",0);
   $this->objpdf->cell(35,$altura,$this->cgccpfcomprador,1,0,"L",0);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(30,$altura,'Fone:',1,0,"L",0);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(32,$altura,$this->fonecomprador   ,1,1,"L",0);

   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(20,$altura,'Endere�o : ',1,0,"L",0);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(80,$altura,substr($this->z01_ender.' - '.$this->z01_bairro,0,46) ,1,0,"L",0);
   $this->objpdf->cell(97,$altura,substr($this->enderecocomprador.','.$this->numerocomprador.' / '.$this->complcomprador,0,50),1,1,"L",0);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(20,$altura,'Munic�pio : ',1,0,"L",0);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(80,$altura,$this->z01_munic.'('.$this->z01_uf.') - CEP: '.$this->z01_cep ,1,0,"L",0);
   $this->objpdf->cell(97,$altura,$this->municipiocomprador.'('.$this->ufcomprador.') - CEP: '.$this->cepcomprador . ' - BAIRRO: '.$this->bairrocomprador ,1,1,"L",0);



   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(20,$altura,'E-mail: ',1,0,"L",0);

   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(80,$altura,$this->mailtransmitente,1,0,"L",0);

   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(97,$altura,$this->mailcomprador,1,0,"L",0);




   $this->objpdf->Ln(5);

   $this->objpdf->setfillcolor(0);
   $this->objpdf->SetFont('Arial','',4);
   $this->objpdf->TextWithDirection(9.5,116,"Inclu�do por: ".$this->usuarioNomeIncluido,'U');

   if (isset($this->usuarioNomeLiberado) && $this->usuarioNomeLiberado != ""){
     $this->objpdf->TextWithDirection(9.5,78,"Liberado por: ".$this->usuarioNomeLiberado,'U');
   }

   $this->objpdf->setfillcolor(235);


   $this->objpdf->SetFont('Arial','B',8);

   if ($this->tipoitbi == "urbano") {
     $this->objpdf->cell(88,$altura,'Dados do Im�vel',1,0,"C",$iPreencheFundo);
   } else {
     $this->objpdf->cell(88,$altura,'Dados da Terra',1,0,"C",$iPreencheFundo);
   }
   $this->objpdf->cell(2,$altura,'',0,0,"C",0);

   if ($this->tipoitbi == "urbano"){
     $this->objpdf->cell(107,$altura,'Dados das Constru��es',1,1,"C",$iPreencheFundo);
   } else {
     $this->objpdf->cell(107,$altura,'Dados das Benfeitorias',1,1,"C",$iPreencheFundo);
   }

   $this->objpdf->SetFont('Arial','',8);
   $y = $this->objpdf->gety();

   if ($this->tipoitbi == "urbano"){

   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(35,$altura,'Matr�cula da Prefeitura: ',1,0,"L",$iPreencheFundo);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(13,$altura,@$this->it06_matric,1,0,"L",0);

   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(30,$altura,'N�mero do im�vel: ',1,0,"L",$iPreencheFundo);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(10,$altura,@$this->j39_numero,1,1,"L",0);

   if (@$this->db21_usadistritounidade == 't') {


       $this->objpdf->SetFont('Arial','B',8);
       $this->objpdf->cell(9,$altura,'Dist.: ',1,0,"L",$iPreencheFundo);
       $this->objpdf->SetFont('Arial','',8);
       $this->objpdf->cell(6,$altura,@$this->j34_distrito,1,0,"L",0);

       $this->objpdf->SetFont('Arial','B',8);
       $this->objpdf->cell(9,$altura,'Set.: ',1,0,"L",$iPreencheFundo);
       $this->objpdf->SetFont('Arial','',8);
       $this->objpdf->cell(10,$altura,@$this->j34_setor,1,0,"L",0);

       $this->objpdf->SetFont('Arial','B',8);
       $this->objpdf->cell(11,$altura,'Quad.: ',1,0,"L",$iPreencheFundo);
       $this->objpdf->SetFont('Arial','',8);
       $this->objpdf->cell(10,$altura,@$this->j34_quadra,1,0,"L",0);

       $this->objpdf->SetFont('Arial','B',8);
       $this->objpdf->cell(8,$altura,'Lot.: ',1,0,"L",$iPreencheFundo);
       $this->objpdf->SetFont('Arial','',8);
       $this->objpdf->cell(10,$altura,(@$this->matriz == ""?$this->j34_lote:@$this->matriz),1,0,"L",0);

       $this->objpdf->SetFont('Arial','B',8);
       $this->objpdf->cell(8,$altura,'Uni.: ',1,0,"L",$iPreencheFundo);
       $this->objpdf->SetFont('Arial','',8);
       $this->objpdf->cell(7,$altura,@$this->j01_unidade,1,1,"L",0);


   } else {

       $this->objpdf->SetFont('Arial','B',8);
       $this->objpdf->cell(15,$altura,'Setor : ',1,0,"L",$iPreencheFundo);
       $this->objpdf->SetFont('Arial','',8);
       $this->objpdf->cell(14,$altura,@$this->j34_setor,1,0,"L",0);
       $this->objpdf->SetFont('Arial','B',8);

       $this->objpdf->cell(15,$altura,'Quadra : ',1,0,"L",$iPreencheFundo);
       $this->objpdf->SetFont('Arial','',8);
       $this->objpdf->cell(14,$altura,@$this->j34_quadra,1,0,"L",0);
       $this->objpdf->SetFont('Arial','B',8);

       $this->objpdf->cell(15,$altura,'Lote: ',1,0,"L",$iPreencheFundo);
       $this->objpdf->SetFont('Arial','',8);
       $this->objpdf->cell(15,$altura,(@$this->matriz == ""?$this->j34_lote:@$this->matriz),1,1,"L",0);

   }


   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(15,$altura,'Matric RI : ',1,0,"L",$iPreencheFundo);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(14,$altura,$this->it22_matricri,1,0,"L",0);
   $this->objpdf->SetFont('Arial','B',8);

   $this->objpdf->cell(15,$altura,'Quadra : ',1,0,"L",$iPreencheFundo);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(14,$altura,$this->it22_quadrari,1,0,"L",0);
   $this->objpdf->SetFont('Arial','B',8);

   $this->objpdf->cell(15,$altura,'Lote: ',1,0,"L",$iPreencheFundo);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(15,$altura,$this->it22_loteri,1,1,"L",0);



   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(22,$altura,'Bairro: ',1,0,"L",$iPreencheFundo);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(66,$altura,@$this->j13_descr,1,1,"L",0);

   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(22,$altura,'Logradouro: ',1,0,"L",$iPreencheFundo);
   $this->objpdf->SetFont('Arial','',8);
   $endereco = @$this->j14_tipo . " " . @$this->j14_nome;
   if(strlen($endereco) > 32){
     $pontos = "...";
   }else{
     $pontos = "";
   }

   $this->objpdf->cell(66,$altura,substr($endereco, 0, 32).$pontos,1,1,"L",0);
   $this->objpdf->SetFont('Arial','B',8);
   $iGetY = $this->objpdf->getY();
     $this->objpdf->cell(22,$altura,'Situa��o: ',1,0,"L",$iPreencheFundo);
     $this->objpdf->SetFont('Arial','',8);
     $this->objpdf->cell(66,$altura,@$this->it07_descr,1,1,"L",0);
     $this->objpdf->SetFont('Arial','B',8);
     $this->objpdf->cell(22,$altura,'Frente: ',1,0,"L",$iPreencheFundo);
     $this->objpdf->cell(21,$altura,db_formatar($this->it05_frente,'f',' ' ,0,'e',3) . 'm',1,0,"R",0);
     $this->objpdf->cell(22,$altura,'Fundos : ',1,0,"L",$iPreencheFundo);
     $this->objpdf->cell(23,$altura,db_formatar($this->it05_fundos,'f',' ' ,0,'e',3).'m',1,1,"R",0);

     $this->objpdf->cell(22,$altura,'Lado Direito: ',1,0,"L",$iPreencheFundo);
     $this->objpdf->cell(21,$altura,db_formatar($this->it05_direito,'f',' ' ,0,'e',3).'m',1,0,"R",0);

     $this->objpdf->cell(22,$altura,'Lado Esquerdo: ',1,0,"L",$iPreencheFundo);
     $this->objpdf->cell(23,$altura,db_formatar($this->it05_esquerdo,'f',' ' ,0,'e',3).'m',1,1,"R",0);

   $this->objpdf->setXY(100,$iGetY);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(24,$altura,''         ,1,0,"L",$iPreencheFundo);
   $this->objpdf->cell(42,$altura,'Real'     ,1,0,"C",$iPreencheFundo);
   $this->objpdf->cell(41,$altura,'Transmitida',1,1,"C",$iPreencheFundo);
   $this->objpdf->setx(100);

   $this->objpdf->cell(24,$altura,'Terreno: '    ,1,0,"L",$iPreencheFundo);
   $this->objpdf->SetFont('Arial','' ,8);
   $this->objpdf->cell(42,$altura,db_formatar($this->areaterreno+0,'f',' ',' ',' ',6).($this->tipoitbi=="urbano"?'m2':'ha')          ,1,0,"R",0);
   $this->objpdf->cell(41,$altura,(count($this->areaterrenomat)==1?db_formatar($this->areatran,'f',' ',' ',' ',6).($this->tipoitbi=="urbano"?'m2':'ha'):(strlen($this->areaterrenomat[1])>2?$this->areatran:db_formatar($this->areatran,'f',' ',' ',' ',6).($this->tipoitbi=="urbano"?'m2':'ha'))),1,1,"R",0);
   $this->objpdf->setx(100);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(24,$altura,'Constru��es:',1,0,"L",$iPreencheFundo);
   $this->objpdf->SetFont('Arial','' ,8);
   $this->objpdf->cell(42,$altura,(@$this->areatotal == 0?'':(count(@$this->areaedificadamat)==1?db_formatar(@$this->areatotal,'f',' ',' ',' ',6).'m2':(strlen(@$this->areaedificadamat[1])>2?db_formatar(@$this->areatotal,'f',' ',' ',' ',6).'m2':db_formatar(@$this->areatotal,'f',' ',' ',' ',6).'m2'))),1,0,"R",0);
   $this->objpdf->cell(41,$altura,(@$this->areatotal == 0?'':(count(@$this->areaedificadamat)==1?db_formatar(@$this->areatrans,'f',' ',' ',' ',6).'m2':(strlen(@$this->areaedificadamat[1])>2?db_formatar(@$this->areatrans,'f',' ',' ',' ',6).'m2':db_formatar(@$this->areatrans,'f',' ',' ',' ',6).'m2'))),1,1,"R",0);

   $iPosicaoYObs = $this->objpdf->getY();

   } else {

   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(33,$altura,'Matr�c. Reg. Im�veis : ',1,0,"L",$iPreencheFundo);
   $this->objpdf->SetFont('Arial','' ,8);
   $this->objpdf->cell(10,$altura,@$this->it22_matricri  ,1,0,"L",0);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(15,$altura,'Distante: '       ,1,0,"L",$iPreencheFundo);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(10,$altura,$this->it18_distcidade   ,1,0,"L",0);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(20,$altura,'km da Cidade'       ,1,1,"L",$iPreencheFundo);

   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(22,$altura,'Logradouro:'                   ,1,0,"L",$iPreencheFundo);
   $this->objpdf->SetFont('Arial','' ,8);
   $this->objpdf->cell(66,$altura,substr($this->it18_nomelograd,0,40)  ,1,1,"L",0);

   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(22,$altura,'Frente: '                        ,1,0,"L",1);
   $this->objpdf->SetFont('Arial','' ,8);
   $this->objpdf->cell(22,$altura,$this->it18_frente != "0" ? (db_formatar($this->it18_frente,'f',' ' ,0,'e',3).'m') : "",1,0,"L",0);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(22,$altura,'Fundos: '                        ,1,0,"L",1);
   $this->objpdf->SetFont('Arial','' ,8);
   $this->objpdf->cell(22,$altura,$this->it18_fundos != "0" ? (db_formatar($this->it18_fundos,'f',' ' ,0,'e',3).'m') : "",1,1,"L",0);

   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(22,$altura,'Profundidade:'                   ,1,0,"L",1);
   $this->objpdf->SetFont('Arial','' ,8);
   $this->objpdf->cell(22,$altura,$this->it18_prof   != "0" ? (db_formatar($this->it18_prof  ,'f',' ' ,0,'e',3).'m') : "",1,0,"L",0);

   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(28,$altura,'Frente via P�blica:',1,0,"L",1);
   $this->objpdf->SetFont('Arial','',8);
   $this->objpdf->cell(16,$altura,$this->lFrenteVia                   ,1,1,"C",0);

   $iGetY = $this->objpdf->getY();

   $this->objpdf->setXY(100,($this->objpdf->getY()+$altura));
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(24,$altura,''         ,1,0,"L",$iPreencheFundo);
   $this->objpdf->cell(42,$altura,'Real'     ,1,0,"C",$iPreencheFundo);
   $this->objpdf->cell(41,$altura,'Transmitida',1,1,"C",$iPreencheFundo);
   $this->objpdf->setx(100);
   $this->objpdf->cell(24,$altura,'Terra: '    ,1,0,"L",$iPreencheFundo);
   $this->objpdf->SetFont('Arial','' ,8);
   $this->objpdf->cell(42,$altura,db_formatar($this->areaterreno+0,'f',' ',' ',' ',6).($this->tipoitbi=="urbano"?'m2':'ha')          ,1,0,"R",0);
   $this->objpdf->cell(41,$altura,(count($this->areaterrenomat)==1?db_formatar($this->areatran,'f',' ',' ',' ',6).($this->tipoitbi=="urbano"?'m2':'ha'):(strlen($this->areaterrenomat[1])>2?$this->areatran:db_formatar($this->areatran,'f',' ',' ',' ',6).($this->tipoitbi=="urbano"?'m2':'ha'))),1,1,"R",0);
   $this->objpdf->setx(100);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(24,$altura,'Benfeitorias:',1,0,"L",$iPreencheFundo);
   $this->objpdf->SetFont('Arial','' ,8);
   $this->objpdf->cell(42,$altura,(@$this->areatotal == 0?'':(count(@$this->areaedificadamat)==1?db_formatar(@$this->areatotal,'f',' ',' ',' ',6).'m2':(strlen(@$this->areaedificadamat[1])>2?db_formatar(@$this->areatotal,'f',' ',' ',' ',6).'m2':db_formatar(@$this->areatotal,'f',' ',' ',' ',6).'m2'))),1,0,"R",0);
   $this->objpdf->cell(41,$altura,(@$this->areatotal == 0?'':(count(@$this->areaedificadamat)==1?db_formatar(@$this->areatrans,'f',' ',' ',' ',6).'m2':(strlen(@$this->areaedificadamat[1])>2?db_formatar(@$this->areatrans,'f',' ',' ',' ',6).'m2':db_formatar(@$this->areatrans,'f',' ',' ',' ',6).'m2'))),1,1,"R",0);

   $iPosicaoYObs = $this->objpdf->getY();

   $this->objpdf->setY($iGetY);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(44,$altura,'Utiliza��o Terra(ha)'  ,1,0,"L",$iPreencheFundo);
   $this->objpdf->cell(44,$altura,'Distribui��o Terra(ha)',1,1,"L",$iPreencheFundo);

     $iGetY = $this->objpdf->getY();

     $iLimiteLinhasCaract = 2;
   $iLinhasCaract       = 0;

     if( count($this->aDadosRuralCaractUtil) > 0 ){
       foreach ( $this->aDadosRuralCaractUtil as $iInd => $aDadosUtil ){
         $this->objpdf->SetFont('Arial','B',6);
         $this->objpdf->cell(30,$altura,$aDadosUtil['Descricao']   ,1,0,"L",$iPreencheFundo);
         $this->objpdf->SetFont('Arial','',6);
         $this->objpdf->cell(14,$altura,$aDadosUtil['Valor']."%",1,1,"R",0);
         if ( $iInd == $iLimiteLinhasCaract) {
           break;
         }
         $iLinhasCaract++;
       }
     }

     if ( $iLinhasCaract < $iLimiteLinhasCaract ) {
       for( $iInd=$iLinhasCaract; $iInd < $iLimiteLinhasCaract; $iInd++){
         $this->objpdf->cell(30,$altura,"",1,0,"L",1);$this->objpdf->cell(170,$altura,'',"LBR",0,"l",0); $this->objpdf->cell(27, $altura,''," LR",1,"L",0);

//   $this->objpdf->ln(2);
         $this->objpdf->cell(14,$altura,"",1,1,"R",0);
       }
     }

   $iLinhasCaract       = 0;

     if( count($this->aDadosRuralCaractDist) > 0 ){

       $this->objpdf->setY($iGetY);
       foreach ( $this->aDadosRuralCaractDist as $iInd => $aDadosDist ){
         $this->objpdf->SetFont('Arial','B',6);
         $this->objpdf->setX(54);
         $this->objpdf->cell(30,$altura,$aDadosDist['Descricao'],1,0,"L",$iPreencheFundo);
         $this->objpdf->SetFont('Arial','',6);
         $this->objpdf->cell(14,$altura,$aDadosDist['Valor']."%",1,1,"R",0);
         if ( $iInd == $iLimiteLinhasCaract) {
          break;
         }
         $iLinhasCaract++;
       }
       $iGetY = $this->objpdf->getY();
     }

     if ( $iLinhasCaract < $iLimiteLinhasCaract ) {
       $this->objpdf->setY($iGetY);
       for( $iInd=$iLinhasCaract; $iInd < $iLimiteLinhasCaract; $iInd++){
     $this->objpdf->setX(54);
         $this->objpdf->cell(30,$altura,"",1,0,"L",$iPreencheFundo);
         $this->objpdf->cell(14,$altura,"",1,1,"R",0);
       }
     }

   }


   $this->objpdf->SetXY(100,$y);
   $this->objpdf->SetFont('Arial','B',7);
   $this->objpdf->cell(24,$altura,'Descri��o',1,0,"C",$iPreencheFundo);
   $this->objpdf->cell(35,$altura,'Tipo',1,0,"C",$iPreencheFundo);
   $this->objpdf->cell(20,$altura,'�rea m2',1,0,"C",$iPreencheFundo);
   $this->objpdf->cell(20,$altura,'�rea trans m2',1,0,"C",$iPreencheFundo);
   $this->objpdf->cell(8,$altura,'Ano',1,1,"C",$iPreencheFundo);
   $this->objpdf->SetFont('Arial','',7);
   $y = $this->objpdf->gety();

   for ($ii = 1;$ii <= 4 ; $ii++){
       $this->objpdf->setx(100);
       $this->objpdf->cell(24,$altura,'',1,0,"C");
       $this->objpdf->cell(35,$altura,'',1,0,"C");
       $this->objpdf->cell(20,$altura,'',1,0,"C");
       $this->objpdf->cell(20,$altura,'',1,0,"C");
       $this->objpdf->cell(8, $altura,'',1,1,"C");
   }
   $yy = $this->objpdf->gety();
   $this->objpdf->SetXY(100,$y);

   if($this->linhasresultcons > 0){
     for ($n = 0;$n < $this->linhasresultcons ; $n++){

       $this->objpdf->setx(100);
       $this->objpdf->cell(24,$altura,( strlen($this->arrayit09_codigo[$n])>12? substr($this->arrayit09_codigo[$n],0,12)."...":$this->arrayit09_codigo[$n]),0,0,"L",0);
       $this->objpdf->cell(35,$altura,substr($this->arrayit10_codigo[$n],0,20),0,0,"L",0);
       $this->objpdf->cell(20,$altura,db_formatar($this->arrayit08_area[$n],'f',' ',' ',' ',5),0,0,"R",0);
         $this->objpdf->cell(20,$altura,db_formatar($this->arrayit08_areatrans[$n],'f',' ',' ',' ',5),0,0,"R",0);
       $this->objpdf->cell(8,$altura,$this->arrayit08_ano[$n],0,1,"C",0);
       if($n == 3){
         break;
       }
     }
   }

   $this->objpdf->setY($iPosicaoYObs);
   $this->objpdf->ln(2);
   $this->objpdf->SetFont('Arial','B',8);
   $this->objpdf->cell(30,$altura,'Observa��es',1,0,"L",$iPreencheFundo);
   $this->objpdf->SetFont('Arial','',7);
   $this->objpdf->cell(140,$altura,'Emitido no Departamento: '.$this->nomeDepartamento,1,0,"R",$iPreencheFundo);
if ($this->hasQrCode) {
    $this->objpdf->cell(27,$altura,'Pague com PIX',1,1,"C",$iPreencheFundo);
    $this->objpdf->Image($this->qrcode,184,$y+35, 20, 20, 'png');
} else {
    $this->objpdf->cell(27,$altura,'V I S T O',1,1,"C",$iPreencheFundo);
}
   $this->objpdf->SetFont('Arial','',8);
   $y = $this->objpdf->gety();


   $this->objpdf->cell(170,$altura,'',"TLR",0,"L",0); $this->objpdf->cell(27, $altura,'',"TLR",1,"L",0);
   $this->objpdf->cell(170,$altura,'',"LBR",0,"l",0); $this->objpdf->cell(27, $altura,'',"LR" ,1,"L",0);
   $this->objpdf->cell(170,$altura,'',"LBR",0,"l",0); $this->objpdf->cell(27, $altura,'',"LR" ,1,"L",0);

   if (@$this->db21_usadebitoitbi == 't') {
       $valor_corrigido = $this->vlrtotal - $this->aDadosFormasPgto[0]['Imposto'] - $this->vlrjuros - $this->vlrmulta;
       $this->objpdf->SetFont('Arial','B',6);
       $this->objpdf->cell(21,$altura,'Valor Principal',1,0,"L",$iPreencheFundo);
       $this->objpdf->SetFont('Arial','',6);
       $this->objpdf->cell(21,$altura,db_formatar($this->aDadosFormasPgto[0]['Imposto'],'f'),1,0,"R",0);

       $this->objpdf->SetFont('Arial','B',6);
       $this->objpdf->cell(21,$altura,'Valor Corre��o',1,0,"L",$iPreencheFundo);
       $this->objpdf->SetFont('Arial','',6);
       $this->objpdf->cell(21,$altura,db_formatar($valor_corrigido, 'f'),1,0,"R",0);

       $this->objpdf->SetFont('Arial','B',6);
       $this->objpdf->cell(21,$altura,'Juros',1,0,"L",$iPreencheFundo);
       $this->objpdf->SetFont('Arial','',6);
       $this->objpdf->cell(21,$altura,db_formatar($this->vlrjuros, 'f'),1,0,"R",0);

       $this->objpdf->SetFont('Arial','B',6);
       $this->objpdf->cell(22,$altura,'Multa',1,0,"L",$iPreencheFundo);
       $this->objpdf->SetFont('Arial','',6);
       $this->objpdf->cell(22,$altura,db_formatar($this->vlrmulta, 'f'),1,0,"R",0);

       $this->objpdf->cell(27, $altura,''," LR",1,"L",0);
   } else {
        $this->objpdf->cell(170,$altura,'',"LBR",0,"l",0); $this->objpdf->cell(27, $altura,''," LR",1,"L",0);
   }

   $this->objpdf->SetFont('Arial','B',6);

   $iPosicaoYFormaPgto = $this->objpdf->getY();
   $this->objpdf->cell(25,$altura,"Tipo"        ,1,0,"C",$iPreencheFundo);
   $this->objpdf->cell(23,$altura,"Informado"     ,1,0,"C",$iPreencheFundo);
   $this->objpdf->cell(23,$altura,"Avaliado"      ,1,0,"C",$iPreencheFundo);
   $iPosicaoXFormaPgto = $this->objpdf->getX();
   $this->objpdf->ln();

   if ($this->tipoitbi == "urbano") {
     $this->objpdf->cell(25,$altura,"Terreno"                   ,1,0,"L",$iPreencheFundo);
   } else {
     $this->objpdf->cell(25,$altura,"Terra"                   ,1,0,"L",$iPreencheFundo);
   }

   $this->objpdf->SetFont('Arial','',6);
   $this->objpdf->cell(23,$altura,db_formatar($this->it01_valorterreno,'f')   ,1,0,"R",0);
   $this->objpdf->cell(23,$altura,db_formatar($this->it14_valoravalter,'f')   ,1,1,"R",0);
   $this->objpdf->SetFont('Arial','B',6);

   if ($this->tipoitbi == "urbano") {
     $this->objpdf->cell(25,$altura,"Constru��o"                ,1,0,"L",$iPreencheFundo);
   } else {
     $this->objpdf->cell(25,$altura,"Benfeitoria"               ,1,0,"L",$iPreencheFundo);
   }

   $this->objpdf->SetFont('Arial','',6);
   $this->objpdf->cell(23,$altura,db_formatar($this->it01_valorconstr,'f')    ,1,0,"R",0);
   $this->objpdf->cell(23,$altura,db_formatar($this->it14_valoravalconstr,'f'),1,1,"R",0);
   $this->objpdf->SetFont('Arial','B',6);
   $this->objpdf->cell(25,$altura,"Total"                     ,1,0,"L",$iPreencheFundo);
   $this->objpdf->SetFont('Arial','',6);
   $this->objpdf->cell(23,$altura,db_formatar($this->it01_valortransacao,'f') ,1,0,"R",0);
   $this->objpdf->cell(23,$altura,db_formatar($this->it14_valoraval,'f')      ,1,1,"R",0);

   if (isset($this->dataLiberado) && $this->dataLiberado != ""){

     $this->objpdf->SetFont('Arial','B',6);
     $this->objpdf->cell(25,$altura,"Data de Avalia��o:",1,0,"L",$iPreencheFundo);

     $this->objpdf->SetFont('Arial','',6);
     $this->objpdf->cell(23,$altura, $this->dataLiberado,1,1,"R",0);

   }

   $iPosicaoYCodBarra = $this->objpdf->getY();
   $this->objpdf->SetFont('Arial','B',6);
   $this->objpdf->setXY($iPosicaoXFormaPgto,$iPosicaoYFormaPgto);
   $this->objpdf->cell(39,$altura,"Forma de Pagamento",1,0,"C",$iPreencheFundo);
   $this->objpdf->cell(20,$altura,"Avaliado"      ,1,0,"C",$iPreencheFundo);
   $this->objpdf->cell(20,$altura,"Aliquota"      ,1,0,"C",$iPreencheFundo);
   $this->objpdf->cell(20,$altura,"Imposto"       ,1,0,"C",$iPreencheFundo);
   $this->objpdf->cell(27, $altura,''         ," LR",1,"L",0);

   $nTotalImposto  = 0;
   $nTotalAvaliado = 0;
   $lExibeMsg      = false;

   if( count($this->aDadosFormasPgto) > 0 ){
     foreach ( $this->aDadosFormasPgto as $iInd => $aDadosFormas ){
       if ( $iInd <= 2 ) {

         $this->objpdf->setX($iPosicaoXFormaPgto);
         $this->objpdf->SetFont('Arial','B',6);
         $this->objpdf->cell(39,$altura,$aDadosFormas['Descricao']            ,1,0,"L",$iPreencheFundo);
         $this->objpdf->SetFont('Arial','',6);
         $this->objpdf->cell(20,$altura,db_formatar($aDadosFormas['Valor'],'f')   ,1,0,"R",0);
         $this->objpdf->cell(20,$altura,$aDadosFormas['Aliquota'].(trim($aDadosFormas['Aliquota']) != ""?"%":"")          ,1,0,"R",0);
         $this->objpdf->cell(20,$altura,db_formatar($aDadosFormas['Imposto'],'f') ,1,0,"R",0);
         $this->objpdf->cell(27, $altura,''," LR",1,"L",0);

         $iPosicaoYTotalFormaPgto = $this->objpdf->getY();

       } else {
         $lExibeMsg = true;
       }

       $nTotalImposto  += $aDadosFormas['Imposto'];
       $nTotalAvaliado += $aDadosFormas['Valor'];
     }
   }

   if ( $lExibeMsg ) {
     $this->objpdf->SetFont('Arial','B',6);
     $this->objpdf->setY($iPosicaoYCodBarra);
     $this->objpdf->cell(71,$altura,"* Existem mais formas de pagamento para esse ITBI","T",0,"L",0);
     $this->objpdf->setXY($iPosicaoXFormaPgto,$iPosicaoYTotalFormaPgto);
   } else{
     $this->objpdf->setX($iPosicaoXFormaPgto);
   }
   $this->objpdf->SetFont('Arial','B',6);
   $this->objpdf->cell(39,$altura,"Total"                       ,1,0,"L",$iPreencheFundo);
   $this->objpdf->SetFont('Arial','',6);
   $this->objpdf->cell(20,$altura,db_formatar($nTotalAvaliado,'f')      ,1,0,"R",0);
   $this->objpdf->cell(20,$altura,""                        ,1,0,"R",0);
   $this->objpdf->cell(20,$altura,db_formatar($this->tx_banc + $this->vlrtotal,'f')       ,1,0,"R",0);
   $this->objpdf->cell(27, $altura,''                   ,"BLR",1,"L",0);


   /*
    * Mostramos os dados das observa��es da GUIA
    */
   $observacoes = substr( str_replace("\n"," ",$this->adquirintes." - ".$this->transmitentes." - ".$this->observacaoIncluido." ".$this->observacaoLiberado) ,0,400);
   $yy = $this->objpdf->gety();
   $this->objpdf->sety($y);
   $this->objpdf->multicell(170,$altura,$observacoes,1,"L",0);
   $this->objpdf->sety($yy);


   $this->objpdf->ln(2);
   $this->objpdf->SetFont('Arial','B',10);
   if ( $this->cgc == "28531762000133") { // araruama
     $this->objpdf->setX(100);
   } else {
     $this->objpdf->setX(135);
   }

   if ($this->it14_valorpaga == 0) {
//     $this->objpdf->cell(60,$altura,'Valor a Pagar : I S E N T O',0,1,"C",0);
     $this->objpdf->cell(60,$altura,'Valor a Pagar : NAO INCIDE',0,1,"C",0);
   } else {

     if ( $this->cgc == "28531762000133") { // araruama
     	 $this->objpdf->cell(60,$altura,'Taxa de expediente: R$ '.db_formatar($this->tx_banc,'f','',1).' - Valor a Pagar : R$ '.db_formatar($this->it14_valorpaga+$this->tx_banc,'f','',1),0,1,"L",0);
     } else {
        $total_itbi = @$this->db21_usadebitoitbi == 't' ? $this->tx_banc + $this->vlrtotal : $this->it14_valorpaga+$this->tx_banc;
       $this->objpdf->cell(60,$altura,'Valor a Pagar : R$ '.db_formatar(($total_itbi),'f'),0,1,"L",0);
     }
   }

   $this->objpdf->ln(5);
   $pos = $this->objpdf->gety();
   $this->objpdf->setfillcolor(0,0,0);

   if ($this->lLiberado) {

     if ($this->it14_valorpaga > 0) {

       $this->objpdf->text(14,$pos,$this->linha_digitavel);
       $this->objpdf->int25(10,$pos+1,$this->codigo_barras,15,0.341);
     }
   }



   $this->objpdf->ln(25);
}
