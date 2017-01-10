<?php
namespace Revisor\Trend;

/**
 * Determine the statistical significance of a (Pearson's) correlation coefficient
 *
 * @author Martin Schlemmer
 */
class CorrelationSignificance
{
    /**
     * A critical value table for (Pearson's) correlation coefficient
     *
     * The alpha level (likelihood of being incorrect when we say the relationship
     * we found in our sample reflects a relationship in the population)
     * in the array keys is for a one-tailed hypothesis.
     *
     * We use a two-tailed hypothesis - we reject the null hypothesis if
     * the regression slope is either positive or negative,
     * so we have to use the .025 for p<.05
     *
     * @see http://www.oneonta.edu/faculty/vomsaaw/w/psy220/files/SignifOfCorrelations.htm
     * @see http://www.gifted.uconn.edu/siegle/research/correlation/corrchrt.htm
     *
     * This could also be calculated as soon as I find the whole formula.
     */
    private $correlationSignificance = [
        '.05' => [
            3 => .988,
            4 => .90,
            5 => .80,
            6 => .73,
            7 => .67,
            8 => .62,
            9 => .58,
            10 => .55,
            11 => .52,
            12 => .50,
            13 => .48,
            14 => .46,
            15 => .44,
            16 => .43,
            17 => .41,
            18 => .40,
            19 => .39,
            20 => .38,
            22 => .36,
            24 => .34,
            26 => .33,
            28 => .32,
            30 => .31,
            40 => .26,
            50 => .23,
            60 => .21,
            80 => .19,
            100 => .17,
            250 => .10,
            500 => .07,
            1000 => .05,
        ],
        '.025' => [
            3 => .997,
            4 => .98,
            5 => .88,
            6 => .81,
            7 => .75,
            8 => .71,
            9 => .67,
            10 => .63,
            11 => .60,
            12 => .58,
            13 => .55,
            14 => .53,
            15 => .51,
            16 => .50,
            17 => .48,
            18 => .47,
            19 => .46,
            20 => .44,
            22 => .42,
            24 => .40,
            26 => .39,
            28 => .37,
            30 => .36,
            40 => .31,
            50 => .28,
            60 => .25,
            80 => .22,
            100 => .20,
            250 => .12,
            500 => .09,
            1000 => .06,
        ],
    ];
    
    /**
     * Check the significance of the correlation
     *
     * Significance is set for p<.05 (two-tailed test) and depends
     * on the number of data points. The higher the data point count,
     * the lower the correlation can be.
     *
     * @param float $correlation    The correlation coefficient
     * @param int   $dataPointCount The number of measured data points (cases)
     *
     * @return boolean Is significant?
     */
    public function isSignificant($correlation, $dataPointCount)
    {
        // We need at least 3 data points to look up the significance
        if ($dataPointCount < 3) {
            return false;
        }
        
        $correlation = abs($correlation);
        
        // Find the table row that is next smaller than
        //  or equal to the data point count
        krsort($this->correlationSignificance['.025']);
        $requiredCorrelation = 1;
        foreach ($this->correlationSignificance['.025'] as $caseCount => $requiredCorrelation) {
            if ($caseCount <= $dataPointCount) {
                break;
            }
        }
        
        return ($requiredCorrelation <= $correlation);
    }
}
