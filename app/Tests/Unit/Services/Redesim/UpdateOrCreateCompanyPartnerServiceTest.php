<?php

namespace App\Tests\Unit\Services\Redesim;

use App\Models\Cgm;
use App\Models\Issbase;
use App\Models\ISSQN\IssbaseLog;
use App\Models\Socio;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\CompanyDTO;
use App\Services\Tributario\ISSQN\CreateIssbaseLogService;
use App\Services\Tributario\ISSQN\Redesim\Alvara\UpdateOrCreateCompanyPartnerService;
use App\Tests\Fakes\FakeEloquent;
use BusinessException;
use LogicException;
use Mockery;
use PHPUnit\Framework\TestCase;

class UpdateOrCreateCompanyPartnerServiceTest extends TestCase
{
    public function testShoudUpdateOrCreateCompanyPartner()
    {
        $mockModelCgmSocio = new Cgm([
            'z01_numcgm' => 123,
            'z01_nome' => 'Fake',
            'z01_cgccpf' => '89199406833'
        ]);

        $mockModelCgmSocio->exists = true;

        $mockIssbaseModelData = new Issbase(
            ['q02_inscr' => 528]
        );

        $cgmSocioMock = Mockery::mock(Cgm::class);

        $cgmSocioFakeEloquent = new FakeEloquent($mockModelCgmSocio);

        $cgmSocioMock->shouldReceive('where')->once()->andReturn($cgmSocioFakeEloquent->mockBuilder);
        $cgmSocioMock->shouldReceive('get')->once()->andReturn($cgmSocioFakeEloquent->mockBuilder);

        $mockModelCgmEmpresa = new Cgm([
            'z01_numcgm' => 1234,
            'z01_nome' => 'Fake',
            'z01_cgccpf' => '53050958333207'
        ]);

        $mockDataRedesim = new CompanyDTO(
            [
                'cpfCnpj' => '53050958333207',
                'socios' => [[
                    'cpf' => $mockModelCgmSocio->z01_cgccpf,
                    'inicio' => '23/02/2021 12:00',
                    'representanteLegal' => true,
                    'nome' => 'QMNLFZaZGZQMCLVZaBMQFZFVMOaGMQMLQZ',
                    'inclusao' => '04/10/2022 11:28'
                ]]
            ]
        );

        $mockModelSocio = new Socio([
            'q95_cgmpri' => $mockModelCgmEmpresa->z01_numcgm,
            'q95_numcgm' => $mockModelCgmSocio->z01_numcgm,
            'q95_perc' => '',
            'q95_tipo' => '',
        ]);

        $socioMock = Mockery::mock(Socio::class);

        $socioMock->shouldReceive('updateOrCreate')->once()->andReturn($mockModelSocio);

        $createIssbaseLogServiceMock = Mockery::mock(CreateIssbaseLogService::class);
        $createIssbaseLogServiceMock->shouldReceive('execute')->once()->andReturn((new IssbaseLog()));

        $service = new UpdateOrCreateCompanyPartnerService($cgmSocioMock, $socioMock, $createIssbaseLogServiceMock);

        $service->execute($mockDataRedesim, $mockModelCgmEmpresa, $mockIssbaseModelData);

        $this->assertTrue(true);
    }

    /**
     * @throws BusinessException
     */
    public function testShoudNotCompanyPartnerWithoutCgmPartner()
    {
        $mockModelCgmSocio = new Cgm();

        $mockIssbaseModelData = new Issbase(
            ['q02_inscr' => 528]
        );

        $cgmSocioMock = Mockery::mock(Cgm::class);

        $cgmSocioFakeEloquent = new FakeEloquent($mockModelCgmSocio);

        $cgmSocioMock->shouldReceive('where')->once()->andReturn($cgmSocioFakeEloquent->mockBuilder);
        $cgmSocioMock->shouldReceive('get')->once()->andReturn($cgmSocioFakeEloquent->mockBuilder);

        $mockModelCgmEmpresa = new Cgm([
            'z01_numcgm' => 1234,
            'z01_nome' => 'Fake',
            'z01_cgccpf' => '53050958333207'
        ]);

        $mockDataRedesim = new CompanyDTO(
            [
                'cpfCnpj' => '53050958333207',
                'socios' => [[
                    'cpf' => '89199406833',
                    'inicio' => '23/02/2021 12:00',
                    'representanteLegal' => true,
                    'nome' => 'QMNLFZaZGZQMCLVZaBMQFZFVMOaGMQMLQZ',
                    'inclusao' => '04/10/2022 11:28'
                ]]
            ]
        );

        $mockModelSocio = new Socio([
            'q95_cgmpri' => $mockModelCgmEmpresa->z01_numcgm,
            'q95_numcgm' => $mockModelCgmSocio->z01_numcgm,
            'q95_perc' => '',
            'q95_tipo' => '',
        ]);

        $socioMock = Mockery::mock(Socio::class);

        $socioMock->shouldReceive('updateOrCreate')->once()->andReturn($mockModelSocio);

        $createIssbaseLogServiceMock = Mockery::mock(CreateIssbaseLogService::class);
        $createIssbaseLogServiceMock->shouldReceive('execute')->once()->andReturn((new IssbaseLog()));

        $service = new UpdateOrCreateCompanyPartnerService($cgmSocioMock, $socioMock, $createIssbaseLogServiceMock);

        $this->expectException(LogicException::class);

        $service->execute($mockDataRedesim, $mockModelCgmEmpresa, $mockIssbaseModelData);
    }
}
