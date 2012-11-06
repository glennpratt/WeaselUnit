<?php
namespace WeaselUnit\Tests;

use WeaselUnit\TestCase;

class TestCaseTest extends TestCase
{
    protected $fromMink = false;

    public function setUp()
    {
        $this->setBrowser('*firefox');
        $this->setBrowserUrl('http://localhost:8675');
        parent::setUp();
    }

    public function testMixed()
    {
        $this->open('http://localhost:8675');  // PHPUnit_Selenium
        $this->assertTextPresent("Mom"); // PHPUnit_Selenium
        $this->getMink()->getSession('selenium')->visit('http://localhost:8675/page1.html'); // Mink
        $page = $this->getMink()->getSession('selenium')->getPage(); // Mink
        $first_link = $page->find('css', 'a'); // Mink
        $this->assertTextPresent("cry"); // PHPUnit_Selenium
        $first_link->click(); // Mink
        $this->assertTextPresent("see"); // PHPUnit_Selenium
    }

    public function testMinkOnly()
    {
      $session = $this->getMink()->getSession('selenium');
      $assert = $this->getMink()->assertSession('selenium');

      $session->visit('http://localhost:8675/');
      $assert->pageTextContains('Hi Mom');
      $session->visit('http://localhost:8675/page1.html');
      $assert->pageTextContains('see');

      $first_link = $session->getPage()->find('css', 'a');
      $first_link->click();
      $assert->pageTextContains('cry');
    }
}
