# Trend Calculator

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]

Analyze data and try to find a significant trend.

## Introduction

Imagine you're looking at points in a graph and you want to draw a single line
through the points that represents their change. This is what Trend Calculator
does. It tells you if there is a line through the points and how steep it is.

![An example of linear regression](http://wiki.awf.forst.uni-goettingen.de/wiki/images/thumb/1/16/2.8.2-fig32.png/300px-2.8.2-fig32.png)

This class takes as an input a series of data points in time, performs
regression, determines its statistical significance and returns a trend.

Time is the independent/explanatory variable, values are the
dependent/outcome/target variable. In simplest terms:

"How does value change in time?"

## Installation

Install Trend Calculator with the PHP package manager, [Composer](https://getcomposer.org/):

``` bash
composer require revisor/trend
```

## Usage

``` php
$trendCalculator = new TrendCalculator();

/**
 * $data is an array of arrays
 * In the inner arrays, keys are a time value in any unit (seconds, ie.
 * timestamp, microseconds, days, weeks...), the values are data values.
 * Multiple values for one point in time are allowed - maybe two events
 * occurred at one time.
 */
$data = [
    [1 => 9],
    [1 => 5],
    [2 => 12],
    [4 => 7]
];

/**
 * The resulting trend is a negative or positive float, or zero.
 * 
 * Values other than zero mean that there is a significant trend in the provided
 * data, and the trend goes down (for a negative number) or up (for a positive number).
 *
 * If the value is zero, it means that there is no significant trend.
 */
$trend = $trendCalculator->calculateTrend($data);
```

The slope of the trend is calculated by [`mcordingley/Regression`](https://github.com/mcordingley/Regression).
You can access more information about your data by asking the regression itself.

``` php
$regression = $trendCalculator->getRegression();
var_dump($regression->getStandardErrorCoefficients());
```

## Change log

Please see the [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

## License

The MIT License (MIT). Please see the [License](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/Revisor/trend.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/Revisor/trend/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/Revisor/trend.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Revisor/trend.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/Revisor/trend.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/Revisor/trend
[link-travis]: https://travis-ci.org/Revisor/trend
[link-scrutinizer]: https://scrutinizer-ci.com/g/Revisor/trend/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/Revisor/trend
[link-downloads]: https://packagist.org/packages/Revisor/trend
[link-author]: https://github.com/Revisor
[link-contributors]: ../../contributors
