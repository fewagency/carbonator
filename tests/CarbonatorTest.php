<?php

use FewAgency\Carbonator\Carbonator;
use Carbon\Carbon;

class CarbonatorTest extends PHPUnit_Framework_TestCase
{
    public function testParseToTzReturnsNullOnFail()
    {
        $this->assertNull(Carbonator::parseToTz('fail'));
    }

    public function testParseToTzReturnsNullOnEmptyString()
    {
        $this->assertNull(Carbonator::parseToTz(''));
    }

    public function testParseToTz()
    {
        $c = Carbonator::parseToTz('tomorrow 13:37');

        $this->assertEquals('13:37:00', $c->toTimeString());
        $this->assertTrue($c->utc);
    }

    public function testParseToTzWithTargetTz()
    {
        $c = Carbonator::parseToTz('tomorrow 13:37', 'Europe/Stockholm');

        $this->assertEquals('Europe/Stockholm', $c->tzName);
        $this->assertEquals('13:37:00', $c->toTimeString());
    }

    public function testParseToTzWithParseTz()
    {
        $c = Carbonator::parseToTz('tomorrow 13:37', '-1', 'Europe/Stockholm');

        $this->assertEquals('-01:00', $c->tzName);
        $this->assertNotEquals('13:37:00', $c->toTimeString());
    }

    public function testParseToDefaultTz() {
        $c = Carbonator::parseToDefaultTz('tomorrow 13:37');

        $this->assertTrue($c->utc);
        $this->assertEquals('13:37:00', $c->toTimeString());
    }

    public function testParseToDefaultTzWithParseTz()
    {
        $c = Carbonator::parseToDefaultTz('tomorrow 13:37', 'Europe/Stockholm');

        $this->assertTrue($c->utc);
        $this->assertNotEquals('13:37:00', $c->toTimeString());
    }
}
