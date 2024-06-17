<?php

namespace App\Tests\Unit\Services;

use App\Models\ISSQN\IssbaseLog;
use App\Services\Tributario\ISSQN\CreateIssbaseLogService;
use Mockery;
use PHPUnit\Framework\TestCase;

class CreateIssbaseLogServiceTest extends TestCase
{
    public function testExecute()
    {
        $mockData = new IssbaseLog([
            'q102_inscr' => 123,
            'q102_issbaselogtipo' => 1,
            'q102_data' => '2024-05-21',
            'q102_hora' => '08:43',
            'q102_obs' => 'INCLUSAO',
            'q102_origem' => 1,
        ]);

        $model = Mockery::mock(IssbaseLog::class);

        $model->shouldReceive('create')->once()->andReturn($mockData);

        $service = new CreateIssbaseLogService($model);

        $result = $service->execute(
            $mockData['q102_inscr'],
            $mockData['q102_origem'],
            $mockData['q102_issbaselogtipo'],
            $mockData['q102_obs'],
        );

        $this->assertEquals($result, $mockData);
    }
}
