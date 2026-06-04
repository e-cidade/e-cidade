<?php 

namespace App\Tests\Unit\Services;

use App\Helpers\Matematica;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Para realizar os testes basta rodar php artisan test --filter MatematicaTest
 */
class MatematicaTest extends TestCase
{
    public function testSubtracaoComDiferencaMinima()
    {
        $this->assertSame('0.0000001', Matematica::subtrair('1.0000001', '1.0000000', 7));
    }

    public function testSomaComPrecisao()
    {
        $this->assertSame('0.24691357824691200', Matematica::somar('0.123456789123456', '0.123456789123456', 17), 'Soma com precisão máxima');
    }
    
    public function testMultiplicacaoComArredondamento()
    {
        $this->assertSame('0.4113', Matematica::multiplicar('1.234', '0.3333', 4), 'Multiplicação com arredondamento');
    }

    public function testDivisaoSimples()
    {
        $this->assertSame('0.3333', Matematica::dividir('1', '3', 4), 'Divisão simples');
    }

    public function testDivisaoComFracaoLonga()
    {
        $this->assertSame('1.4285714286', Matematica::dividir('10', '7', 10), 'Divisão com fração longa');
    }

    public function testComparacaoComPrecisaoMaximaIguais()
    {
        $this->assertSame(0, Matematica::comparar('1.1234567891234567', '1.1234567891234567'), 'Comparação com precisão máxima (iguais)');
    }

    public function testComparacaoComDiferencaInfima()
    {
        $this->assertSame(1, Matematica::comparar('1.000000000000001', '1.000000000000000'), 'Comparação com diferença ínfima');
    }

    public function testPercentualPequenoSobreGrande()
    {
        $this->assertSame('0.010000', Matematica::percentual('1', '10000', 6), 'Percentual pequeno sobre grande');
    }

    public function testPercentualNormal()
    {
        $this->assertSame('12.50', Matematica::percentual('25', '200'), 'Percentual normal');
    }
    
    public function testSomaComEscalaZero()
    {
        $this->assertSame('6', Matematica::somar('2.9', '2.9', 0), 'Soma com escala zero');
    }

    public function testSubtracaoComEscalaZero()
    {
        $this->assertSame('7', Matematica::subtrair('10.9', '4.1', 0), 'Subtração com escala zero');
    }

    public function testMultiplicacaoComEscalaDezesseis()
    {
        $this->assertSame('0.1219318122240000', Matematica::multiplicar('0.123456', '0.987654', 16), 'Multiplicação com escala 16');
    }

    public function testEscalaForaDoIntervaloLancaExcecao()
    {
        try {
            Matematica::dividir(1, 1, 20);
            $this->fail('Divisão com escala inválida deveria lançar exceção');
        } catch (InvalidArgumentException $e) {
            $this->assertSame(992, $e->getCode(), 'Escala inválida lança exceção');
        }
    }

    public function testComparacaoComEscalaZero()
    {
        $this->assertSame(0, Matematica::comparar('1.9', '1.1', 0), 'Comparação com escala zero');
    }

    public function testComparacaoComZerosFinais()
    {
        $this->assertSame(0, Matematica::comparar('1.1000000000', '1.1'), 'Comparação com zeros finais');
    }

    public function testSomaComNegativo()
    {
        $this->assertSame('-1.30', Matematica::somar('-2.5', '1.2', 2), 'Soma com número negativo');
    }

    public function testSubtracaoQueResultaEmNegativo()
    {
        $this->assertSame('-1.30', Matematica::subtrair('1.2', '2.5', 2), 'Subtração que resulta em número negativo');
    }

    public function testMultiplicacaoDeDoisNegativos()
    {
        $this->assertSame('7.00', Matematica::multiplicar('-2', '-3.5', 2), 'Multiplicação de dois números negativos');
    }

    public function testDivisaoNegativoPorPositivo()
    {
        $this->assertSame('-2.500', Matematica::dividir('-10', '4', 3), 'Divisão de número negativo por positivo');
    }

    public function testDivisaoPositivoPorNegativo()
    {
        $this->assertSame('-2.500', Matematica::dividir('10', '-4', 3), 'Divisão de número positivo por negativo');
    }

    public function testComparacaoDeNegativos()
    {
        $this->assertSame(-1, Matematica::comparar('-1.00000001', '-1.00000000', 8), 'Comparação entre negativos');
    }

    public function testComparacaoEntrePositivoENegativo()
    {
        $this->assertSame(1, Matematica::comparar('0.00001', '-0.00001'), 'Comparação entre número positivo e negativo');
    }

    public function testPercentualPequenoFracionado()
    {
        $this->assertSame('0.0000001000', Matematica::percentual('0.00001', '9999.99', 10), 'Percentual com resultado fracionário pequeno');
    }


