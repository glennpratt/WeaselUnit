<?php
namespace SeleniumMinkMigrate\Selenium;

class Client extends \Selenium\Client
{
    public $sessionId;

    /**
     * Creates a new browser instance.
     *
     * @param string $startPage The URL of the website to test
     * @param string $type      Type of browser, for Selenium
     *
     * @return Selenium\Browser A browser instance
     */
    public function getBrowser($startPage, $type = '*firefox')
    {
        $url    = 'http://'.$this->host.':'.$this->port.'/selenium-server/driver/';
        $driver = new Driver($url, $this->timeout);

        if ($this->sessionId) {
            $driver->setSessionId($this->sessionId);
        }

        $class  = $this->browserClass;

        return new $class($driver, $startPage, $type);
    }
}