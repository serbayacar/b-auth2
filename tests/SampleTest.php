<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class SampleTest extends TestCase
{
    public function testOne() : void
    {
        $this->assertEquals(3,3);
    }

}