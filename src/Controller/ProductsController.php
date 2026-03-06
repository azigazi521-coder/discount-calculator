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
use Doctrine\ORM\EntityManagerInterface;
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

        if ($request->headers->has('force_fail')) {
            $status = (int)$request->headers->get('force_fail');
            return new JsonResponse(
                ["error" => "Forced failure for testing purposes"],
                $status  >= 100 && $status <= 599 ? $status : 500
            );
        }
        /** @var LowestPriceEnquiry $lowestPriceEnquiry */
        $lowestPriceEnquiry = $dtoSerializer->deserialize(
            $request->getContent(),
            LowestPriceEnquiry::class,
            'json'
        );

        $product = $this->repository->find($id);

        $lowestPriceEnquiry->setProduct($product);

        $promotions = $promotionCache->findValidForProduct($product, $lowestPriceEnquiry->getRequestDate());

        $modifiedEnquery = $promotionsFilter->apply($lowestPriceEnquiry, ...$promotions);

        $responseContent = $dtoSerializer->serialize(
            $modifiedEnquery,
            'json'
        );

        return new Response($responseContent, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/products/{id}/promotions', name: 'promotions', methods: 'GET')]
    public function promotions() {}
}
