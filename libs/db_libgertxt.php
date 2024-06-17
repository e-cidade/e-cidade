<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: CAIXA
class cl_layout_BBBS {

/***************************************************************************************************/
/***************************************************************************************************/
/*********************  VARIÁVEIS USADAS PARA GERAR ARQUIVO DO BANCO CEF **********************/
/***************************************************************************************************/
/***************************************************************************************************/
/*
    CABEÇALHO ARQUIVO
*/ 
    var  $CEFheaderA_001_003 = null;
    var  $CEFheaderA_004_007 = null;
    var  $CEFheaderA_008_008 = null;
    var  $CEFheaderA_009_017 = null;
    var  $CEFheaderA_018_018 = null;
    var  $CEFheaderA_019_032 = null;
    var  $CEFheaderA_033_038 = null;
    var  $CEFheaderA_039_040 = null;
    var  $CEFheaderA_041_041 = null;
    var  $CEFheaderA_042_042 = null;
    var  $CEFheaderA_043_045 = null;
    var  $CEFheaderA_046_049 = null;
    var  $CEFheaderA_050_052 = null;
    var  $CEFheaderA_053_057 = null;
    var  $CEFheaderA_058_058 = null;
    var  $CEFheaderA_059_070 = null;
    var  $CEFheaderA_071_071 = null;
    var  $CEFheaderA_072_072 = null;
    var  $CEFheaderA_073_102 = null;
    var  $CEFheaderA_103_132 = null;
    var  $CEFheaderA_133_142 = null;
    var  $CEFheaderA_143_143 = null;
    var  $CEFheaderA_144_151 = null;
    var  $CEFheaderA_152_157 = null;
    var  $CEFheaderA_158_163 = null;
    var  $CEFheaderA_164_166 = null;
    var  $CEFheaderA_167_171 = null;
    var  $CEFheaderA_172_191 = null;
    var  $CEFheaderA_192_211 = null;
    var  $CEFheaderA_212_222 = null;
    var  $CEFheaderA_223_225 = null;
    var  $CEFheaderA_226_228 = null;
    var  $CEFheaderA_229_230 = null;
    var  $CEFheaderA_231_240 = null;

/*
    CABEÇALHO LOTE
*/
    var  $CEFheaderL_001_003 = null;
    var  $CEFheaderL_004_007 = null;
    var  $CEFheaderL_008_008 = null;
    var  $CEFheaderL_009_009 = null;
    var  $CEFheaderL_010_011 = null;
    var  $CEFheaderL_012_013 = null;
    var  $CEFheaderL_014_016 = null;
    var  $CEFheaderL_017_017 = null;
    var  $CEFheaderL_018_018 = null;
    var  $CEFheaderL_019_032 = null;
    var  $CEFheaderL_033_038 = null;  
    var  $CEFheaderL_039_040 = null;
	var  $CEFheaderL_041_044 = null;
	var  $CEFheaderL_045_046 = null;
	var  $CEFheaderL_047_052 = null;  
    var  $CEFheaderL_053_057 = null;
    var  $CEFheaderL_058_058 = null;
    var  $CEFheaderL_059_070 = null;
    var  $CEFheaderL_071_071 = null;    
    var  $CEFheaderL_072_072 = null;
    var  $CEFheaderL_073_102 = null;
    var  $CEFheaderL_103_142 = null;    
    var  $CEFheaderL_143_172 = null;
    var  $CEFheaderL_173_177 = null;
    var  $CEFheaderL_178_192 = null;
    var  $CEFheaderL_193_212 = null;
    var  $CEFheaderL_213_217 = null;
    var  $CEFheaderL_218_220 = null;
    var  $CEFheaderL_221_222 = null;
    var  $CEFheaderL_223_230 = null;
    var  $CEFheaderL_231_240 = null;

/*
    CORPO SEGMENTO A
*/
    var  $CEFregistA_001_003 = null;
    var  $CEFregistA_004_007 = null;
    var  $CEFregistA_008_008 = null;
    var  $CEFregistA_009_013 = null;
    var  $CEFregistA_014_014 = null;
    var  $CEFregistA_015_015 = null;
    var  $CEFregistA_016_017 = null;
    var  $CEFregistA_018_020 = null;
    var  $CEFregistA_021_023 = null;
    var  $CEFregistA_024_028 = null;
    var  $CEFregistA_029_029 = null;
    var  $CEFregistA_030_041 = null;
    var  $CEFregistA_042_042 = null;
    var  $CEFregistA_043_043 = null;
    var  $CEFregistA_044_073 = null;
    var  $CEFregistA_074_079 = null;
    var  $CEFregistA_080_092 = null;
    var  $CEFregistA_093_093 = null;
    var  $CEFregistA_094_101 = null;
    var  $CEFregistA_102_104 = null;
    var  $CEFregistA_105_119 = null;
    var  $CEFregistA_120_134 = null;
    var  $CEFregistA_135_143 = null;
    var  $CEFregistA_144_146 = null;
    var  $CEFregistA_147_148 = null;
    var  $CEFregistA_149_149 = null;
    var  $CEFregistA_150_150 = null;
    var  $CEFregistA_151_152 = null;
    var  $CEFregistA_153_154 = null;
    var  $CEFregistA_155_162 = null;
    var  $CEFregistA_163_177 = null;
    var  $CEFregistA_178_217 = null;
    var  $CEFregistA_218_219 = null;
    var  $CEFregistA_220_229 = null;
    var  $CEFregistA_230_230 = null;
    var  $CEFregistA_231_240 = null;


/*
    CORPO SEGMENTO B
*/
    var  $CEFregistB_001_003 = null;
    var  $CEFregistB_004_007 = null;
    var  $CEFregistB_008_008 = null;
    var  $CEFregistB_009_013 = null;
    var  $CEFregistB_014_014 = null;
    var  $CEFregistB_015_017 = null;
    var  $CEFregistB_018_018 = null;
    var  $CEFregistB_019_032 = null;
    var  $CEFregistB_033_062 = null;
    var  $CEFregistB_063_067 = null;
    var  $CEFregistB_068_082 = null;
    var  $CEFregistB_083_097 = null;
    var  $CEFregistB_098_117 = null;
    var  $CEFregistB_118_122 = null;
    var  $CEFregistB_123_125 = null;
    var  $CEFregistB_126_127 = null;
    var  $CEFregistB_128_135 = null;
    var  $CEFregistB_136_150 = null;
    var  $CEFregistB_151_165 = null;
    var  $CEFregistB_166_180 = null;
    var  $CEFregistB_181_195 = null;
    var  $CEFregistB_196_210 = null;
    var  $CEFregistB_211_225 = null;
    var  $CEFregistB_226_240 = null; 
/*
    TRAILLER LOTE
*/
    var  $CEFtraillerL_001_003 = null;
    var  $CEFtraillerL_004_007 = null;
    var  $CEFtraillerL_008_008 = null;
    var  $CEFtraillerL_009_017 = null;
    var  $CEFtraillerL_018_023 = null;
    var  $CEFtraillerL_024_041 = null;
    var  $CEFtraillerL_042_059 = null;
    var  $CEFtraillerL_060_065 = null;
    var  $CEFtraillerL_066_230 = null;
    var  $CEFtraillerL_231_240 = null;

