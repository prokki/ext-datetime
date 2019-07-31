# Changes in Ext-DateTime

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

## [0.0.0] - 2019-07-31

### Added
- First version supports
  - Static constructors
    - `DateTime::create()`
    - `DateTime::current()`
    - `DateTime::createFromObject()`
  - Non-static cloning
    - `DateTime::duplicate()`
    - `DateTime::toImmutable()` and `DateTimeImmutable::toMutable()` 
  - Manipulating methods
    - `DateTime::addHours()` / `DateTime::subHours()`
    - `DateTime::addDays()` / `DateTime::subDays()`
    - `DateTime::addMonth()` / `DateTime::subMonth()`
  - Instant setters
    - `DateTime::toEndOfDay()`, `DateTime::toNoon()`, `DateTime::toStartOfDay()`
    - `DateTime::toStartOfMonth()`, `DateTime::toEndOfMonth()`
