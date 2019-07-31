<?php

declare( strict_types=1 );

namespace ExtDateTime;

use DateInterval;
use DateTimeInterface;
use DateTimeZone;
use Exception;

/**
 * Class DateTime
 *
 * @package ExtDateTime
 */
class DateTime extends \DateTime
{
    /**
     * Returns the current server date time or `false` if the current day could not be fetched.
     *
     * @return boolean|static
     *
     * @throws Exception
     */
    public static function current()
    {
        return static::createFromFormat('U.u', sprintf('%.6F', microtime(true)));
    }

    /**
     * Parses a string into a new {@see DateTime} object according to the specified format.
     *
     * @param string            $format   Format accepted by date().
     * @param string            $time     String representing the time.
     * @param DateTimeZone|null $timezone [optional] A DateTimeZone object representing the desired time zone.
     *
     * @return boolean|static
     *
     * @throws Exception
     *
     * @link http://php.net/manual/en/datetime.createfromformat.php
     */
    public static function createFromFormat($format, $time, DateTimeZone $timezone = null)
    {
        $datetime = is_null($timezone) ?
            parent::createFromFormat($format, $time) :
            parent::createFromFormat($format, $time, $timezone);

        if( !is_object($datetime) )
        {
            throw new Exception('Cannot create an object by DateTime::createFromFormat().');
        }

        return static::createFromObject($datetime);
    }

    /**
     * Overrides {@see \DateTime::createFromImmutable()} to return a {@see \ExtDateTime\DateTime} object
     * instead of the default {@see \DateTime} object.
     *
     * @param \DateTimeImmutable $DateTimeImmutable
     *
     * @return boolean|static
     *
     * @throws Exception
     */
    public static function createFromImmutable($DateTimeImmutable)
    {
        return static::createFromObject($DateTimeImmutable);
    }

    /**
     * Copies and casts the submitted datetime object to a new {@see \ExtDateTime\DateTime} object.
     *
     * @param DateTimeInterface $dateTime the source object has to implement the {@see \DateTimeInterface}
     *
     * @return boolean|static
     */
    public static function createFromObject(DateTimeInterface $dateTime)
    {
        $parts      = explode(':', serialize($dateTime));
        $parts[ 1 ] = strlen(static::class);
        $parts[ 2 ] = sprintf('"%s"', static::class);

        return unserialize(implode(':', $parts));
    }

    /**
     * Static constructor. Returns a new {@see \ExtDateTime\DateTime} object.
     *
     * @param string       $time
     * @param DateTimeZone $timezone
     *
     * @return boolean|static
     *
     * @throws Exception
     */
    public static function create(string $time = 'now', DateTimeZone $timezone = null)
    {
        return new static($time, $timezone);
    }

    /**
     * Converts the mutable datetime object to an immutable datetime object.
     *
     * Wrapper function of {\ExtDateTime\DateTimeImmutable::createFromMutable()}.
     *
     * @return DateTimeImmutable
     *
     * @throws Exception
     */
    public function toImmutable(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromMutable($this);
    }

    /**
     * Returns a cloned object.
     *
     * This method is just a wrapper function for {@see \clone()}.
     *
     * @return static
     */
    public function duplicate(): DateTimeInterface
    {
        return clone $this;
    }

    /**
     * @return integer
     */
    protected function getAbsoluteMonths(): int
    {
        return intval($this->format('Y')) * 12 + intval($this->format('m'));
    }

    /**
     * Adds (or subtracts) hours.
     *
     * @param integer $hours to add some hours pass a positive number, to subtract pass a negative number
     *
     * @return static
     */
    public function addHours(int $hours): DateTimeInterface
    {
        return $this->add(DateInterval::createFromDateString(sprintf('%d hour', $hours)));
    }

    /**
     * Subtracts (or adds) hours.
     *
     * @param integer $hours to subtract some hours pass a positive number, to add pass a negative number
     *
     * @return static
     */
    public function subHours(int $hours): DateTimeInterface
    {
        return $this->addHours(0 - $hours);
    }

    /**
     * Adds (or subtracts) days.
     *
     * @param integer $days to add some days pass a positive number, to subtract pass a negative number
     *
     * @return static
     */
    public function addDays(int $days): DateTimeInterface
    {
        return $this->add(DateInterval::createFromDateString(sprintf('%d day', $days)));
    }

    /**
     * Subtracts (or adds) days.
     *
     * @param integer $days to subtracts some days pass a positive number, to add pass a negative number
     *
     * @return static
     */
    public function subDays(int $days): DateTimeInterface
    {
        return $this->addDays(0 - $days);
    }

    /**
     * Adds an amount of months to the current date.
     *
     * **ATTENTION**: If the current date is the 31/30/29 and the target month has less days then the current month, the day will be set to the last
     * day of the target month.
     *
     * Example: Current date is '2017-01-30 17:00:00' and you want to add '1 month', then the result will be '2017-02-28 17:00:00'.
     *
     * @param integer $month
     *
     * @return static
     */
    public function addMonth(int $month): DateTimeInterface
    {
        $absoluteMonths = $this->getAbsoluteMonths();

        $this->add(DateInterval::createFromDateString(sprintf('%d month', $month)));

        if( $absoluteMonths + $month !== $this->getAbsoluteMonths() )
        {
            $this->subDays((int) $this->format('d'));
        }

        return $this;
    }

    /**
     * Subs an amount of months from the current date.
     *
     * **ATTENTION**: If the current date is the 31/30/29 and the target month has less days then the current month, the day will be set to the last
     * day of the target month.
     *
     * Example: Current date is '2017-03-31 17:00:00' and you want to sub '1 month', then the result will be '2017-02-28 17:00:00'.
     *
     * @param integer $month
     *
     * @return static
     */
    public function subMonth(int $month): DateTimeInterface
    {
        $absolute_months = $this->getAbsoluteMonths();

        $this->sub(DateInterval::createFromDateString(sprintf('%d month', $month)));

        if( $absolute_months - $month !== $this->getAbsoluteMonths() )
        {
            $this->subDays((int) $this->format('d'));
        }

        return $this;
    }

    /**
     * Sets the time to the end of the day (`23:59:59`).
     *
     * @return static
     */
    public function toEndOfDay(): DateTimeInterface
    {
        return $this->setTime(23, 59, 59);
    }

    /**
     * Sets the time to noon (`12:00:00`).
     *
     * @return static
     */
    public function toNoon(): DateTimeInterface
    {
        return $this->setTime(12, 0, 0);
    }

    /**
     * Sets the time to the start of the day (`00:00:00`).
     *
     * @return static
     */
    public function toStartOfDay(): DateTimeInterface
    {
        return $this->setTime(0, 0, 0);
    }

    /**
     * Sets the date to the first day of the month and additionally the time to the start of the day (`00:00:00`).
     *
     * @return static
     */
    public function toStartOfMonth(): DateTimeInterface
    {
        return $this->setDate((int) $this->format('Y'), (int) $this->format('m'), 1)->toStartOfDay();
    }

    /**
     * Sets the date to the last day of the month and additionally the time to the end of the day (`23:59:59`).
     *
     * @return static
     */
    public function toEndOfMonth(): DateTimeInterface
    {
        return $this->setDate((int) $this->format('Y'), (int) $this->format('m'), (int) $this->format('t'))->toEndOfDay();
    }

}