    /*
    TRAILLER ARQUIVO
*/
    var  $CEFtraillerA_001_003 = null;
    var  $CEFtraillerA_004_007 = null;
    var  $CEFtraillerA_008_008 = null;
    var  $CEFtraillerA_009_017 = null;
    var  $CEFtraillerA_018_023 = null;
    var  $CEFtraillerA_024_029 = null;
    var  $CEFtraillerA_030_035 = null;
    var  $CEFtraillerA_036_240 = null;
   
/***************************************************************************************************/
/***************************************************************************************************/
/*********************  VARIÁVEIS USADAS PARA GERAR ARQUIVO DO BANCO BANRISUL **********************/
/***************************************************************************************************/
/***************************************************************************************************/
/*
    CABEÇALHO ARQUIVO
*/ 
    var  $BSheaderA_001_003 = null;
    var  $BSheaderA_004_007 = null;
    var  $BSheaderA_008_008 = null;
    var  $BSheaderA_009_017 = null;
    var  $BSheaderA_018_018 = null;
    var  $BSheaderA_019_032 = null;
    var  $BSheaderA_033_037 = null;
    var  $BSheaderA_038_052 = null;
    var  $BSheaderA_053_057 = null;
    var  $BSheaderA_058_058 = null;
    var  $BSheaderA_059_061 = null;
    var  $BSheaderA_062_071 = null;
    var  $BSheaderA_072_072 = null;
    var  $BSheaderA_073_102 = null;
    var  $BSheaderA_103_132 = null;
    var  $BSheaderA_133_142 = null;
    var  $BSheaderA_143_143 = null;
    var  $BSheaderA_144_151 = null;
    var  $BSheaderA_152_157 = null;
    var  $BSheaderA_158_163 = null;
    var  $BSheaderA_164_166 = null;
    var  $BSheaderA_167_171 = null;
    var  $BSheaderA_172_191 = null;
    var  $BSheaderA_192_211 = null;
    var  $BSheaderA_212_240 = null;
/*
    CABEÇALHO LOTE
*/ 
    var  $BSheaderL_001_003 = null;
    var  $BSheaderL_004_007 = null;
    var  $BSheaderL_008_008 = null;
    var  $BSheaderL_009_009 = null;
    var  $BSheaderL_010_011 = null;
    var  $BSheaderL_012_013 = null;
    var  $BSheaderL_014_016 = null;
    var  $BSheaderL_017_017 = null;
    var  $BSheaderL_018_018 = null;
    var  $BSheaderL_019_032 = null;
    var  $BSheaderL_033_037 = null;
    var  $BSheaderL_038_052 = null;
    var  $BSheaderL_053_057 = null;
    var  $BSheaderL_058_061 = null;
    var  $BSheaderL_062_071 = null;
    var  $BSheaderL_072_072 = null;
    var  $BSheaderL_073_102 = null;
    var  $BSheaderL_103_142 = null;
    var  $BSheaderL_143_172 = null;
    var  $BSheaderL_173_177 = null;
    var  $BSheaderL_178_192 = null;
    var  $BSheaderL_193_212 = null;
    var  $BSheaderL_213_220 = null;
    var  $BSheaderL_221_222 = null;
    var  $BSheaderL_223_224 = null;
    var  $BSheaderL_225_240 = null;
/*
    FINAL CABEÇALHOS      
*/
/*
    CORPO
*/
    var  $BSregist_001_003 = null;
    var  $BSregist_004_007 = null;
    var  $BSregist_008_008 = null;
    var  $BSregist_009_013 = null;
    var  $BSregist_014_014 = null;
    var  $BSregist_015_015 = null;
    var  $BSregist_016_017 = null;
    var  $BSregist_018_020 = null;
    var  $BSregist_021_023 = null;
    var  $BSregist_024_028 = null;
    var  $BSregist_029_029 = null;
    var  $BSregist_030_042 = null;
    var  $BSregist_043_043 = null;
    var  $BSregist_044_073 = null;
    var  $BSregist_074_088 = null;
    var  $BSregist_089_093 = null;
    var  $BSregist_094_101 = null;
    var  $BSregist_102_104 = null;
    var  $BSregist_105_119 = null;
    var  $BSregist_120_134 = null;
    var  $BSregist_135_154 = null;
    var  $BSregist_155_162 = null;
    var  $BSregist_163_177 = null;
    var  $BSregist_178_182 = null;
    var  $BSregist_183_202 = null;
    var  $BSregist_203_203 = null;
    var  $BSregist_204_217 = null;
    var  $BSregist_218_229 = null;
    var  $BSregist_230_230 = null;
    var  $BSregist_231_240 = null;
/*
    FINAL CORPO
*/
/***************************************************************************************************/

/***************************************************************************************************/
/***************************************************************************************************/
/*********************  VARIÁVEIS USADAS PARA GERAR ARQUIVO DO BANCO DO BRASIL *********************/
/***************************************************************************************************/
/***************************************************************************************************/
/*
    CABEÇALHO ARQUIVO
*/
    var  $BBheaderA_001_003 = null;
    var  $BBheaderA_004_007 = null;
    var  $BBheaderA_008_008 = null;
    var  $BBheaderA_009_017 = null;
    var  $BBheaderA_018_018 = null;
    var  $BBheaderA_019_032 = null;
    var  $BBheaderA_033_052 = null;
    var  $BBheaderA_053_057 = null;
    var  $BBheaderA_058_058 = null;
    var  $BBheaderA_059_070 = null;
    var  $BBheaderA_071_071 = null;
    var  $BBheaderA_072_072 = null;
    var  $BBheaderA_073_102 = null;
    var  $BBheaderA_103_132 = null;
    var  $BBheaderA_133_142 = null;
    var  $BBheaderA_143_143 = null;
    var  $BBheaderA_144_151 = null;
    var  $BBheaderA_152_157 = null;
    var  $BBheaderA_158_163 = null;
    var  $BBheaderA_164_166 = null;
    var  $BBheaderA_167_171 = null;
    var  $BBheaderA_172_191 = null;
    var  $BBheaderA_192_211 = null;
    var  $BBheaderA_212_222 = null;
    var  $BBheaderA_223_225 = null;
    var  $BBheaderA_226_228 = null;
    var  $BBheaderA_229_230 = null;
    var  $BBheaderA_231_240 = null;
/*
    CABEÇALHO LOTE
*/ 
    var  $BBheaderL_001_003 = null;
    var  $BBheaderL_004_007 = null;
    var  $BBheaderL_008_008 = null;
    var  $BBheaderL_009_009 = null;
    var  $BBheaderL_010_011 = null;
    var  $BBheaderL_012_013 = null;
    var  $BBheaderL_014_016 = null;
    var  $BBheaderL_017_017 = null;
    var  $BBheaderL_018_018 = null;
    var  $BBheaderL_019_032 = null;
    var  $BBheaderL_033_052 = null;    
    var  $BBheaderL_053_057 = null;
    var  $BBheaderL_058_058 = null;
    var  $BBheaderL_059_070 = null;
    var  $BBheaderL_071_071 = null;    
    var  $BBheaderL_072_072 = null;
    var  $BBheaderL_073_102 = null;
    var  $BBheaderL_103_142 = null;    
    var  $BBheaderL_143_172 = null;
    var  $BBheaderL_173_177 = null;
    var  $BBheaderL_178_192 = null;
    var  $BBheaderL_193_212 = null;
    var  $BBheaderL_213_217 = null;
    var  $BBheaderL_218_220 = null;
    var  $BBheaderL_221_222 = null;
    var  $BBheaderL_223_230 = null;
    var  $BBheaderL_231_240 = null;
/*         
    FINAL CBABEÇALHOS      
*/
/*
    CORPO SEGMENTO A
*/
    var  $BBregistA_001_003 = null;
    var  $BBregistA_004_007 = null;
    var  $BBregistA_008_008 = null;
    var  $BBregistA_009_013 = null;
    var  $BBregistA_014_014 = null;
    var  $BBregistA_015_015 = null;
    var  $BBregistA_016_017 = null;
    var  $BBregistA_018_020 = null;
    var  $BBregistA_021_023 = null;
    var  $BBregistA_024_028 = null;
    var  $BBregistA_029_029 = null;
    var  $BBregistA_030_041 = null;
    var  $BBregistA_042_042 = null;
    var  $BBregistA_043_043 = null;
    var  $BBregistA_044_073 = null;
    var  $BBregistA_074_093 = null;
    var  $BBregistA_094_101 = null;
    var  $BBregistA_102_104 = null;
    var  $BBregistA_105_119 = null;
    var  $BBregistA_120_134 = null;
    var  $BBregistA_135_154 = null;
    var  $BBregistA_155_162 = null;
    var  $BBregistA_163_177 = null;
    var  $BBregistA_178_217 = null;
    var  $BBregistA_218_229 = null;
    var  $BBregistA_230_230 = null;
    var  $BBregistA_231_240 = null;
/*
    CORPO SEGMENTO B
*/
    var  $BBregistB_001_003 = null;
    var  $BBregistB_004_007 = null;
    var  $BBregistB_008_008 = null;
    var  $BBregistB_009_013 = null;
    var  $BBregistB_014_014 = null;
    var  $BBregistB_015_017 = null;
    var  $BBregistB_018_018 = null;
    var  $BBregistB_019_032 = null;
    var  $BBregistB_033_062 = null;
    var  $BBregistB_063_067 = null;
    var  $BBregistB_068_082 = null;
    var  $BBregistB_083_097 = null;
    var  $BBregistB_098_117 = null;
    var  $BBregistB_118_122 = null;
    var  $BBregistB_123_125 = null;
    var  $BBregistB_126_127 = null;
    var  $BBregistB_128_135 = null;
    var  $BBregistB_136_150 = null;
    var  $BBregistB_151_165 = null;
    var  $BBregistB_166_180 = null;
    var  $BBregistB_181_195 = null;
    var  $BBregistB_196_210 = null;
    var  $BBregistB_211_225 = null;
    var  $BBregistB_226_240 = null;
/*
    FINAL CORPO
*/
/***************************************************************************************************/

/***************************************************************************************************/
/***************************************************************************************************/
/*****************  VARIÁVEIS USADAS PARA GERAR TRAILLER DO ARQUIVO DOS DOIS BANCOS ****************/
/***************************************************************************************************/
/***************************************************************************************************/
/*
    TRAILLER LOTE
*/
    var  $BBBStraillerL_001_003 = null;
    var  $BBBStraillerL_004_007 = null;
    var  $BBBStraillerL_008_008 = null;
    var  $BBBStraillerL_009_017 = null;
    var  $BBBStraillerL_018_023 = null;
    var  $BBBStraillerL_024_041 = null;
    var  $BBBStraillerL_042_059 = null;
    var  $BBBStraillerL_060_230 = null;
    var  $BBBStraillerL_231_240 = null;
/*
    TRAILLER ARQUIVO
*/
    var  $BBBStraillerA_001_003 = null;
    var  $BBBStraillerA_004_007 = null;
    var  $BBBStraillerA_008_008 = null;
    var  $BBBStraillerA_009_017 = null;
    var  $BBBStraillerA_018_023 = null;
    var  $BBBStraillerA_024_029 = null;
    var  $BBBStraillerA_230_035 = null;
    var  $BBBStraillerA_236_240 = null;
/*
	FINAL TRAILLERS
*/
/***************************************************************************************************/

