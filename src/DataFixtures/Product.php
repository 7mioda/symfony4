<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Product as ProductEntity;
Use Faker\Factory;

class Product extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for($i = 0; $i <100 ; $i++){
            $product = new ProductEntity();
            $product->setName($faker->name());
            $product->setPrice($faker->randomFloat());
            $product->setDescription($faker->text(150));
            $product->setQuantity($faker->randomFloat());
            $manager->persist($product);
        }
        $manager->flush();
    }
}
