<?php

namespace App\DataFixtures;

use App\Entity\Beer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AppFixtures extends Fixture
{
    private const ENDPOINT = 'https://api.punkapi.com/v2/beers';

    public function __construct(private HttpClientInterface $client) {} 

    public function load(ObjectManager $manager): void
    {
        $response = $this->client->request('GET', self::ENDPOINT);
        $beers = json_decode($response->getContent());
        $i = 0;
        foreach($beers as $beer) {
            $object[$i] = new Beer();
            $object[$i]->setName($beer->name);
            $object[$i]->setDescription($beer->description);
            $object[$i]->setPrice(rand(100, 1000));
            $object[$i]->setImageUrl($beer->image_url);
            $manager->persist($object[$i]);
            $i++;
        }
        $manager->flush();
    }
}
