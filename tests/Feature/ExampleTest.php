<?php

namespace Tests\Feature;

use Someline\Services\ZhangYueService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testZhangYueApi()
    {
        $zhangYueService = new ZhangYueService();
        $result = $zhangYueService->fetchBookInfo('10004510');
        print_r($result);
    }
}
