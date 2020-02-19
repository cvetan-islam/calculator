<?php


namespace App\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
  public function testShowPost() {

    $client = static::createClient();
    $client->request('POST', '/calculator/result', ['expression' => '63/9*3+22']);
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertSelectorTextContains('div#result', 'Result: 63/9*3+22 = 43.');


  }

  public function testShowPost1() {
    $client1 = static::createClient();
    $client1->request('POST', '/calculator/result', ['expression' => '63/9*3+25']);
    $this->assertEquals(200, $client1->getResponse()->getStatusCode());
    $this->assertSelectorTextContains('div#result', 'Result: 63/9*3+25 = 46.');

  }

  public function testShowPost2() {
    $client = static::createClient();
    $client->request('POST', '/calculator/result', ['expression' => '22+4.0*10.0/2.0*3.0']);
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertSelectorTextContains('div#result', 'Result: 22+4.0*10.0/2.0*3.0 = 82');
  }


  public function testShowPost3() {
    $client = static::createClient();
    $client->request('POST', '/calculator/result', ['expression' => '-25+5-4+20/2-10']);
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertSelectorTextContains('div#result', 'Result: -25+5-4+20/2-10 = -24.');
  }

  public function testShowPost4() {
    $client = static::createClient();
    $client->request('POST', '/calculator/result', ['expression' => '96/2+35*10-11*10$asd']);
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertSelectorTextContains('div#result', 'Result: Incorrect expression (unexpected characters)96/2+35*10-11*10$asd.');
  }

  public function testShowPost5() {
    $client = static::createClient();
    $client->request('POST', '/calculator/result', ['expression' => '56+55/2']);
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertSelectorTextContains('div#result', 'Result: 56+55/2 = 83.5');
  }

  public function testShowPost6() {
    $client = static::createClient();
    $client->request('POST', '/calculator/result', ['expression' => '9**3']);
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertSelectorTextContains('div#result', 'Result: 9**3 = 729');
  }

  public function testShowPost7() {
    $client = static::createClient();
    $client->request('POST', '/calculator/result', ['expression' => '9***3']);
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertSelectorTextContains('div#result', 'Result: Incorrect expression: 9***3.');
  }

  public function testShowPost8() {
    $client = static::createClient();
    $client->request('POST', '/calculator/result', ['expression' => '5-21/-7']);
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertSelectorTextContains('div#result', 'Result: 5-21/-7 = 8');
  }

}