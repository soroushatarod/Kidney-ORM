<?php
namespace Kidney\Utility;

class KidneyLogger extends \Kidney\AbstractFactory\Singleton
{

    /**
     * Holding of colors
     * 
     * @var Array
     */
    private $_colors = array(
        'green' => "\033[32m",
        'yellow' => "\033[33m",
        'blue' => "\033[34m",
        'purple' => "\033[35m",
        'cyan' => "\033[36m",
        'white' => "\033[37m",
        'red' => "\033[31m"
    );

    private $_path = 'Logs';

    /**
     * Returns todays date, in format: YearMonthDay H:M:S
     * 
     * @return Date,
     */
    private function getTodaysDate()
    {
        date_default_timezone_set('Asia/Dubai');
        return date("Y_m_d");
    }

    /**
     * To log Info events such as user loging in
     * 
     * @param
     *            string or array $str, Info to be loged
     * @param string $color
     *            , default: Green
     */
    public function logInfo($str, $color = "green")
    {
        $this->write(' LogNotice', $str, $this->getColor($color));
    }

    /**
     * To log Error, things that are important
     * 
     * @param
     *            string or array $str, Error to be loged
     * @param string $color            
     */
    public function logError($str, $color = "red")
    {
        $this->write('  LogError', $str, $this->getColor($color));
    }

    /**
     * To be used during development, to test functions etc
     * 
     * @param
     *            string or array $str, Testing of Variables etc to be loged
     * @param string $color            
     */
    public function logDebug($str, $color = "green")
    {
        if(is_array($str) || is_object($str))
            $this->write('DEBUG ', '', $this->getColor($color));
            
        $this->write('LogTest ', $str, $this->getColor($color));
    }

    /**
     * Function that writes to file, creates new file daily
     * 
     * @param string $type            
     * @param string $string            
     * @param string $color            
     */
    public function write($type, $string, $color)
    {
        $fileName = $this->getTodaysDate() . '.log';
        $handle = fopen($fileName, 'a');
        if ( is_array($string) || is_object($string) )
        {
            fwrite($handle, str_replace("\n", "\n\t\t\t\t\t\t\t\t\t\t\t", "\t\t\t\t\t\t\t\t\t\t\t" . print_r($string, true)) . "\n");
        }
        else
        {
            fwrite($handle, $color . date("Y-m-d H:i:s") . "  - [ " . $type . "]  ---> " . $string . "\033[0m \n");
        }
        fclose($handle);
    }

    /**
     * Get method that returns the color value from $_colors array
     * 
     * @param string $parm            
     * @return string the color in Hex code
     */
    public function getColor($parm)
    {
        return $this->_colors[$parm];
    }
}

?>