    public function testSomaComZero()
    {
        $this->assertSame('0.00000', Matematica::somar('0', '0.0000', 5), 'Soma com zero');
    }

    public function testSubtracaoComZero()
    {
        $this->assertSame('0.00000', Matematica::subtrair('0', '0.0000', 5), 'Subtração com zero');
    }

    public function testMultiplicacaoComZero()
    {
        $this->assertSame('0.00000000', Matematica::multiplicar('123.456', '0.0000', 8), 'Multiplicação com zero');
    }

    public function testDivisaoDeZeroPorAlgo()
    {
        $this->assertSame('0.000000', Matematica::dividir('0', '123.456', 6), 'Divisão de zero por algo');
    }

    public function testComparacaoZeroComZero()
    {
        $this->assertSame(0, Matematica::comparar('0.0000000', '0'), 'Comparação zero com zero');
    }

    public function testMultiplicacaoDigitosLongos()
    {
        $this->assertSame('121932631356499712.458314', Matematica::multiplicar('123456789.123456', '987654321.987654', 6), 'Multiplicação com muitos dígitos');
    }

    public function testSomaComExcessoDeCasasDecimais()
    {
        $this->assertSame('2.00000', Matematica::somar('1.9999999', '0.0000001', 5), 'Soma com excesso de casas decimais');
    }

    public function testSubtracaoComTruncamento()
    {
        $this->assertSame('100.0000', Matematica::subtrair('100.00001', '0.000009', 4), 'Subtração com truncamento');
    }

    public function testPercentualComValorNegativo()
    {
        $this->assertSame('-5.00', Matematica::percentual('-5', '100', 2), 'Percentual com valor negativo');
    }

    public function testPercentualComBaseNegativa()
    {
        $this->assertSame('-5.00', Matematica::percentual('5', '-100', 2), 'Percentual com base negativa');
    }

    public function testComparacaoDeGrandesValoresIguais()
    {
        $this->assertSame(0, Matematica::comparar('123456789012345.12345678', '123456789012345.12345678'), 'Comparação de grandes valores iguais');
    }

    public function testSomaComInteiros()
    {
        $this->assertSame('15', Matematica::somar(10, 5, 0), 'Soma com inteiros');
    }

    public function testSubtracaoComInteiros()
    {
        $this->assertSame('12', Matematica::subtrair(20, 8, 0), 'Subtração com inteiros');
    }

    public function testMultiplicacaoComInteiros()
    {
        $this->assertSame('42', Matematica::multiplicar(7, 6, 0), 'Multiplicação com inteiros');
    }

    public function testDivisaoComInteiros()
    {
        $this->assertSame('2.500', Matematica::dividir(10, 4, 3), 'Divisão com inteiros');
    }

    public function testSomaComStringEInteiro()
    {
        $this->assertSame('15.5', Matematica::somar('10.5', 5, 1), 'Soma com string e inteiro');
    }

    public function testSubtracaoComStringEInteiro()
    {
        $this->assertSame('12.75', Matematica::subtrair('20.75', 8, 2), 'Subtração com string e inteiro');
    }

    public function testMultiplicacaoComStringEInteiro()
    {
        $this->assertSame('42.6', Matematica::multiplicar('7.1', 6, 1), 'Multiplicação com string e inteiro');
    }

    public function testDivisaoComStringEInteiro()
    {
        $this->assertSame('2.625', Matematica::dividir('10.5', 4, 3), 'Divisão com string e inteiro');
    }

    public function testSomaComNegativosInteiros()
    {
        $this->assertSame('-15', Matematica::somar(-5, -10, 0), 'Soma com negativos inteiros');
    }

    public function testSubtracaoComNegativosInteiros()
    {
        $this->assertSame('-5', Matematica::subtrair(-10, -5, 0), 'Subtração com negativos inteiros');
    }

    public function testMultiplicacaoComNegativosInteiros()
    {
        $this->assertSame('-20', Matematica::multiplicar(-4, 5, 0), 'Multiplicação com negativos inteiros');
    }

    public function testDivisaoComNegativosInteiros()
    {
        $this->assertSame('-5.00', Matematica::dividir(-20, 4, 2), 'Divisão com negativos inteiros');
    }

    public function testMultiplicacaoAltaPrecisaoArredondamento2()
    {
        $this->assertSame('121932632103337905.66', Matematica::multiplicar('123456789.987654321', '987654321.123456789', 2), 'Multiplicação alta precisão arredondamento 2');
    }

    public function testMultiplicacaoAltaPrecisaoArredondamento8()
    {
        $this->assertSame('12193263124980.86022114', Matematica::multiplicar('1234567.89123456', '9876543.21123456', 8), 'Multiplicação alta precisão arredondamento 8');
    }

    public function testDivisaoGrandeComArredondamento4()
    {
        $this->assertSame('124999.9990', Matematica::dividir('123456789123456', '987654321', 4), 'Divisão grande com arredondamento 4');
    }