   var $arquivo  = null;
   var $nomearq  = '/tmp/modelo.txt';

//////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////// MÉTODOS LAYOUT DO BANCO BANRISUL //////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////// Início --- OBS.: Somente HEADER do arquivo, HEADER do lote e REGISTROS ///////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
   function geraHEADERArqBS(){
          $this->arquivo = fopen($this->nomearq,"w");
	  fputs($this->arquivo,
	          $this->BSheaderA_001_003
	         .$this->BSheaderA_004_007
	         .$this->BSheaderA_008_008
	         .$this->BSheaderA_009_017
	         .$this->BSheaderA_018_018
	         .$this->BSheaderA_019_032
	         .$this->BSheaderA_033_037
	         .$this->BSheaderA_038_052
	         .$this->BSheaderA_053_057
	         .$this->BSheaderA_058_058
	         .$this->BSheaderA_059_061
	         .$this->BSheaderA_062_071
	         .$this->BSheaderA_072_072
	         .$this->BSheaderA_073_102
	         .$this->BSheaderA_103_132
	         .$this->BSheaderA_133_142
	         .$this->BSheaderA_143_143
	         .$this->BSheaderA_144_151
	         .$this->BSheaderA_152_157
	         .$this->BSheaderA_158_163
	         .$this->BSheaderA_164_166
	         .$this->BSheaderA_167_171
	         .$this->BSheaderA_172_191
	         .$this->BSheaderA_192_211
	         .$this->BSheaderA_212_240
		 ."\r\n"
		 //.chr(13).chr(10)

	  );
   }
   function geraHEADERLoteBS(){
        //segundo cabeçalho
          fputs($this->arquivo,
	          $this->BSheaderL_001_003
	         .$this->BSheaderL_004_007
	         .$this->BSheaderL_008_008
	         .$this->BSheaderL_009_009
	         .$this->BSheaderL_010_011
	         .$this->BSheaderL_012_013
	         .$this->BSheaderL_014_016
	         .$this->BSheaderL_017_017
	         .$this->BSheaderL_018_018
	         .$this->BSheaderL_019_032
	         .$this->BSheaderL_033_037
	         .$this->BSheaderL_038_052
	         .$this->BSheaderL_053_057
	         .$this->BSheaderL_058_061
	         .$this->BSheaderL_062_071
	         .$this->BSheaderL_072_072
	         .$this->BSheaderL_073_102
	         .$this->BSheaderL_103_142
	         .$this->BSheaderL_143_172
	         .$this->BSheaderL_173_177
	         .$this->BSheaderL_178_192
	         .$this->BSheaderL_193_212
	         .$this->BSheaderL_213_220
	         .$this->BSheaderL_221_222
	         .$this->BSheaderL_223_224
	         .$this->BSheaderL_225_240
		 ."\r\n"
		// .chr(13).chr(10)
	  );
    }
    function geraREGISTROSBS(){
	  fputs($this->arquivo,
	          $this->BSregist_001_003
	         .$this->BSregist_004_007
	         .$this->BSregist_008_008
	         .$this->BSregist_009_013
	         .$this->BSregist_014_014
	         .$this->BSregist_015_015
	         .$this->BSregist_016_017
	         .$this->BSregist_018_020
	         .$this->BSregist_021_023
	         .$this->BSregist_024_028
	         .$this->BSregist_029_029
	         .$this->BSregist_030_042
	         .$this->BSregist_043_043
	         .$this->BSregist_044_073
	         .$this->BSregist_074_088
	         .$this->BSregist_089_093
	         .$this->BSregist_094_101
	         .$this->BSregist_102_104
	         .$this->BSregist_105_119
	         .$this->BSregist_120_134
	         .$this->BSregist_135_154
	         .$this->BSregist_155_162
	         .$this->BSregist_163_177
	         .$this->BSregist_178_182
	         .$this->BSregist_183_202
	         .$this->BSregist_203_203
	         .$this->BSregist_204_217
	         .$this->BSregist_218_229
	         .$this->BSregist_230_230
	         .$this->BSregist_231_240
		 ."\r\n"
		 //.chr(13).chr(10)
	       );
    }
//////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////// FINAL MÉTODOS ARQUIVO DO BANCO DO BANRISUL ////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////// MÉTODOS LAYOUT DO BANCO DO BRASIL /////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////// Início --- OBS.: Somente HEADER do arquivo, HEADER do lote e REGISTROS ///////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

