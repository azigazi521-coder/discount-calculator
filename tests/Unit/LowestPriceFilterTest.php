<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Product;
use App\Tests\ServiceTestCase;
use App\Entity\Promotion;

class LowestPriceFilterTest extends ServiceTestCase
{
    public function testApplyLowestPriceFilter(): void
    {
        $product = new Product();
        $product->setPrice(100);
        $lowestPriceEnquiry = new LowestPriceEnquiry();
        $lowestPriceEnquiry->setProduct($product);
        $lowestPriceEnquiry->setQuantity(5);
        $lowestPriceEnquiry->setRequestDate('06-03-2026');
        $lowestPriceEnquiry->setPromotionId(3);

        $lowestPriceFilter = $this->container->get('App\Filter\LowestPriceFilter');
        $promotions = $this->promotionsDataProvider();

        $modifiedEnquery = $lowestPriceFilter->apply($lowestPriceEnquiry, ...$promotions);

        $this->assertEquals(250, $modifiedEnquery->getDiscountedPrice());
        $this->assertEquals(100, $modifiedEnquery->getPrice());
        $this->assertEquals(1, $modifiedEnquery->getPromotionId());
        $this->assertEquals("Wyprzedaż", $modifiedEnquery->getPromotionName());
    }

    public function promotionsDataProvider(): array
    {
        $promotion1 = new Promotion();
        $promotion1->setName('Wyprzedaż');
        $promotion1->setType('date_range_multiplier');
        $promotion1->setAdjustment(0.5);
        $promotion1->setCriteria(['from' => '2026-03-01', 'to' => '2026-03-31']);
        $promotion1->setId(1);

        $promotion2 = new Promotion();
        $promotion2->setName('Black Friday');
        $promotion2->setType('fixed_price_voucher');
        $promotion2->setAdjustment(50);
        $promotion2->setCriteria(['code' => "OU812"]);
        $promotion2->setId(2);

        $promotion3 = new Promotion();
        $promotion3->setName('Kup 2 dostaniesz 3 gratis');
        $promotion3->setType('even_items_multiplier');
        $promotion3->setAdjustment(0.5);
        $promotion3->setCriteria(['minimum_quantity' => 2]);
        $promotion3->setId(3);

        return [$promotion1, $promotion2, $promotion3];
    }
}
