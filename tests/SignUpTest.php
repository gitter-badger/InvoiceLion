<?php
use Goutte\Client;
use PHPUnit\Framework\TestCase;

class SignUpTest extends TestCase
{
    public function testRegistration()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'http://localhost:8000/');
        $link = $crawler->selectLink('Register')->link();
        $crawler = $client->click($link);
        $form = $crawler->selectButton('Submit')->form();
        $crawler = $client->submit($form, array('username' => 'maurits@vdschee.nl'));
        $nodes = $crawler->filter('.alert');
        $nodes->each(function ($node) {
            $this->assertEquals('', $node->text(), 'Validation error occurred');
        });
        $this->assertCount(0, $nodes);
        $this->assertNotEquals(500, $client->getResponse()->getStatus(), '500 returned on form submission');
        $this->assertEquals('Login link sent', $crawler->filter('h1')->text(), 'Wrong title for resulting page');
    }
}
