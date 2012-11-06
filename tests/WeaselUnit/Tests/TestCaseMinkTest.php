<?php
namespace WeaselUnit\Tests;

use WeaselUnit\TestCase;

class TestCaseMinkTest extends TestCase
{
    protected $fromMink = false;

    public function setUp()
    {
      $this->setBrowser('*firefox');
      $this->setBrowserUrl('http://localhost:8675');
      parent::setUp();
    }

    public function testOpen()
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
