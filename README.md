## Ext-DateTime

[![LICENSE](https://img.shields.io/badge/release-0%2E0%2E0-blue.svg?style=flat)](hhttps://github.com/prokki/ext-datetime/releases/tag/0.0.0)
[![Packagist](https://img.shields.io/badge/Packagist-0%2E0%2E0-blue.svg?style=flat)](https://packagist.org/packages/prokki/ext-datetime)
[![LICENSE](https://img.shields.io/badge/License-MIT-blue.svg?style=flat)](LICENSE)
[![PHP v7.3](https://img.shields.io/badge/PHP-%E2%89%A57%2E3-0044aa.svg)](https://www.php.net/manual/en/migration73.new-features.php)
[![codecov](https://codecov.io/gh/prokki/ext-datetime/branch/master/graph/badge.svg)](https://codecov.io/gh/prokki/ext-datetime)
[![Build Status](https://travis-ci.org/prokki/ext-datetime.svg?branch=master)](https://travis-ci.org/prokki/ext-datetime)

Extends the native datetime objects ([\DateTime](https://www.php.net/manual/en/class.datetime.php) and [\DateTimeImmutable](https://www.php.net/manual/en/class.datetimeimmutable.php)) with a lot of additional helpful methods.

Most of the new methods are **short cuts of already existing functionality**
to avoid (re-)initialization of necessary parameter if you would use the native classes.
But these methods will help you to save time and code if you need to handle a lot of
date- and time-operations.

All new methods support [*method chaining*](https://stackoverflow.com/questions/3724112/php-method-chaining). 

### Table of Contents

* [Requirements](#requirements)
* [Integration](#integration)
* [Usage](#usage)
  * [Static Initialization](#static-initialization)
  * [Cloning](#cloning)
  * [Manipulating](#manipulating)



### Requirements

The usage of [**PHP v7.3**](https://www.php.net/manual/en/migration73.new-features.php) is obligatory.



### Integration

Please install via [composer](https://getcomposer.org/).

```bash
composer require prokki/ext-datetime "^0.0"
```



### Usage

The classes and instances of the classes [ExtDateTime/DateTime](src/DateTime.php) and [ExtDateTime/DateTimeImmutable](src/DateTimeImmutable.php) can be used
exactly like native datetime objects/classes.

Example

```php
use ExtDateTime\DateTime;
use ExtDateTime\DateTimeImmutable;

// create a datetime object
$dateTime = new DateTime("now");

// create an immutable datetime object
$dateTimeImmutable = new DateTimeImmutable("now");
```


#### Static Initialization

Similar to the native objects there are several others ways to create a datetime object.

All static methods can be used to implement [*method chaining*](https://stackoverflow.com/questions/3724112/php-method-chaining). 

##### create()

Use the static constructor `create()` to use chaining immediately.

```php
use ExtDateTime\DateTime;

// create a datetime object
// and use chaining immediately
$dateTime = DateTime::create("now")
            ->addHours(5)
            ->addDays(5)
            ;
```

##### current()

The static constructor `current()` returns a datetime object with the current date/time.
But in opposite to `DateTime::create("now")` this method returns the object **with additional microtime**. 

```php
use ExtDateTime\DateTime;

$currentMicroseconds = DateTime::current()->format("u");         // output example 654321
$noMicroseconds      = DateTime::create("now")->format("u");     // output always 000000
```

##### createFromObject()

Creates a new object from any datetime object implementing the [DateTimeInterface](https://www.php.net/manual/en/class.datetimeinterface.php).

```php
use ExtDateTime\DateTimeImmutable;

// create an immutable datetime object from a native non-immutable object
$datetime = DateTimeImmutable::createFromObject(new \DateTime());
```


#### Cloning

Two new methods add the availability to use chaining directly after cloning an object.

##### duplicate()

This method is just a wrapper function for `clone`:

```php
use ExtDateTime\DateTime;

// create a datetime object
$datetime = DateTime::current();

// clone the datetime object and proceed like usual with chaining
$clone = $datetime->duplicate()
         ->addHours(5)
         ->addDays(5)
         ;
```

##### toImmutable() / toMutable()

Instead of using static constructors ([DateTime::createFromImmutable](https://www.php.net/manual/en/datetime.createfromimmutable.php) or [DateTimeImmutable::createFromMutable](https://www.php.net/manual/en/datetimeimmutable.createfrommutable.php)) you can also use these new non-static methods.

```php
use ExtDateTime\DateTime;
use ExtDateTime\DateTimeImmutable;

// create a datetime object
$datetime = DateTime::current();

$clonedImmutable = $datetime->toImmutable();       // only available in class DateTime

$clonedMutable   = $clonedImmutable->toMutable();  // only available in class DateTimeImmutable
```



#### Manipulating

Most of the new methods are short cuts to avoid re-initialization of necessary parameter in your code.

##### addHours() / subHours()

Adds or subtracts hours of a datetime object.

```php
use ExtDateTime\DateTime;

// create a datetime object and add 10 hours
$datetimeFuture = DateTime::create("2020-07-30 12:35:17")
                  ->addHours(10)
                  ->format("Y-m-d h:i:s");                   // "2020-07-30 22:35:17"

// create a datetime object and subtracts 10 hours
$datetimeFuture = DateTime::create("2020-07-30 12:35:17")
                  ->subHours(10)
                  ->format("Y-m-d h:i:s");                   // "2020-07-30 02:35:17"
```

##### addDays() / subHours()

Adds or subtracts days of a datetime object.

```php
use ExtDateTime\DateTime;

// create a datetime object and add 10 days
$datetimeFuture = DateTime::create("2020-07-30 12:35:17")
                  ->addDays(10)
                  ->format("Y-m-d h:i:s");                   // "2020-08-09 22:35:17"

// create a datetime object and subtracts 10 days
$datetimeFuture = DateTime::create("2020-07-30 12:35:17")
                  ->subDays(10)
                  ->format("Y-m-d h:i:s");                   // "2020-07-20 02:35:17"
```

##### addMonth() / subMonth()

Adds or subtracts months of a datetime object.

**Attention**: This methods behaves differently than the suspected background function
of adding days. If the current date is the last day of the month (31/30/29/28) and the target month
has less days then the current month, the day will be set to the last day of the target month.

Example: The datetime object is

> *2017-01-30 17:00:00*

and you want to add _1 month_, the result will be

> *2017-02-28 17:00:00*


```php
use ExtDateTime\DateTime;

// create a datetime object and add 10 months
$datetimeFuture = DateTime::create("2020-07-30 12:35:17")
                  ->addMonth(10)
                  ->format("Y-m-d h:i:s");                   // "2020-08-09 22:35:17"

// create a datetime object and subtracts 10 months
$datetimeFuture = DateTime::create("2020-07-30 12:35:17")
                  ->subMonth(10)
                  ->format("Y-m-d h:i:s");                   // "2020-07-20 02:35:17"
```

##### toEndOfDay()

Sets the time to the end of the day (*23:59:59*).

```php
use ExtDateTime\DateTime;

// create a datetime object and sets the time to the end of the day
$datetimeFuture = DateTime::create("2020-07-30 12:35:17")
                  ->toEndOfDay(10)
                  ->format("Y-m-d h:i:s");                   // "2020-07-30 23:59:59"
```

##### toNoon()

Sets the time to noon (*12:00:00*).

```php
use ExtDateTime\DateTime;

// create a datetime object and sets the time to noon
$datetimeFuture = DateTime::create("2020-07-30 12:35:17")
                  ->toNoon(10)
                  ->format("Y-m-d h:i:s");                   // "2020-07-30 12:00:00"
```

##### toStartOfDay()

Sets the time to the start of the day  (*00:00:00*).

```php
use ExtDateTime\DateTime;

// create a datetime object and sets the time the start of the day
$datetimeFuture = DateTime::create("2020-07-30 12:35:17")
                  ->toStartOfDay(10)
                  ->format("Y-m-d h:i:s");                   // "2020-07-30 00:00:00"
```

##### toStartOfMonth()

Sets the date to the first day of the month and additionally the time to the start of the day (*00:00:00*).

```php
use ExtDateTime\DateTime;

// create a datetime object and sets the date to the first day of the month
$datetimeFuture = DateTime::create("2020-07-30 12:35:17")
                  ->toStartOfMonth()
                  ->format("Y-m-d h:i:s");                   // "2020-07-01 00:00:00"
```

##### toEndOfMonth()

Sets the date to the last day of the month and additionally the time to the end of the day (*23:59:59*).

```php
use ExtDateTime\DateTime;

// create a datetime object and sets the date to the last day of the month
$datetimeFuture = DateTime::create("2020-07-30 12:35:17")
                  ->toEndOfMonth()
                  ->format("Y-m-d h:i:s");                   // "2020-07-31 23:59:59"
```
