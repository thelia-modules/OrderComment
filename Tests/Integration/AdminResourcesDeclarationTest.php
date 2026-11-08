<?php

declare(strict_types=1);

namespace OrderComment\Tests\Integration;

use OrderComment\Api\Resource\OrderComment as OrderCommentResource;
use OrderComment\OrderComment;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Thelia\Core\Security\Resource\AdminResources;

/**
 * The "thelia.api.admin_resources" parameter is shared by every module that publishes
 * admin resources: the module adds its own to what is already declared.
 */
final class AdminResourcesDeclarationTest extends TestCase
{
    public function testTheResourceIsAddedToWhatAnotherModuleDeclared(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('thelia.api.admin_resources', ['Other\\Resource' => AdminResources::MODULE]);

        foreach (OrderComment::getCompilers() as $pass) {
            $pass->process($container);
        }

        self::assertSame(
            ['Other\\Resource' => AdminResources::MODULE, OrderCommentResource::class => AdminResources::ORDER],
            $container->getParameter('thelia.api.admin_resources'),
        );
    }

    public function testTheResourceIsDeclaredWhenNoOtherModuleDeclaresAny(): void
    {
        $container = new ContainerBuilder();

        foreach (OrderComment::getCompilers() as $pass) {
            $pass->process($container);
        }

        self::assertSame(
            [OrderCommentResource::class => AdminResources::ORDER],
            $container->getParameter('thelia.api.admin_resources'),
        );
    }
}
