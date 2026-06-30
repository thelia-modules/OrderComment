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
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request || !$request->hasSession()) {
            return [];
        }
        $comment = $request->getSession()->get('order-comment');

        return $comment ? [$comment] : [];
    }
}
