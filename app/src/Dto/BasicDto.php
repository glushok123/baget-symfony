<?php

namespace App\Dto;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class BasicDto implements DtoInterface
{
    public function toArray(array $groups = []): array
    {
        $metadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new PropertyNormalizer($metadataFactory, null);
        $serializer = new Serializer([$normalizer, new DateTimeNormalizer(), new ObjectNormalizer()]);

        return $serializer->normalize(
            $this,
            null,
            ['groups' => $groups]
        );
    }
}
