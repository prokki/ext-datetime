<?php

namespace ExtDateTime\Test;

use DateTimeZone;
use Exception;
use ExtDateTime\DateTime;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        // call super method
        parent::setUp();

        // server timezone is UTC
        date_default_timezone_set('UTC');
    }

    /**
     * @throws Exception
     */
    public static function testCurrent()
    {
        $dateTime1 = DateTime::current();
        $dateTime2 = DateTime::current();
        self::assertNotEquals($dateTime1, $dateTime2);

        $today = new \DateTime('now');
        self::assertLessThan(1000000, abs((int) $today->diff($dateTime1)->format('%f')));
    }

    /**
     * @throws Exception
     */
    public static function testCreateFromObject()
    {
        $dateTime1 = DateTime::createFromObject(new \DateTime('2016-07-13T19:25:47+07:00'));
        $dateTime2 = DateTime::createFromFormat('Y-m-d H:i:s', '2016-07-13 19:25:47', new DateTimeZone('+07:00'));

        self::assertEquals($dateTime1, $dateTime2);
    }

    /**
     * @throws Exception
     */
    public function testCreateFromFormat()
    {
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', '2019-12-31 16:17:35');

        self::assertInstanceOf(DateTime::class, $dateTime);
        self::assertEquals(1577809055, $dateTime->getTimestamp());
    }

    /**
     * @throws Exception
     */
    public function testCreateFromFormatException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Cannot create an object by DateTime::createFromFormat().');

        DateTime::createFromFormat('Y-m-d', 'I do not want to submit valid parameters!');
    }

    /**
     * @throws Exception
     */
    public function testCreateFromImmutable()
    {
        $dateTime = DateTime::createFromImmutable(new \DateTimeImmutable());
        $dateTimeChanged = $dateTime->addDays(1);

        self::assertNotInstanceOf(\DateTimeImmutable::class, $dateTime);
        self::assertSame($dateTime, $dateTimeChanged);
    }

    /**
     * @throws Exception
     */
    public function testCreate()
    {
        self::assertInstanceOf(DateTime::class, DateTime::create());
    }

    /**
     * @throws Exception
     */
    public function testToImmutable()
    {
        $dateTime = DateTime::create();
        $dateTimeImmutable = $dateTime->toImmutable();
        $dateTimeImmutableChanged = $dateTimeImmutable->addDays(1);

        self::assertInstanceOf(\DateTimeImmutable::class, $dateTimeImmutable);
        self::assertInstanceOf(\DateTimeImmutable::class, $dateTimeImmutableChanged);
        self::assertNotSame($dateTimeImmutable, $dateTimeImmutableChanged);
    }

    /**
     * @throws Exception
     */
    public function testDuplicate()
    {
        $dateTime1 = DateTime::create();
        $dateTime2 = $dateTime1->duplicate();

        // content of date time equals
        self::assertEquals($dateTime1->format('U.u'), $dateTime2->format('U.u'));
        // but objects are not the same
        self::assertNotSame($dateTime1, $dateTime2);
        self::assertInstanceOf(DateTime::class, $dateTime2);
    }

    /**
     * @throws Exception
     */
    public function testAddHours()
    {
        $dateTime = DateTime::create('2019-12-31 16:17:35');

        // check "same" object
        self::assertSame($dateTime, $dateTime->addHours(0));
        self::assertInstanceOf(DateTime::class, $dateTime->addHours(0));

        // check +8 hours from origin $dateTime
        self::assertEquals('2020-01-01 00:17:35', $dateTime->addHours(8)->format('Y-m-d H:i:s'));
        // check -46 hours from already changed $dateTime
        self::assertEquals('2019-12-30 02:17:35', $dateTime->addHours(-46)->format('Y-m-d H:i:s'));
    }

    /**
     * @throws Exception
     */
    public function testSubHours()
    {
        $dateTime = DateTime::create('1978-08-20 07:14:57');

        // check "same" object
        self::assertSame($dateTime, $dateTime->subHours(0));
        self::assertInstanceOf(DateTime::class, $dateTime->subHours(0));

        // check -100 hours from origin $dateTime
        self::assertEquals('1978-08-16 03:14:57', $dateTime->subHours(100)->format('Y-m-d H:i:s'));
    }

    /**
     * @throws Exception
     */
    public function testAddDays()
    {
        $dateTime = DateTime::create('2019-12-31 16:17:35');

        // check "same" object
        self::assertSame($dateTime, $dateTime->addDays(0));
        self::assertInstanceOf(DateTime::class, $dateTime->addDays(0));

        // check +8 hours from origin $dateTime
        self::assertEquals('2020-01-08 16:17:35', $dateTime->addDays(8)->format('Y-m-d H:i:s'));
        // check -46 hours from already changed $dateTime
        self::assertEquals('2019-11-23 16:17:35', $dateTime->addDays(-46)->format('Y-m-d H:i:s'));
    }

    /**
     * @throws Exception
     */
    public function testSubDays()
    {
        $dateTime = DateTime::create('1978-08-20 07:14:57');

        // check "same" object
        self::assertSame($dateTime, $dateTime->subDays(0));
        self::assertInstanceOf(DateTime::class, $dateTime->subDays(0));

        // check -100 days from origin $dateTime
        self::assertEquals('1978-05-12 07:14:57', $dateTime->subDays(100)->format('Y-m-d H:i:s'));
    }

    /**
     * @throws Exception
     */
    public function testAddMonth()
    {
        $dateTime = DateTime::create('2019-12-31 16:17:35');

        // check "same" object
        self::assertSame($dateTime, $dateTime->addMonth(0));
        self::assertInstanceOf(DateTime::class, $dateTime->addMonth(0));

        // check +8 months from origin $dateTime
        self::assertEquals('2020-08-31 16:17:35', $dateTime->addMonth(8)->format('Y-m-d H:i:s'));
        // check -46 months from already changed $dateTime
        self::assertEquals('2016-10-31 16:17:35', $dateTime->addMonth(-46)->format('Y-m-d H:i:s'));

        // special case: add one month to the 31th of October = not November, 31st but November, 30th!
        self::assertEquals('2016-11-30 16:17:35', DateTime::create('2016-10-31 16:17:35')->addMonth(1)->format('Y-m-d H:i:s'));
    }

    /**
     * @throws Exception
     */
    public function testSubMonth()
    {
        $dateTime = DateTime::create('2019-12-31 16:17:35');

        // check "same" object
        self::assertSame($dateTime, $dateTime->subMonth(0));
        self::assertInstanceOf(DateTime::class, $dateTime->subMonth(0));

        // check -100 months from origin $dateTime
        self::assertEquals('2011-08-31 16:17:35', $dateTime->subMonth(100)->format('Y-m-d H:i:s'));

        // special case: sub two month from the 31th of August = not June, 31st but June, 30th!
        self::assertEquals('2011-06-30 16:17:35', DateTime::create('2011-08-31 16:17:35')->subMonth(2)->format('Y-m-d H:i:s'));
    }

    /**
     * @throws Exception
     */
    public function testToEndOfDay()
    {
        $dateTime = DateTime::create('1978-08-20 07:14:57');

        // check "same" object
        self::assertSame($dateTime, $dateTime->toEndOfDay());
        self::assertInstanceOf(DateTime::class, $dateTime->toEndOfDay());

        // set time to end of day (23:59:59)
        self::assertEquals('1978-08-20 23:59:59', $dateTime->toEndOfDay()->format('Y-m-d H:i:s'));
    }

    /**
     * @throws Exception
     */
    public function testToNoon()
    {
        $dateTime = DateTime::create('1978-08-20 07:14:57');

        // check "same" object
        self::assertSame($dateTime, $dateTime->toNoon());
        self::assertInstanceOf(DateTime::class, $dateTime->toNoon());

        // set time to noon (12:00:00)
        self::assertEquals('1978-08-20 12:00:00', $dateTime->toNoon()->format('Y-m-d H:i:s'));
    }

    /**
     * @throws Exception
     */
    public function testToStartOfDay()
    {
        $dateTime = DateTime::create('1978-08-20 07:14:57');

        // check "same" object
        self::assertSame($dateTime, $dateTime->toStartOfDay());
        self::assertInstanceOf(DateTime::class, $dateTime->toStartOfDay());

        // set time to start of day (00:00:00)
        self::assertEquals('1978-08-20 00:00:00', $dateTime->toStartOfDay()->format('Y-m-d H:i:s'));
    }

    /**
     * @throws Exception
     */
    public function testToStartOfMonth()
    {
        $dateTime = DateTime::create('1978-08-20 07:14:57');

        // check "same" object
        self::assertSame($dateTime, $dateTime->toStartOfMonth());
        self::assertInstanceOf(DateTime::class, $dateTime->toStartOfMonth());

        // sets the date to the end of the month (last day of the month) and the time to end of the day (23:59:59)
        self::assertEquals('1978-08-01 00:00:00', $dateTime->toStartOfMonth()->format('Y-m-d H:i:s'));
        self::assertEquals('1978-08-01 00:00:00', $dateTime->toStartOfMonth()->format('Y-m-d H:i:s'));
    }

    /**
     * @throws Exception
     */
    public function testToEndOfMonth()
    {
        $dateTime = DateTime::create('1978-08-20 07:14:57');

        // check "same" object
        self::assertSame($dateTime, $dateTime->toEndOfMonth());
        self::assertInstanceOf(DateTime::class, $dateTime->toEndOfMonth());

        // sets the date to the start of the month (first day of the month) and the time to start of the day (00:00:00)
        self::assertEquals('1978-08-31 23:59:59', $dateTime->toEndOfMonth()->format('Y-m-d H:i:s'));
        self::assertEquals('1978-08-31 23:59:59', $dateTime->toEndOfMonth()->format('Y-m-d H:i:s'));
    }

}
