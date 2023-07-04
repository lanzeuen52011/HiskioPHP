<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase // 只要拿來測試都一定要繼承 TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true); // 此處的ture與false會導致最後的測試結果是通過還是沒通過
    }
}
