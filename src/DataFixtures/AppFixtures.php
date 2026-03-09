<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Promotion;
use App\Entity\ProductPromotion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [];
        for ($i = 1; $i <= 3; $i++) {
            $product = new Product();
            $product->setPrice($i * 100);
            $manager->persist($product);
            $products[] = $product;
        }

        $promotions = [];
        $date = new \DateTime();

        $promoData = [
            [
                'Wyprzedaż',
                'date_range_multiplier',
                0.5,
                [
                    "from" => $date->format('Y-m-d'),
                    "to" => $date->modify('+15 days')->format('Y-m-d')
                ]
            ],
            ['Kod Rabatowy', 'fixed_price_voucher', 100, ['code' => 'OU812']],
            ['Black Friday', 'even_items_multiplier', 0.5, ['minimum_quantity' => 2]]
        ];

        foreach ($promoData as $data) {
            $promotion = new Promotion();
            $promotion->setName($data[0]);
            $promotion->setType($data[1]);
            $promotion->setAdjustment($data[2]);
            $promotion->setCriteria($data[3]);
            $manager->persist($promotion);
            $promotions[] = $promotion;
        }

        for ($i = 0; $i < 3; $i++) {
            $pp = new ProductPromotion();
            $pp->setProduct($products[$i]);
            $pp->setPromotion($promotions[$i]);
            $pp->setValidTo(new \DateTime('+30 days'));
            $manager->persist($pp);
        }

        $extraPromotion = new ProductPromotion();
        $extraPromotion->setProduct($products[0]);
        $extraPromotion->setPromotion($promotions[1]);
        $extraPromotion->setValidTo(new \DateTime('+15 days'));
        $manager->persist($extraPromotion);

        $extraPromotion = new ProductPromotion();
        $extraPromotion->setProduct($products[0]);
        $extraPromotion->setPromotion($promotions[2]);
        $extraPromotion->setValidTo(new \DateTime('+15 days'));
        $manager->persist($extraPromotion);

        $manager->flush();
    }
}
