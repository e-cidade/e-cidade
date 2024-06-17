<?php

namespace App\Tests\Fakes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Mockery;

class FakeEloquent
{
    public $mockBuilder;
    public function __construct(Model $model)
    {
        $bulderMock = Mockery::mock(Builder::class);

        $bulderMock->shouldReceive('where','get')->once()->andReturnSelf();
        $bulderMock->shouldReceive('first')->once()->andReturn($model);

        $this->mockBuilder = $bulderMock;
        return $this;
    }
}
