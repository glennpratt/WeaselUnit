<?php
namespace WeaselUnit\Tests;

use WeaselUnit\TestCase;

class TestCaseTest extends TestCase
{
    public function setUp() {
        $this->setBrowser('*firefox');
        $this->setBrowserUrl('http://www.google.com');
        parent::setUp();
    }

    public function testOpen() {
        $this->open('http://www.google.com/');  // PHPUnit_Selenium
        $this->assertTextPresent("I'm Feeling Lucky"); // PHPUnit_Selenium
        $this->getMink()->getSession('selenium')->visit('http://www.yahoo.com/'); // Mink
        $page = $this->getMink()->getSession('selenium')->getPage(); // Mink
        $first_link = $page->find('css', 'a'); // Mink
        $this->assertTextPresent("Yahoo"); // PHPUnit_Selenium
        $first_link->click(); // Mink
        $this->assertTextPresent("Yahoo"); // PHPUnit_Selenium
    }
}
