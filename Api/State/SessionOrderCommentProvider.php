<?php

namespace OrderComment\Api\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

readonly class SessionOrderCommentProvider implements ProviderInterface
{
    public function __construct(private RequestStack $requestStack) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $comment = $this->requestStack->getSession()->get('order-comment');

        return $comment ? [$comment] : [];
    }
}
