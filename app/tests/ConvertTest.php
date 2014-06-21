<?php

class ConvertTest extends TestCase
{
    public function testConvertToFloat()
    {
        $this->assertSame(12000000.45, Convert::toFloat("12,000,000.45"));
        $this->assertSame(545.0, Convert::toFloat("# 545"));
    }

    public function testConvertToInt()
    {
        $this->assertSame(12000000, Convert::toInt("12,000,000.45"));
        $this->assertSame(545, Convert::toInt("# 545"));
    }

    public function testConvertToArray()
    {
        $this->assertSame(['test', 'ok'], Convert::toArray("   test,      ok    "));
        $this->assertSame(['foo', 'bar'], Convert::toArray("   foo,,, foo, bar,       "));
        $this->assertSame(['foo', 'bar'], Convert::toArray("foo;bar", ';'));
    }

    public function testConvertToString()
    {
        $this->assertSame("foo bar bar fooo (test)", Convert::toString("foo bar \n    bar \t fooo  \n \n \n (test)"));
    }
}
