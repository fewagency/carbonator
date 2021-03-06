<?php

use Carbon\Carbon;
use FewAgency\Carbonator\Carbonator;
use PHPUnit\Framework\TestCase;

class CarbonatorTest extends TestCase
{
    public function testParseToTzReturnsNullOnFail()
    {
        $this->assertNull(Carbonator::parseToTz('fail'));
    }

    public function testParseToTzReturnsNullForEmptyString()
    {
        $this->assertNull(Carbonator::parseToTz(''));
    }

    public function testParseToTzReturnsNullForSpaceOnlyString()
    {
        $this->assertNull(Carbonator::parseToTz('  '));
    }

    public function testParseToTz()
    {
        $c = Carbonator::parseToTz('tomorrow 13:37');

        $this->assertEquals('13:37:00', $c->toTimeString());
        $this->assertTrue($c->utc);
    }

    public function testParseToTzWithTargetTz()
    {
        $c = Carbonator::parseToTz('tomorrow 13:37', '+01:00');

        $this->assertEquals('+01:00', $c->tzName);
        $this->assertEquals('14:37:00', $c->toTimeString());
    }

    public function testParseToTzWithParseTz()
    {
        $c = Carbonator::parseToTz('tomorrow 13:37', '-01:00', '+01:00');

        $this->assertEquals('-01:00', $c->tzName);
        $this->assertEquals('11:37:00', $c->toTimeString());
    }

    public function testParseToTzWithInvalidTargetTz()
    {
        $c = Carbonator::parseToTz('tomorrow 13:37', 'Invalid tz', 'UTC');

        $this->assertNull($c);
    }

    public function testParseToDefaultTz()
    {
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

    public function testFormatInTz()
    {
        $this->assertEquals(
            '2016-08-05T12:37:00+00:00',
            Carbonator::formatInTz('2016-08-05 13:37 +01:00', Carbon::W3C)
        );
    }

    public function testFormatInTzWithTargetTz()
    {
        $this->assertEquals(
            '2016-08-05T14:37:00+02:00',
            Carbonator::formatInTz('2016-08-05 13:37 +01:00', Carbon::W3C, '+02:00')
        );
    }

    public function testParseToDatetimeLocal()
    {
        $this->assertEquals(
            '2016-08-05 12:37',
            Carbonator::parseToDatetimeLocal('2016-08-05 13:37 +01:00')
        );
    }

    public function testParseToDatetimeLocalWithTargetTz()
    {
        $this->assertEquals(
            '2016-08-05 13:37',
            Carbonator::parseToDatetimeLocal('2016-08-05 13:37 +01:00', '+01:00')
        );
    }

    public function testParseToDatetimeLocalWithParseTz()
    {
        $this->assertEquals(
            '2016-08-05 15:37',
            Carbonator::parseToDatetimeLocal('2016-08-05 13:37', '+01:00', '-01:00')
        );
    }

    public function testParseToDatetime()
    {
        $this->assertEquals(
            '2016-08-05T13:37:00+01:00',
            Carbonator::parseToDatetime('2016-08-05 13:37 +01:00')
        );
    }

    public function testParseToDatetimeWithParseTz()
    {
        $this->assertEquals(
            '2016-08-05T13:37:00+01:00',
            Carbonator::parseToDatetime('2016-08-05 13:37', '+01:00')
        );
    }

    public function testParseArrayReturnsNull()
    {
        $this->assertNull(Carbonator::parse([
            'year' => '2016',
            'month' => '08',
            'day' => '07',
            'hour' => '13',
            'minute' => '45',
        ]));
    }
}
