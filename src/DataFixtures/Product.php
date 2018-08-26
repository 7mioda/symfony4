<?php

namespace App\DataFixtures;

use App\Service\FileUploader;
use date;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Product as ProductEntity;
Use Faker\Factory;
use Faker\Provider\DateTime;
use Faker\Provider\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @property FileUploader fileUploader
 */
class Product extends Fixture
{

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for($i = 0; $i <100 ; $i++){
            $product = new ProductEntity();
            $product->setName($faker->name())
                ->setPrice($faker->randomFloat())
                ->setDescription($faker->text(150))
                ->setQuantity($faker->randomFloat())
                ->setUpdatedAt($faker->dateTimeThisCentury());
            $manager->persist($product);
        }
        $manager->flush();
    }
}


