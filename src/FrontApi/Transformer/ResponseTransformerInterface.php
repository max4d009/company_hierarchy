<?php


namespace App\FrontApi\Transformer;


interface ResponseTransformerInterface
{
    public function support(): string;

    public function transform($entity);

    public function transformList(array $entityList): array;
}