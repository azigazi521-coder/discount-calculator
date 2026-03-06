<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\DTO\LowestPriceEnquiry;
use App\Tests\ServiceTestCase;
use App\Entity\Promotion;
use App\Filter\Modifier\DateRangeMultiplier;
use App\Filter\Modifier\FixedPriceVoucher;
use App\Filter\Modifier\EvenItemsMultiplier;

class PriceModifiersTest extends ServiceTestCase
{
    public function test_DateRangeMultiplier_returns_a_correctly_midified_price(): void
    {

        $enquiry = new LowestPriceEnquiry();
        $enquiry->setQuantity(5);
        $enquiry->setRequestDate('2026-03-16');

        $promotion = new Promotion();
        $promotion->setName('Wyprzedaż');
        $promotion->setType('date_rate_multiplier');
        $promotion->setAdjustment(0.5);
        $promotion->setCriteria(['from' => '2026-03-01', 'to' => '2026-03-31']);

        $dateRangeModifier = new DateRangeMultiplier();

        $modifiedPrice = $dateRangeModifier->modify(100, 5, $promotion, $enquiry);

        $this->assertEquals(250, $modifiedPrice);
    }

    public function test_FixedPriceVoucher_returns_a_correctly_midified_price(): void
    {

        $enquiry = new LowestPriceEnquiry();
        $enquiry->setQuantity(5);
        $enquiry->setVoucherCode("OU812");

        $promotion = new Promotion();
        $promotion->setName('Black Friday');
        $promotion->setType('fixed_price_voucher');
        $promotion->setAdjustment(100);
        $promotion->setCriteria(['code' => "OU812"]);

        $dateRangeModifier = new FixedPriceVoucher();

        $modifiedPrice = $dateRangeModifier->modify(150, 5, $promotion, $enquiry);

        $this->assertEquals(500, $modifiedPrice);
    }

    public function test_EvenItemsMultiplier_returns_a_correctly_midified_price(): void
    {

        $enquiry = new LowestPriceEnquiry();
        $enquiry->setQuantity(5);

        $promotion = new Promotion();
        $promotion->setName('Kup 2 dostaniesz 1 gratis');
        $promotion->setType('even_items_multiplier');
        $promotion->setAdjustment(0.5);
        $promotion->setCriteria(['minimum_quantity' => 2]);

        $dateRangeModifier = new EvenItemsMultiplier();

        $modifiedPrice = $dateRangeModifier->modify(100, 5, $promotion, $enquiry);

        $this->assertEquals(300, $modifiedPrice);
    }
}
