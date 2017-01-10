<?php
namespace Revisor\Trend;

use mcordingley\Regression\SimpleRegression;
use Exception;

/**
 * A calculator that analyzes data and tries to find a significant trend
 *
 * This class takes as an input a series of data points in time, performs
 * regression, determines its statistical significance and returns a trend.
 *
 * Time is the independent/explanatory variable, values are
 * the dependent/outcome/target variable. In simplest terms:
 * "How does value change with time?"
 *
 * To try out linear regression visually, visit
 * http://www.alcula.com/calculators/statistics/linear-regression/
 *
 * @author Martin Schlemmer
 */
class TrendCalculator
{
    /**
     * @var CorrelationSignificance
     */
    private $correlationSignificance;
    
    /**
     * @var SimpleRegression
     */
    private $regression;
    
    public function __construct(CorrelationSignificance $correlationSignificance = null)
    {
        if (is_null($correlationSignificance)) {
            $correlationSignificance = new CorrelationSignificance();
        }
        $this->correlationSignificance = $correlationSignificance;
    }
    
    /**
     * Calculate trend from the given data points
     *
     * It accepts multiple values for one timestamp
     *
     * @param array $data Keys are time in any units (in seconds, milliseconds, days, week),
     *                    values are data values.
     *                    Each point is specified in the separate array,
     *                    so we can define multiple values for one timestamp:
     *                    [
     *                    [timestamp1 => value1]
     *                    [timestamp1 => value2]
     *                    [timestamp2 => value3]
     *                    ...
     *                    ]
     *
     * @return float Trend - A negative/positive float or 0 for not significant trend
     */
    public function calculateTrend($data = array())
    {
        // Check that we have more than two data points
        $dataCount = count($data);
        if ($dataCount <= 2) {
            return 0;
        }
        
        $this->createRegression();
        
        foreach ($data as $point) {
            $this->regression->addData(current($point), [key($point)]);
        }
        
        try {
            if ( ! $this->isCorrelationSignificant($dataCount)) {
                return 0;
            }
            
            $slope = $this->regression->getCoefficients()[0];
            return $slope;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Get the regression object for further questioning
     *
     * You can eg. ask the regression to predict() a future value
     *
     * @return SimpleRegression
     */
    public function getRegression()
    {
        if ($this->regression === null) {
            $this->createRegression();
        }
        
        return $this->regression;
    }
    
    /**
     * Check the significance of the correlation
     *
     * Since we don't have a correlation, we use the square root of R-Squared.
     * We lose the sign, but the absolute value of correlation stays the same.
     *
     * @param int $dataPointCount The number of measured data points (cases)
     *
     * @return boolean Is significant?
     */
    private function isCorrelationSignificant($dataPointCount)
    {
        // R-Squared is the square of r, the correlation coefficient
        $rSquared = $this->regression->getRSquared();
        // Note: This is not really a correlation, we have lost the +/- sign
        // However we don't need the sign for determining the significance
        $correlation = sqrt($rSquared);
        
        // Significance is determined for p<.05 (two-tailed test)
        $isSignificant = $this->correlationSignificance->isSignificant(
            $correlation,
            $dataPointCount
        );
        return $isSignificant;
    }
    
    /**
     * A factory method for creating the regression object
     */
    private function createRegression()
    {
        $this->regression = new SimpleRegression();
    }
}
