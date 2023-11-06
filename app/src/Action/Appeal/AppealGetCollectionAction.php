<?php

namespace App\Action\Appeal;

use App\Dto\Appeal\Filter\FilterDto;
use App\Dto\Product\Request\RequestSearchDto;
use App\Entity\User\User;
use App\Service\AppealService;
use Knp\Component\Pager\PaginatorInterface;

class AppealGetCollectionAction
{
    public function __construct(
        private readonly AppealService $service
    )
    {
    }

    public function handle(PaginatorInterface $paginator, FilterDto $dto, User $user): array
    {
        return $this->service->getCollection($paginator, $dto, $user);
    }
}
