<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\BasicWAF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class BasicWAFTest extends TestCase
{
    protected $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new BasicWAF;
    }

    /** @test */
    public function it_allows_normal_requests()
    {
        $request = Request::create('/test', 'GET', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $response = $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_blocks_sql_injection_attempts()
    {
        Log::shouldReceive('channel->warning')->once();

        // Use pattern that matches WAF regex: select...from or or/and with 1=1
        $request = Request::create('/test', 'POST', [
            'search' => '1 OR 1=1',
        ]);

        $response = $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_blocks_xss_attempts()
    {
        Log::shouldReceive('channel->warning')->once();

        $request = Request::create('/test', 'POST', [
            'content' => '<script>alert("XSS")</script>',
        ]);

        $response = $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_blocks_path_traversal_attempts()
    {
        Log::shouldReceive('channel->warning')->once();

        $request = Request::create('/test', 'GET', [
            'file' => '../../etc/passwd',
        ]);

        $response = $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_blocks_javascript_protocol_injection()
    {
        Log::shouldReceive('channel->warning')->once();

        $request = Request::create('/test', 'POST', [
            'url' => 'javascript:alert(1)',
        ]);

        $response = $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_blocks_event_handler_injection()
    {
        Log::shouldReceive('channel->warning')->once();

        $request = Request::create('/test', 'POST', [
            'input' => '<img onerror=alert(1)>',
        ]);

        $response = $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_blocks_union_select_sql_injection()
    {
        Log::shouldReceive('channel->warning')->once();

        $request = Request::create('/test', 'GET', [
            'id' => '1 UNION SELECT * FROM users WHERE 1=1',
        ]);

        $response = $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });

        $this->assertEquals(403, $response->getStatusCode());
    }
}
