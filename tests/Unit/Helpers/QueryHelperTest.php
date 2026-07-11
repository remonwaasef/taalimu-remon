<?php

namespace Tests\Unit\Helpers;

use App\Helpers\QueryHelper;
use PHPUnit\Framework\TestCase;

class QueryHelperTest extends TestCase
{
    /** @test */
    public function it_escapes_percent_sign()
    {
        $this->assertEquals('100\%', QueryHelper::escapeLike('100%'));
    }

    /** @test */
    public function it_escapes_underscore()
    {
        $this->assertEquals('user\_name', QueryHelper::escapeLike('user_name'));
    }

    /** @test */
    public function it_escapes_backslash()
    {
        $this->assertEquals('\\\\path', QueryHelper::escapeLike('\\path'));
    }

    /** @test */
    public function it_handles_mixed_characters()
    {
        $this->assertEquals('50\%\_discount\\\\', QueryHelper::escapeLike('50%_discount\\'));
    }

    /** @test */
    public function it_returns_empty_string_for_null_or_empty()
    {
        $this->assertEquals('', QueryHelper::escapeLike(null));
        $this->assertEquals('', QueryHelper::escapeLike(''));
    }
}
