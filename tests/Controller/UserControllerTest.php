<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'SensioTV');
    }

    public function testUserRegistration()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $client->clickLink('Register');
        $this->assertSelectorTextContains('h1', 'Create your account');

        // On failure
        $client->submitForm('Create your SensioTV account', [
            'user[firstName]' => '',
            'user[email]' => 'badEmail',
        ]);
        var_dump($client->getResponse()->getContent());die;
        $this->assertEquals(5, $client->getCrawler()->filter('.form-error-icon')->count());

        // On success
        $client->submitForm('Create your SensioTV account', [
            'user[firstName]' => 'Joseph',
            'user[lastName]' => 'ROUFF',
            'user[email]' => 'joseph2@joseph.io',
            'user[password][first]' => 'testtest',
            'user[password][second]' => 'testtest',
            'user[terms]' => true,
        ]);
        $this->assertEquals(0, $client->getCrawler()->filter('.form-error-icon')->count());
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('joseph2@joseph.io');

        $this->assertNotNull($user);
        $this->assertEquals('Joseph', $user->getFirstName());
        // bin/phpunit > public/test.html
    }
}