   function geraHEADERArqBB(){
          $this->arquivo = fopen($this->nomearq,"w");
	  fputs($this->arquivo,
	          $this->BBheaderA_001_003
	         .$this->BBheaderA_004_007
	         .$this->BBheaderA_008_008
	         .$this->BBheaderA_009_017
	         .$this->BBheaderA_018_018
	         .$this->BBheaderA_019_032
	         .$this->BBheaderA_033_052
	         .$this->BBheaderA_053_057
	         .$this->BBheaderA_058_058
	         .$this->BBheaderA_059_070
	         .$this->BBheaderA_071_071
	         .$this->BBheaderA_072_072
	         .$this->BBheaderA_073_102
	         .$this->BBheaderA_103_132
	         .$this->BBheaderA_133_142
	         .$this->BBheaderA_143_143
	         .$this->BBheaderA_144_151
	         .$this->BBheaderA_152_157
	         .$this->BBheaderA_158_163
	         .$this->BBheaderA_164_166
	         .$this->BBheaderA_167_171
	         .$this->BBheaderA_172_191
	         .$this->BBheaderA_192_211
	         .$this->BBheaderA_212_222
	         .$this->BBheaderA_223_225
                 .$this->BBheaderA_226_228
                 .$this->BBheaderA_229_230
                 .$this->BBheaderA_231_240
		 ."\r\n"
		 //.chr(13).chr(10)
	  );
   }
   function geraHEADERLoteBB(){
        //segundo cabeçalho
          fputs($this->arquivo,
	          $this->BBheaderL_001_003
	         .$this->BBheaderL_004_007
	         .$this->BBheaderL_008_008
	         .$this->BBheaderL_009_009
	         .$this->BBheaderL_010_011
	         .$this->BBheaderL_012_013
	         .$this->BBheaderL_014_016
	         .$this->BBheaderL_017_017
	         .$this->BBheaderL_018_018
	         .$this->BBheaderL_019_032
	         .$this->BBheaderL_033_052
	         .$this->BBheaderL_053_057
	         .$this->BBheaderL_058_058
	         .$this->BBheaderL_059_070
	         .$this->BBheaderL_071_071
	         .$this->BBheaderL_072_072
	         .$this->BBheaderL_073_102
	         .$this->BBheaderL_103_142
	         .$this->BBheaderL_143_172
	         .$this->BBheaderL_173_177
	         .$this->BBheaderL_178_192
	         .$this->BBheaderL_193_212
	         .$this->BBheaderL_213_217
	         .$this->BBheaderL_218_220
	         .$this->BBheaderL_221_222
	         .$this->BBheaderL_223_230
                 .$this->BBheaderL_231_240
		 ."\r\n"
		// .chr(13).chr(10)
	  );
    }
    function geraREGISTROSBB(){
	  fputs($this->arquivo,
	          $this->BBregistA_001_003
	         .$this->BBregistA_004_007
	         .$this->BBregistA_008_008
	         .$this->BBregistA_009_013
	         .$this->BBregistA_014_014
	         .$this->BBregistA_015_015
	         .$this->BBregistA_016_017
	         .$this->BBregistA_018_020
	         .$this->BBregistA_021_023
	         .$this->BBregistA_024_028
	         .$this->BBregistA_029_029
	         .$this->BBregistA_030_041
	         .$this->BBregistA_042_042
	         .$this->BBregistA_043_043
	         .$this->BBregistA_044_073
	         .$this->BBregistA_074_093
	         .$this->BBregistA_094_101
	         .$this->BBregistA_102_104
	         .$this->BBregistA_105_119
	         .$this->BBregistA_120_134
	         .$this->BBregistA_135_154
	         .$this->BBregistA_155_162
	         .$this->BBregistA_163_177
	         .$this->BBregistA_178_217
	         .$this->BBregistA_218_229
	         .$this->BBregistA_230_230
	         .$this->BBregistA_231_240
		 ."\r\n"
		 //.chr(13).chr(10)
	       );
	  fputs($this->arquivo,
	          $this->BBregistB_001_003
	         .$this->BBregistB_004_007
	         .$this->BBregistB_008_008
	         .$this->BBregistB_009_013
	         .$this->BBregistB_014_014
	         .$this->BBregistB_015_017
	         .$this->BBregistB_018_018
	         .$this->BBregistB_019_032
	         .$this->BBregistB_033_062
	         .$this->BBregistB_063_067
	         .$this->BBregistB_068_082
	         .$this->BBregistB_083_097
	         .$this->BBregistB_098_117
	         .$this->BBregistB_118_122
	         .$this->BBregistB_123_125
	         .$this->BBregistB_126_127
	         .$this->BBregistB_128_135
	         .$this->BBregistB_136_150
	         .$this->BBregistB_151_165
	         .$this->BBregistB_166_180
	         .$this->BBregistB_181_195
	         .$this->BBregistB_196_210
	         .$this->BBregistB_211_225
	         .$this->BBregistB_226_240
		 ."\r\n"
		 //.chr(13).chr(10)
	       );
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////// FINAL MÉTODOS ARQUIVO DO BANCO DO BRASIL /////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////// INÍCIO MÉTODOS QUE GERAM TRAILLERS ////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
// OBS.: Arquivos do banco do brasil e do banrisul não mudam trailler de arquivo e trailler de lote //
    function geraTRAILLERLote(){
      $this->arquivo = fopen($this->nomearq,"w");
	  fputs($this->arquivo,
	          $this->BBBStraillerL_001_003
	         .$this->BBBStraillerL_004_007
	         .$this->BBBStraillerL_008_008
	         .$this->BBBStraillerL_009_017
	         .$this->BBBStraillerL_018_023
	         .$this->BBBStraillerL_024_041
	         .$this->BBBStraillerL_042_059
	         .$this->BBBStraillerL_060_230
	         .$this->BBBStraillerL_231_240
		 //.chr(13).chr(10)
		 ."\r\n"
	       );
    }
    function geraTRAILLERArquivo(){
      $this->arquivo = fopen($this->nomearq,"w");
	  fputs($this->arquivo,
	          $this->BBBStraillerA_001_003
	         .$this->BBBStraillerA_004_007
	         .$this->BBBStraillerA_008_008
	         .$this->BBBStraillerA_009_017
	         .$this->BBBStraillerA_018_023
	         .$this->BBBStraillerA_024_029
	         .$this->BBBStraillerA_230_035
	         .$this->BBBStraillerA_236_240
		 //.chr(13).chr(10)
		 ."\r\n"
	       );
    }

    function geraHEADERArqCEF(){
        $this->arquivo = fopen($this->nomearq,"w");
        fputs($this->arquivo,
            $this->CEFheaderA_001_003
            .$this->CEFheaderA_004_007
            .$this->CEFheaderA_008_008
            .$this->CEFheaderA_009_017
            .$this->CEFheaderA_018_018
            .$this->CEFheaderA_019_032
            .$this->CEFheaderA_033_038
            .$this->CEFheaderA_039_040
            .$this->CEFheaderA_041_041
            .$this->CEFheaderA_042_042
            .$this->CEFheaderA_043_045
            .$this->CEFheaderA_046_049
            .$this->CEFheaderA_050_052
            .$this->CEFheaderA_053_057
            .$this->CEFheaderA_058_058
            .$this->CEFheaderA_059_070
            .$this->CEFheaderA_071_071
            .$this->CEFheaderA_072_072
            .$this->CEFheaderA_073_102
            .$this->CEFheaderA_103_132
            .$this->CEFheaderA_133_142
            .$this->CEFheaderA_143_143
            .$this->CEFheaderA_144_151
            .$this->CEFheaderA_152_157
            .$this->CEFheaderA_158_163
            .$this->CEFheaderA_164_166
            .$this->CEFheaderA_167_171
            .$this->CEFheaderA_172_191
            .$this->CEFheaderA_192_211
            .$this->CEFheaderA_212_222
            .$this->CEFheaderA_223_225
            .$this->CEFheaderA_226_228
            .$this->CEFheaderA_229_230
            .$this->CEFheaderA_231_240
       ."\r\n"
       //.chr(13).chr(10)
    );
    }
    function geraHEADERLoteCEF(){
        //segundo cabeçalho
          fputs($this->arquivo,
          $this->CEFheaderL_001_003
          .$this->CEFheaderL_004_007
          .$this->CEFheaderL_008_008
          .$this->CEFheaderL_009_009
          .$this->CEFheaderL_010_011
          .$this->CEFheaderL_012_013
          .$this->CEFheaderL_014_016
          .$this->CEFheaderL_017_017
          .$this->CEFheaderL_018_018
          .$this->CEFheaderL_019_032
          .$this->CEFheaderL_033_038
          .$this->CEFheaderL_039_040
          .$this->CEFheaderL_041_044
          .$this->CEFheaderL_045_046
          .$this->CEFheaderL_047_052
          .$this->CEFheaderL_053_057
          .$this->CEFheaderL_058_058
          .$this->CEFheaderL_059_070
          .$this->CEFheaderL_071_071
          .$this->CEFheaderL_072_072
          .$this->CEFheaderL_073_102
          .$this->CEFheaderL_103_142
          .$this->CEFheaderL_143_172
          .$this->CEFheaderL_173_177
          .$this->CEFheaderL_178_192
          .$this->CEFheaderL_193_212
          .$this->CEFheaderL_213_217
          .$this->CEFheaderL_218_220
          .$this->CEFheaderL_221_222
          .$this->CEFheaderL_223_230
          .$this->CEFheaderL_231_240
		 ."\r\n"
		// .chr(13).chr(10)
	  );
    }
    function geraREGISTROSCEF(){
        fputs($this->arquivo,
                $this->CEFregistA_001_003
                .$this->CEFregistA_004_007
                .$this->CEFregistA_008_008
                .$this->CEFregistA_009_013
                .$this->CEFregistA_014_014
                .$this->CEFregistA_015_015
                .$this->CEFregistA_016_017
                .$this->CEFregistA_018_020
                .$this->CEFregistA_021_023
                .$this->CEFregistA_024_028
                .$this->CEFregistA_029_029
                .$this->CEFregistA_030_041
                .$this->CEFregistA_042_042
                .$this->CEFregistA_043_043
                .$this->CEFregistA_044_073
                .$this->CEFregistA_074_079
                .$this->CEFregistA_080_092
                .$this->CEFregistA_093_093
                .$this->CEFregistA_094_101
                .$this->CEFregistA_102_104
                .$this->CEFregistA_105_119
                .$this->CEFregistA_120_134
                .$this->CEFregistA_135_143
                .$this->CEFregistA_144_146 
                .$this->CEFregistA_147_148 
                .$this->CEFregistA_149_149 
                .$this->CEFregistA_150_150 
                .$this->CEFregistA_151_152 
                .$this->CEFregistA_153_154 
                .$this->CEFregistA_155_162 
                .$this->CEFregistA_163_177 
                .$this->CEFregistA_178_217 
                .$this->CEFregistA_218_219 
                .$this->CEFregistA_220_229 
                .$this->CEFregistA_230_230 
                .$this->CEFregistA_231_240


           ."\r\n"
           //.chr(13).chr(10)
             );
        fputs($this->arquivo,
                $this->CEFregistB_001_003
                .$this->CEFregistB_004_007
                .$this->CEFregistB_008_008
                .$this->CEFregistB_009_013
                .$this->CEFregistB_014_014
                .$this->CEFregistB_015_017
                .$this->CEFregistB_018_018
                .$this->CEFregistB_019_032
                .$this->CEFregistB_033_062
                .$this->CEFregistB_063_067
                .$this->CEFregistB_068_082
                .$this->CEFregistB_083_097
                .$this->CEFregistB_098_117
                .$this->CEFregistB_118_122
                .$this->CEFregistB_123_125
                .$this->CEFregistB_126_127
                .$this->CEFregistB_128_135
                .$this->CEFregistB_136_150
                .$this->CEFregistB_151_165
                .$this->CEFregistB_166_180
                .$this->CEFregistB_181_195
                .$this->CEFregistB_196_210
                .$this->CEFregistB_211_225
                .$this->CEFregistB_226_240
           ."\r\n"
           //.chr(13).chr(10)
             );
      }
      function geraTRAILLERLoteCEF(){
        
        fputs($this->arquivo,
                $this->CEFtraillerL_001_003
                .$this->CEFtraillerL_004_007
                .$this->CEFtraillerL_008_008
                .$this->CEFtraillerL_009_017
                .$this->CEFtraillerL_018_023
                .$this->CEFtraillerL_024_041
                .$this->CEFtraillerL_042_059
                .$this->CEFtraillerL_060_065
                .$this->CEFtraillerL_066_230
                .$this->CEFtraillerL_231_240
           //.chr(13).chr(10)
           ."\r\n"
             );
      }

      function geraTRAILLERArquivoCEF(){
        
        fputs($this->arquivo,
            $this->CEFtraillerA_001_003
           .$this->CEFtraillerA_004_007
           .$this->CEFtraillerA_008_008
           .$this->CEFtraillerA_009_017
           .$this->CEFtraillerA_018_023
           .$this->CEFtraillerA_024_029
           .$this->CEFtraillerA_030_035
           .$this->CEFtraillerA_036_240
       //.chr(13).chr(10)
       ."\r\n"
         );
    }
    
//////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////// FINAL MÉTODOS QUE GERAM TRAILLERS ////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////
//          FECHA O ARQUIVO           //
////////////////////////////////////////
    function gera(){
       fclose($this->arquivo);
    }
////////////////////////////////////////
}
?>