    public function testMultiplicacaoArredondamentoZeroTruncar()
    {
        $this->assertSame('2896', Matematica::multiplicar('1234.5678', '2.3456', 0), 'Multiplicação arredondamento zero truncar');
    }

    public function testDivisaoMisturadaAltaEscala()
    {
        $this->assertSame('8.000000073', Matematica::dividir('987654321', 123456789, 9), 'Divisão misturada alta escala');
    }

    public function testMultiplicacaoPequenosNumerosEscala12()
    {
        $this->assertSame('0.000000000000', Matematica::multiplicar('0.000000123456', '0.000000987654', 12), 'Multiplicação pequenos números escala 12');
    }

    public function testDivisaoPequenosNumerosEscala12()
    {
        $this->assertSame('8.000048600311', Matematica::dividir('0.000000987654', '0.000000123456', 12), 'Divisão pequenos números escala 12');
    }

    public function testDivisaoArredondamento3ParaBaixo()
    {
        $this->assertSame('3.334', Matematica::dividir('10.001', '3', 3), 'Divisão arredondamento 3 para baixo');
    }

    public function testMultiplicacaoNegativosGrandesEscala5()
    {
        $this->assertSame('12193263111263.52690', Matematica::multiplicar('-1234567.89', '-9876543.21', 5), 'Multiplicação negativos grandes escala 5');
    }

    public function testDivisaoNegativosGrandesEscala7()
    {
        $this->assertSame('8.0000001', Matematica::dividir('-987654321', '-123456789', 7), 'Divisão negativos grandes escala 7');
    }

