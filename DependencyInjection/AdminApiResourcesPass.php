<?php

declare(strict_types=1);

namespace OrderComment\DependencyInjection;

use OrderComment\Api\Resource\OrderComment as OrderCommentResource;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Thelia\Core\Security\Resource\AdminResources;

/**
 * Gives the order comments the order permission: an order comment is shown on the order.
 * Without an entry in `thelia.api.admin_resources`, the admin API refuses the endpoint to
 * every administrator but a superadministrator.
 *
 * Merged rather than set: the parameter is one array shared by every module publishing
 * admin resources, and setting it would erase the others.
 */
final readonly class AdminApiResourcesPass implements CompilerPassInterface
{
    private const PARAMETER = 'thelia.api.admin_resources';

    public function process(ContainerBuilder $container): void
    {
        $declared = $container->hasParameter(self::PARAMETER) ? (array) $container->getParameter(self::PARAMETER) : [];

        $container->setParameter(self::PARAMETER, [
            ...$declared,
            OrderCommentResource::class => AdminResources::ORDER,
        ]);
    }
}
