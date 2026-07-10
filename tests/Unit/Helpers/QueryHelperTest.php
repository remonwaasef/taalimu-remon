<?php

namespace Tests\Unit\Helpers;

use App\Helpers\QueryHelper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class QueryHelperTest extends TestCase
{
    #[Test]
    public function it_escapes_percent_sign()
    {
        $this->assertEquals('100\%', QueryHelper::escapeLike('100%'));
    }

    #[Test]
    public function it_escapes_underscore()
    {
        $this->assertEquals('user\_name', QueryHelper::escapeLike('user_name'));
    }

    #[Test]
    public function it_escapes_backslash()
    {
        $this->assertEquals('\\\\path', QueryHelper::escapeLike('\\path'));
    }

    #[Test]
    public function it_handles_mixed_characters()
    {
        $this->assertEquals('50\%\_discount\\\\', QueryHelper::escapeLike('50%_discount\\'));
    }

    #[Test]
    public function it_returns_empty_string_for_null_or_empty()
    {
        $this->assertEquals('', QueryHelper::escapeLike(null));
        $this->assertEquals('', QueryHelper::escapeLike(''));
    }
}
