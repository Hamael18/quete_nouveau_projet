<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;


class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i=0; $i<50; $i++) {

            $article = new Article();
            $article->setTitle(mb_strtolower($faker->sentence($nbWords=3)));
            $article->setContent($faker->paragraph($nb=2));

            $manager->persist($article);
            $article->setCategory($this->getReference('categorie_'.rand(0,4)));
            $manager->flush();
        }
    }
}