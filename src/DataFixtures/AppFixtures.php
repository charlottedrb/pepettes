<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use App\Entity\City;
use App\Entity\Place;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $dataPlaces = $serializer->decode(file_get_contents(dirname(__FILE__) . '/csv/places.csv'), 'csv', ['csv_delimiter' => ';']);
        $dataCities = $serializer->decode(file_get_contents(dirname(__FILE__) . '/csv/cities.csv'), 'csv', ['csv_delimiter' => ';']);
        $dataTags = $serializer->decode(file_get_contents(dirname(__FILE__) . '/csv/tags.csv'), 'csv', ['csv_delimiter' => ';']);

        foreach ($dataCities as $d) {
            $city = new City();
            $city->setName($d['name']);
            $city->setCountry($d['country']);
            $this->addReference('city_' . $d['name'], $city);
            $manager->persist($city);
        }
        
        foreach ($dataTags as $d) {
            $tag = new Tag();
            $tag->setName($d['name']);
            $this->addReference('tag_' . trim(str_replace(' ', '_', $d['name'])), $tag);
            $manager->persist($tag);
        }

        foreach ($dataPlaces as $d) {
            $place = new Place();
            $place->setName($d['name']);
            $place->setPriceRange($d['price_range']);
            $place->setSecurityLevel($d['security_level']);
            $place->setCharoRate($d['charo_rate']);
            $place->setHasCocktails($d['has_cocktails']);
            $place->setHasBeers($d['has_beers']);
            $place->setHasWines($d['has_wines']);
            $place->setHasSofts($d['has_softs']);
            $place->setDescription($d['description']);
            $place->setImageFilename($d['image']);
            $place->setCity($this->getReference('city_' . $d['city']));
            $place->setTips($d['tips']);
            $place->setRecommandations($d['recommandation']);
            $place->setCreatedAt(new DateTimeImmutable());
            $tags = explode('|', $d['tags']);
            foreach ($tags as $t) {
                $place->addTag($this->getReference('tag_' . trim(str_replace(' ', '_', $t))));
            }
            $manager->persist($place);
        }
        $manager->flush();
    }
}
