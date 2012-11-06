<?php
namespace WeaselUnit\Tests;

use WeaselUnit\TestCase;

class TestCaseTest extends TestCase
{
    protected $fromMink = false;

    public function setUp() {
        $this->setBrowser('*firefox');
        $this->setBrowserUrl('http://localhost:8675');
        parent::setUp();
    }

    public function testOpen() {
        $this->open('http://localhost:8675');  // PHPUnit_Selenium
        $this->assertTextPresent("Mom"); // PHPUnit_Selenium
        $this->getMink()->getSession('selenium')->visit('http://localhost:8675/page1.html'); // Mink
        $page = $this->getMink()->getSession('selenium')->getPage(); // Mink
        $first_link = $page->find('css', 'a'); // Mink
        $this->assertTextPresent("cry"); // PHPUnit_Selenium
        $first_link->click(); // Mink
        $this->assertTextPresent("see"); // PHPUnit_Selenium
    }
}
