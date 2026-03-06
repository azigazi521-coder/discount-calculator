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
        // 1. Tworzymy 3 Produkty
        $products = [];
        for ($i = 1; $i <= 3; $i++) {
            $product = new Product();
            $product->setPrice($i * 100); // 100, 200, 300
            $manager->persist($product);
            $products[] = $product;
        }

        // 2. Tworzymy 3 Promocje
        $promotions = [];
        $promoData = [
            ['Wyprzedaż', 'date_rate_multiplier', 0.5, ["to" => "2026-03-31", "from" => "2026-03-01"]],
            ['Black Friday', 'fixed_price_voucher', 50, ['code' => 'OU812']],
            ['Kod Rabatowy', 'fixed_price_voucher', 200, ['code' => 'OU813']]
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

        // 3. Łączymy je w tabeli ProductPromotion
        for ($i = 0; $i < 3; $i++) {
            $pp = new ProductPromotion();
            $pp->setProduct($products[$i]);     // Powiązanie z obiektem Product
            $pp->setPromotion($promotions[$i]); // Powiązanie z obiektem Promotion
            $pp->setValidTo(new \DateTime('+30 days'));
            $manager->persist($pp);
        }

        // DODATKOWA PROMOCJA DLA PIERWSZEGO PRODUKTU
        // Teraz produkt nr 1 (index 0) będzie miał przypisane promocje nr 1 i nr 2
        $extraPromotion = new ProductPromotion();
        $extraPromotion->setProduct($products[0]);      // Pierwszy produkt
        $extraPromotion->setPromotion($promotions[1]);   // Druga promocja (np. Black Friday)
        $extraPromotion->setValidTo(new \DateTime('+15 days'));
        $manager->persist($extraPromotion);

        // Zapisujemy wszystko do bazy jednym "flushem"

        $manager->flush();
    }
}
