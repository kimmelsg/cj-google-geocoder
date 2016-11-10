<?php

namespace NavJobs\GoogleGeocoder\Test;

use Mockery;
use GuzzleHttp\Client;
use PHPUnit_Framework_TestCase;

abstract class TestCase extends PHPUnit_Framework_TestCase
{

    /**
     * @param $responseData
     * @return Client
     */
    protected function getMockClient($responseData)
    {
        return Mockery::mock(Client::class)
            ->shouldReceive('get')
            ->andReturnSelf()
            ->shouldReceive('getBody')
            ->andReturn($responseData)
            ->mock();
    }
}
