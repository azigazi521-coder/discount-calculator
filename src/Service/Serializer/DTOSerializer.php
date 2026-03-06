<?php

declare(strict_types=1);

namespace App\Service\Serializer;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;

class DTOSerializer implements SerializerInterface
{
    private SerializerInterface $serializer;

    public function __construct()
    {
        
        $this->serializer = new Serializer(
            [new ObjectNormalizer(
                classMetadataFactory: new ClassMetadataFactory(new AttributeLoader()),
                nameConverter: new CamelCaseToSnakeCaseNameConverter())],
            [new JsonEncoder()]
        );
    }

    public function serialize($data, string $format, array $context = []): string
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    /**
     * @template TObject of object
     * @template TType of string|class-string<TObject>
     */
    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        return $this->serializer->deserialize($data, $type, $format, $context);
    }
}
