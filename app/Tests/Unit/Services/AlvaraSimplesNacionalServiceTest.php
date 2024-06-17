<?php

namespace App\Tests\Unit\Services;

use App\Models\ISSQN\IssCadSimples;
use App\Models\ISSQN\IssCadSimplesBaixa;
use App\Models\ISSQN\IssMotivoBaixa;
use App\Services\Tributario\ISSQN\AlvaraSimplesNacionalService;
use DateTime;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase;

class AlvaraSimplesNacionalServiceTest extends TestCase
{
    private IssCadSimples $issCadSimples;
    private IssCadSimplesBaixa $issCadSimplesBaixa;
    private DateTime $startDate;
    private DateTime $endDate;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->startDate = new DateTime();
        $this->endDate = new DateTime();
        $this->issCadSimples = new IssCadSimples([
            'q38_sequencial' => 123,
            'q38_inscr' => 123,
            'q38_dtinicial' => $this->startDate->format('Y-m-d'),
            'q38_categoria' => IssCadSimples::CATEGORIA_ME,
        ]);

        $this->issCadSimples->exists = true;

        $this->issCadSimplesBaixa = new IssCadSimplesBaixa(
            [
                'q39_sequencial' => 1,
                'q39_isscadsimples' => $this->issCadSimples->q38_sequencial,
                'q39_dtbaixa' => $this->endDate->format('Y-m-d'),
                'q39_issmotivobaixa' => IssMotivoBaixa::MOTIVO_BAIXA_REDESIM,
                'q39_obs' => 'test'
            ]
        );
        $this->issCadSimplesBaixa->exists = true;

        parent::__construct($name, $data, $dataName);
    }

    public function testShouldEnquadrarOptanteSimples()
    {
        $issCadSimplesMock = Mockery::mock(IssCadSimples::class);

        $issCadSimplesMock->shouldReceive('updateOrCreate')->once()->andReturn($this->issCadSimples);

        $service = new AlvaraSimplesNacionalService($issCadSimplesMock, new IssCadSimplesBaixa());
        $service->execute($this->issCadSimples->q38_sequencial, $this->startDate, $this->issCadSimples->q38_categoria);

        $this->assertTrue(true);
    }

    public function testShouldDesenquadrarOptanteSimples()
    {
        $issCadSimplesMock = Mockery::mock(IssCadSimples::class);
        $issCadSimplesMock->shouldReceive('updateOrCreate')->once()->andReturn($this->issCadSimples);

        $issCadSimplesBaixa = Mockery::mock(IssCadSimplesBaixa::class);
        $issCadSimplesBaixa->shouldReceive('updateOrCreate')->once()->andReturn($this->issCadSimplesBaixa);

        $service = new AlvaraSimplesNacionalService($issCadSimplesMock, $issCadSimplesBaixa);
        $service->execute(
            $this->issCadSimples->q38_sequencial,
            $this->startDate,
            $this->issCadSimples->q38_categoria,
            $this->endDate,
            IssMotivoBaixa::MOTIVO_BAIXA_REDESIM,
            'test'
        );

        $this->assertTrue(true);
    }

    public function testShouldNotDesenquadrarOptanteSimplesWithOutMotivoBaixa()
    {
        $issCadSimplesMock = Mockery::mock(IssCadSimples::class);
        $issCadSimplesMock->shouldReceive('create')->once()->andReturn($this->issCadSimples);

        $issCadSimplesBaixa = Mockery::mock(IssCadSimplesBaixa::class);
        $issCadSimplesBaixa->shouldReceive('updateOrCreate')->once()->andReturn($this->issCadSimplesBaixa);

        $service = new AlvaraSimplesNacionalService($issCadSimplesMock, $issCadSimplesBaixa);

        $this->expectException(InvalidArgumentException::class);

        $service->execute(
            $this->issCadSimples->q38_sequencial,
            $this->startDate,
            $this->issCadSimples->q38_categoria,
            $this->endDate,
            null,
            'test'
        );
    }
}
