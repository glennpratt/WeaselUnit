<?php
namespace SeleniumMinkMigrate\Tests;

use SeleniumMinkMigrate\TestCase;

class TestCaseTest extends TestCase
{
    public function setUp() {
        $this->setBrowser('*firefox');
        $this->setBrowserUrl('http://www.google.com');
    }

    public function testOpen() {
        $this->open('http://www.google.com/');
        $this->assertTextPresent("I'm Feeling Lucky");
    }
}
