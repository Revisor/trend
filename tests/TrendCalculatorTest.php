<?php
namespace Revisor\Trend\Test;

use mcordingley\Regression\SimpleRegression;
use Revisor\Trend\CorrelationSignificance;
use Revisor\Trend\TrendCalculator;

class TrendCalculatorTest extends \PHPUnit_Framework_TestCase
{
    const ALLOWED_DELTA = 0.001;
    
    /**
     * @var TrendCalculator
     */
    private $tc;
    
    // A linear function with a positive slope
    private $linearPoints = [
        [1 => 1],
        [2 => 2],
        [3 => 3],
        [4 => 4],
        [5 => 5]
    ];
    
    // A linear function with a negative slope
    private $negativeLinearPoints = [
        [1 => 5],
        [2 => 4],
        [3 => 3],
        [4 => 2],
        [5 => 1]
    ];
    
    // Points with a significant flat trend
    private $flatTrend = [
        [1 => 1],
        [2 => 1],
        [3 => 1],
        [4 => 1],
    ];
    
    // Points with an insignificant trend 
    private $insignificantTrend = [
        [1 => 6],
        [2 => 1],
        [3 => 5],
        [4 => 0],
        [5 => 3],
    ];
    
    protected function setUp()
    {
        $this->tc = new TrendCalculator();
    }
    
    public function testInstance()
    {
        $this->assertInstanceOf(TrendCalculator::class, $this->tc);
    }
    
    public function testRegressAnAscendingLine()
    {
        $this->assertEquals(
            1.0,
            $this->tc->calculateTrend($this->linearPoints),
            '',
            self::ALLOWED_DELTA
        );
    }
    
    public function testRegressAnAscendingLineWithDuplicateKeys()
    {
        $duplicatedLinearPoints = $this->duplicateKeys($this->linearPoints);
        $this->assertEquals(
            1.0,
            $this->tc->calculateTrend($duplicatedLinearPoints),
            '',
            self::ALLOWED_DELTA
        );
    }
    
    public function testRegressADecliningLine()
    {
        $this->assertEquals(
            -1.0,
            $this->tc->calculateTrend($this->negativeLinearPoints),
            '',
            self::ALLOWED_DELTA
        );
    }
    
    public function testRegressADecliningLineWithDuplicateKeys()
    {
        $duplicatedNegativeLinearPoints = $this->duplicateKeys($this->negativeLinearPoints);
        $this->assertEquals(
            -1.0,
            $this->tc->calculateTrend($duplicatedNegativeLinearPoints),
            '',
            self::ALLOWED_DELTA
        );
    }
    
    public function testNoTrendWithTooFewCases()
    {
        $preserveKeys = true;
        $line = array_slice($this->linearPoints, 0, 2, $preserveKeys);
        
        $this->assertEquals(0, $this->tc->calculateTrend($line));
    }
    
    public function testFlatTrend()
    {
        $this->assertEquals(0, $this->tc->calculateTrend($this->flatTrend));
    }
    
    public function testFlatTrendWithDuplicateKeys()
    {
        $duplicatedFlatTrend = $this->duplicateKeys($this->flatTrend);
        $this->assertEquals(0, $this->tc->calculateTrend($duplicatedFlatTrend));
    }
    
    public function test0ForAnInsignificantTrend()
    {
        $this->assertEquals(0, $this->tc->calculateTrend($this->insignificantTrend));
    }
    
    public function test0ForAnInsignificantTrendWithDuplicateKeys()
    {
        $duplicatedInsignificantTrend = $this->duplicateKeys($this->insignificantTrend);
        $this->assertEquals(0, $this->tc->calculateTrend($duplicatedInsignificantTrend));
    }
    
    public function testAccessToRegression()
    {
        $this->assertInstanceOf(
            SimpleRegression::class,
            $this->tc->getRegression()
        );
    }
    
    /**
     * Split each point value into two values with the same key (timestamp)
     * The average of these two values is original value
     *
     * original:
     *  [
     *      [key1 => 3]
     *      [key2 => 2]
     *  ]
     *
     * duplicate keys:
     *  [
     *      [key1 => 2]
     *      [key1 => 4]
     *      [key2 => 1]
     *      [key2 => 3]
     *  ]
     *
     * @param $points
     *
     * @return array
     */
    private function duplicateKeys($points)
    {
        foreach($points as $key => $point) {
            $timestamp = key($point);
            $value = current($point);
            $points[$key] = [$timestamp => ($value - 1)];
            $points[] = [$timestamp => ($value + 1)];
        }
        
        return $points;
    }
}
