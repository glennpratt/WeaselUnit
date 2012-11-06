<?php
namespace WeaselUnit;

use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\Mink\Driver\SeleniumDriver;

use WeaselUnit\Selenium\Client as SeleniumClient;

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
        $this->seleniumClient = new SeleniumClient('localhost', 4444);
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
        $this->seleniumClient = new SeleniumClient('localhost', 4444);
        $this->seleniumClient->sessionId = $sessionId;
        $this->seleniumDriver = new SeleniumDriver('*firefox', 'http://www.google.com', $this->seleniumClient);
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
