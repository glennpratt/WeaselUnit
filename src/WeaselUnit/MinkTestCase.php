<?php
namespace WeaselUnit;

use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Mink;
use Behat\Mink\Session;

use PHPUnit_Framework_TestCase;

abstract class MinkTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mink
     */
    protected $mink;

    /**
     * Prepare Mink for use.
     */
    protected function prepareMinkSession()
    {
        $driver = new GoutteDriver();
        $this->mink = new Mink(array(
            'selenium' => new Session($driver),
        ));
    }

    /**
     * Get the Mink session.
     * @return Mink
     */
    protected function getMink()
    {
        if (empty($this->mink)) {
            $this->prepareMinkSession();
        }
        return $this->mink;
    }
}