    public function testSomaComValorNaoNumericoLancaExcecao()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(990);
        Matematica::somar('abc', '1');
    }

    public function testSubtracaoComValorNaoNumericoLancaExcecao()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(990);
        Matematica::subtrair('1', 'xyz');
    }

    public function testMultiplicacaoComValorNaoNumericoLancaExcecao()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(990);
        Matematica::multiplicar('texto', '5');
    }

    public function testDivisaoComValorNaoNumericoLancaExcecao()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(990);
        Matematica::dividir('10', 'texto');
    }

    public function testDivisaoPorZeroLancaExcecao()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(991);
        Matematica::dividir('10', '0');
    }

    public function testSomaComEscalaNegativaLancaExcecao()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(992);
        Matematica::somar('1', '1', -1);
    }

    public function testMultiplicacaoComEscalaMuitoAltaLancaExcecao()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(992);
        Matematica::multiplicar('1', '1', 20);
    }

    public function testComparacaoComValorNaoNumericoLancaExcecao()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(990);
        Matematica::comparar('1', 'abc');
    }

    public function testPercentualComValorNaoNumericoLancaExcecao()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(990);
        Matematica::percentual('abc', '100');
    }

    public function testPercentualComBaseZeroLancaExcecao()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(991);
        Matematica::percentual('50', '0');
    }

    public function testDivisaoComEscalaNegativaLancaExcecao()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(992);
        Matematica::dividir('1', '1', -5);
    }

    public function testSubtracaoComEscalaMaiorQue17LancaExcecao()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(992);
        Matematica::subtrair('1', '1', 18);
    }

    public function testArredondamentoParaCima()
    {
        $this->assertSame('1.23457', Matematica::arredondar('1.23456789', 5), 'Arredondamento para cima');
    }

    public function testArredondamentoParaBaixo()
    {
        $this->assertSame('1.234', Matematica::arredondar('1.2344', 3), 'Arredondamento para baixo');
    }

    public function testArredondamentoValorExato()
    {
        $this->assertSame('2.5000', Matematica::arredondar('2.50000', 4), 'Arredondamento valor exato');
    }

    public function testArredondamentoComEscalaZero()
    {
        $this->assertSame('3', Matematica::arredondar('2.9', 0), 'Arredondamento com escala zero');
    }

    public function testArredondamentoValorNegativo()
    {
        $this->assertSame('-1.2346', Matematica::arredondar('-1.234567', 4), 'Arredondamento de valor negativo');
    }

    public function testArredondamentoComEscalaNegativaLancaExcecao()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(992);
        Matematica::arredondar('1.23', -1);
    }

    public function testArredondamentoComEscalaMaiorQue17LancaExcecao()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(992);
        Matematica::arredondar('1.23', 18);
    }

    public function testArredondamentoComValorNaoNumericoLancaExcecao()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(990);
        Matematica::arredondar('abc', 2);
    }

    public function testMultiplicacaoComEscalaMaxima()
    {
        $this->assertSame('2.89589986832799940', Matematica::multiplicar('1.2345678912345678', '2.3456789123456789', 17), 'Multiplicação com escala máxima 17');
    }

    public function testSomaComEscalaMaxima()
    {
        $this->assertSame('3.99999999999999999', Matematica::somar('1.12345678912345678', '2.87654321087654321', 17), 'Soma com escala máxima 17');
    }

    public function testDivisaoComEscalaMaxima17()
    {
        $this->assertSame('3.33333333333333333', Matematica::dividir('10', '3', 17), 'Divisão com escala máxima 17');
    }

    public function testSubtracaoComEscalaMaxima17()
    {
        $this->assertSame('7.00000000000000000', Matematica::subtrair('10', '3', 17), 'Subtração com escala máxima 17');
    }

    public function testMultiplicacaoNumeroMuitoPequenoEscala17()
    {
        $this->assertSame('0.00000000000000000', Matematica::multiplicar('0.00000000000000001', '0.00000000000000002', 17), 'Multiplicação número muito pequeno escala 17');
    }

    public function testDivisaoNumeroMuitoPequenoEscala17()
    {
        $this->assertSame('0.00000000000000000', Matematica::dividir('0.00000000000000001', '2', 17), 'Divisão número muito pequeno escala 17');
    }

    public function testArredondarZeroEscala5()
    {
        $this->assertSame('0.00000', Matematica::arredondar('0', 5), 'Arredondar zero para escala 5');
    }

    public function testArredondarZeroEscala0()
    {
        $this->assertSame('0', Matematica::arredondar('0', 0), 'Arredondar zero para escala 0');
    }

    public function testArredondarValoresMuitoGrandesEscala2()
    {
        $this->assertSame('123456789012345.68', Matematica::arredondar('123456789012345.6789', 2), 'Arredondar valores muito grandes escala 2');
    }

    public function testArredondarNegativo4Casas()
    {
        $this->assertSame('-1.2346', Matematica::arredondar('-1.234567', 4), 'Arredondar -1.234567 para 4 casas decimais');
    }

    public function testArredondarNegativoParaCima4Casas()
    {
        $this->assertSame('-1.2345', Matematica::arredondar('-1.234543', 4), 'Arredondar -1.234543 para 4 casas decimais');
    }

    public function testArredondarZero3Casas()
    {
        $this->assertSame('0.000', Matematica::arredondar('0', 3), 'Arredondar 0 para 3 casas decimais');
    }

    public function testArredondarProximoZeroPositivo3Casas()
    {
        $this->assertSame('0.000', Matematica::arredondar('0.00049', 3), 'Arredondar 0.00049 para 3 casas decimais');
    }

    public function testArredondarProximoZeroNegativo3Casas()
    {
        $this->assertSame('0.000', Matematica::arredondar('-0.00049', 3), 'Arredondar -0.00049 para 3 casas decimais');
    }

    public function testArredondarValorGrandePositivo2Casas()
    {
        $this->assertSame('123456789.99', Matematica::arredondar('123456789.987654321', 2), 'Arredondar 123456789.987654321 para 2 casas decimais');
    }

    public function testArredondarValorGrandeNegativo2Casas()
    {
        $this->assertSame('-123456789.99', Matematica::arredondar('-123456789.987654321', 2), 'Arredondar -123456789.987654321 para 2 casas decimais');
    }

    public function testArredondarLimiteParaCima4Casas()
    {
        $this->assertSame('5.0000', Matematica::arredondar('4.99995', 4), 'Arredondar 4.99995 para 4 casas decimais');
    }
    public function testArredondarLimiteParaBaixo4Casas()
    {
        $this->assertSame('4.9999', Matematica::arredondar('4.99994', 4), 'Arredondar 4.99994 para 4 casas decimais');
    }

        public function testExemploUmAbnt()
    {
        $this->assertSame('1.3', Matematica::arredondar('1.3333', 1), 'Arredondar 1.3333 para 1 casa decimal conforme primeiro exeplmo da NBR 5891:2014');
    }

    public function testExemploDoisAbnt()
    {
        $this->assertSame('1.7', Matematica::arredondar('1.6666', 1), 'Arredondar 1.6666 para 1 casa decimal conforme segundo exeplmo da NBR 5891:2014');
    }

    public function testExemploTresAbnt()
    {
        $this->assertSame('4.9', Matematica::arredondar('4.8505', 1), 'Arredondar 4.8505 para 1 casa decimal conforme terceiro exeplmo da NBR 5891:2014');
    }

    public function testExemploQuatroAbnt()
    {
        $this->assertSame('4.6', Matematica::arredondar('4.5500', 1), 'Arredondar 4.5500 para 1 casa decimal conforme quarto exeplmo da NBR 5891:2014');
    }

    public function testExemploCincoAbnt()
    {
        $this->assertSame('4.8', Matematica::arredondar('4.8500', 1), 'Arredondar 4.8500 para 1 casa decimal conforme quinto exeplmo da NBR 5891:2014');
    }
}
