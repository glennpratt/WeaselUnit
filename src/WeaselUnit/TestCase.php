<?php
namespace WeaselUnit;

use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\Mink\Driver\SeleniumDriver;

//use WeaselUnit\Selenium\Client as SeleniumClient;
use Selenium\Client;

use PHPUnit_Extensions_SeleniumTestCase;
use PHPUnit_Extensions_SeleniumTestCase_Driver;

use ReflectionClass;

abstract class TestCase extends PHPUnit_Extensions_SeleniumTestCase
{
    protected $seleniumClient;

    protected $seleniumDriver;

    protected $fromMink = true;

    /**
     * @var Mink
     */
    protected $mink;

    public function setUp()
    {
        $this->fromMink ? $this->fromMink() : $this->fromPHPUnit();
    }

    protected function fromMink()
    {
        $this->seleniumClient = new Client('localhost', 4444);
        $this->seleniumDriver = new SeleniumDriver('*firefox', 'http://www.google.com', $this->seleniumClient);
        $this->mink = new Mink(array(
            'selenium' => new Session($this->seleniumDriver),
        ));

        $this->prepareTestSession();
        $sessionId = self::getSeleniumDriverSessionId($this->seleniumDriver);
        self::setPHPUnitSessionId($this->drivers[0], $sessionId);
    }

    protected function fromPHPUnit()
    {
        $this->prepareTestSession();
        $sessionId = self::getPHPUnitSessionId($this->drivers[0]);
        $this->seleniumClient = new Client('localhost', 4444);
        $this->seleniumDriver = new SeleniumDriver('*firefox', 'http://www.google.com', $this->seleniumClient);
        self::setSeleniumDriverSessionId($this->seleniumDriver, $sessionId);
        $this->mink = new Mink(array(
            'selenium' => new Session($this->seleniumDriver),
        ));
    }

    /**
     * @return Mink
     */
    public function getMink()
    {
        return $this->mink;
    }

    /**
     *
     * @return string
     */
    public static function getSeleniumDriverSessionId(SeleniumDriver $driver)
    {
        $class = new ReflectionClass(get_class($driver));
        $property = $class->getProperty("browser");
        $property->setAccessible(true);
        $browser = $property->getValue($driver);

        $class = new ReflectionClass(get_class($browser));
        $property = $class->getProperty("driver");
        $property->setAccessible(true);
        $driver = $property->getValue($browser);

        $class = new ReflectionClass(get_class($driver));
        $property = $class->getProperty("sessionId");
        $property->setAccessible(true);
        return $property->getValue($driver);
    }

    /**
     *
     * @return string
     */
    public static function setSeleniumDriverSessionId(SeleniumDriver $driver, $sessionId)
    {
        $driverClass = new ReflectionClass(get_class($driver));
        $property = $driverClass->getProperty("browser");
        $property->setAccessible(true);
        $browser = $property->getValue($driver);

        $class = new ReflectionClass(get_class($browser));
        $property = $class->getProperty("driver");
        $property->setAccessible(true);
        $seleniumDriver = $property->getValue($browser);

        $seleniumDriverClass = new ReflectionClass(get_class($seleniumDriver));
        $property = $seleniumDriverClass->getProperty("sessionId");
        $property->setAccessible(true);
        $ret = $property->setValue($seleniumDriver, $sessionId);

        // Mark the driver as started now that it has a sessionId.
        $property = $driverClass->getProperty("started");
        $property->setAccessible(true);
        $property->setValue($driver, true);

        return $ret;
    }

    /**
     *
     * @return string
     */
    public static function getPHPUnitSessionId(PHPUnit_Extensions_SeleniumTestCase_Driver $driver)
    {
        $class = new ReflectionClass(get_class($driver));
        $property = $class->getProperty("sessionId");
        $property->setAccessible(true);

        return $property->getValue($driver);
    }

    /**
     *
     * @param string $sessionId
     */
    public static function setPHPUnitSessionId(PHPUnit_Extensions_SeleniumTestCase_Driver $driver, $sessionId)
    {
        $class = new ReflectionClass(get_class($driver));
        $property = $class->getProperty("sessionId");
        $property->setAccessible(true);

        return $property->setValue($driver, $sessionId);
    }

}
