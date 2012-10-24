<?php
namespace WeaselUnit;

use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\Mink\Driver\SeleniumDriver;

use WeaselUnit\Selenium\Client as SeleniumClient;

use PHPUnit_Extensions_SeleniumTestCase;
use ReflectionClass;

abstract class TestCase extends PHPUnit_Extensions_SeleniumTestCase
{
    protected $seleniumClient;

    protected $seleniumDriver;

    /**
     * @var Mink
     */
    protected $mink;

    public function setUp() {
        $this->prepareTestSession();
        $sessionId = $this->getPHPUnitSessionId();
        $legacy_driver = $this->drivers[0];
        //var_dump($legacy_driver);
        //var_dump($sessionId);
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
    public function getMink() {
        return $this->mink;
    }

    public function getPHPUnitSessionId() {
        $driver = $this->drivers[0];
        $class = new ReflectionClass(get_class($driver));
        $property = $class->getProperty("sessionId");
        $property->setAccessible(true);

        return $property->getValue($driver);
    }

}
