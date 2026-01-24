<?php

namespace Tests\Unit\Services;

use App\Services\FinanceService;
use PHPUnit\Framework\TestCase;

/**
 * We use a proxy class to test protected methods without changing the original code's visibility
 * or we just make the method public in the original class if it's considered part of the "logic unit".
 * For this exercise, I will test it via a public wrapper or reflection.
 */
class FinanceServiceTest extends TestCase
{
    protected $financeService;

    protected function setUp(): void
    {
        parent::setUp();
        $courseService = $this->createMock(\App\Services\CourseService::class);
        $whatsappService = $this->createMock(\App\Services\WhatsAppService::class);
        $this->financeService = new FinanceService($courseService, $whatsappService);
    }

    /** @test */
    public function it_determines_status_as_paid_when_paid_is_equal_to_total()
    {
        $status = $this->invokeMethod($this->financeService, 'determineStatus', [100, 100]);
        $this->assertEquals('paid', $status);
    }

    /** @test */
    public function it_determines_status_as_paid_when_paid_is_greater_than_total()
    {
        $status = $this->invokeMethod($this->financeService, 'determineStatus', [100, 120]);
        $this->assertEquals('paid', $status);
    }

    /** @test */
    public function it_determines_status_as_partial_when_paid_is_less_than_total_but_greater_than_zero()
    {
        $status = $this->invokeMethod($this->financeService, 'determineStatus', [100, 50]);
        $this->assertEquals('partial', $status);
    }

    /** @test */
    public function it_determines_status_as_pending_when_paid_is_zero()
    {
        $status = $this->invokeMethod($this->financeService, 'determineStatus', [100, 0]);
        $this->assertEquals('pending', $status);
    }

    /**
     * Helper to call protected methods for unit testing.
     */
    protected function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
