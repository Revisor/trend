<?php
namespace Revisor\Trend\Test;

use Revisor\Trend\CorrelationSignificance;

class CorrelationSignificanceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CorrelationSignificance
     */
    private $cs;
    
    protected function setUp()
    {
        $this->cs = new CorrelationSignificance();
    }
    
    public function testInstance()
    {
        $this->assertInstanceOf(CorrelationSignificance::class, $this->cs);
    }
    
    public function testTooFewDatapointsMarkedAsNotSignificant()
    {
        $correlation = 1;
        $dataPointCount = 2;
        
        $this->assertFalse(
            $this->cs->isSignificant($correlation, $dataPointCount)
        );
    }
    
    public function testDeterminingSignificanceProperly()
    {
        $correlation = .64;
        $dataPointCount = 10;
        $this->assertTrue(
            $this->cs->isSignificant($correlation, $dataPointCount)
        );
    
        $correlation = .60;
        $this->assertFalse(
            $this->cs->isSignificant($correlation, $dataPointCount)
        );
    }
    
    public function testDeterminingSignificanceForValuesMissingFromTheTable()
    {
        $correlation = .41;
        $dataPointCount = 25;
        // There is no row for N = 25, the class must use the next lower row
        $this->assertTrue(
            $this->cs->isSignificant($correlation, $dataPointCount)
        );
        
    }
}
