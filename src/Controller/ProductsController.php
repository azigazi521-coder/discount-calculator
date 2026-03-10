<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use App\DTO\LowestPriceEnquiry;
use App\Filter\PriceFilterInterface;
use App\Repository\ProductRepository;
use App\Cache\PromotionCache;

class ProductsController extends AbstractController
{

    public function __construct(
        private ProductRepository $repository
    ) {}

    #[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
    public function lowestPrice(
        Request $request,
        int $id,
        SerializerInterface $dtoSerializer,
        PriceFilterInterface $promotionsFilter,
        PromotionCache $promotionCache
    ): Response {

        /** @var LowestPriceEnquiry $lowestPriceEnquiry */
        $lowestPriceEnquiry = $dtoSerializer->deserialize(
            $request->getContent(),
            LowestPriceEnquiry::class,
            'json'
        );

        $product = $this->repository->findOrFail($id);

        $lowestPriceEnquiry->setProduct($product);

        $promotions = $promotionCache->findValidForProduct($product, $lowestPriceEnquiry->getRequestDate());

        $modifiedEnquery = $promotionsFilter->apply($lowestPriceEnquiry, ...$promotions);

        $responseContent = $dtoSerializer->serialize(
            $modifiedEnquery,
            'json'
        );

        return new JsonResponse(data: $responseContent, status: Response::HTTP_OK, json: true);
    }

    #[Route('/products/{id}/promotions', name: 'promotions', methods: 'GET')]
    public function promotions() {
    }

    #[Route('/', name: 'test', methods: 'GET')]
    public function test(): Response
    {
        return $this->render('test.html.twig');
    }
}
