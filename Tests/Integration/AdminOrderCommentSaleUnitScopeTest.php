<?php

declare(strict_types=1);

namespace OrderComment\Tests\Integration;

use OrderComment\Model\OrderComment;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Domain\SaleUnit\Access\AdminSaleUnitScope;
use Thelia\Test\ApiTestCase;

/**
 * The admin order comments of an administrator restricted to some sale units are
 * those of the orders placed on them.
 */
final class AdminOrderCommentSaleUnitScopeTest extends ApiTestCase
{
    private int $mine;
    private int $theirs;
    private int $national;
    private string $restrictedToken;

    protected function setUp(): void
    {
        parent::setUp();

        $factory = $this->createFixtureFactory();
        $first = $factory->saleUnit();
        $second = $factory->saleUnit();
        $connection = $this->getPropelConnection();

        $myOrder = $factory->order()->setSaleUnitId($first->getId());
        $myOrder->save($connection);
        $theirOrder = $factory->order()->setSaleUnitId($second->getId());
        $theirOrder->save($connection);
        $nationalOrder = $factory->order();

        $this->mine = $this->comment($myOrder->getId());
        $this->theirs = $this->comment($theirOrder->getId());
        $this->national = $this->comment($nationalOrder->getId());

        $restricted = $factory->restrictedAdmin(
            [AdminResources::ORDER => [AccessManager::VIEW]],
            ['password' => 'password'],
        );
        $factory->adminSaleUnit($restricted, $first);
        $this->restrictedToken = $this->authenticateAsAdmin($restricted);
    }

    protected function tearDown(): void
    {
        $this->getService(AdminSaleUnitScope::class)->reset();

        parent::tearDown();
    }

    public function testARestrictedAdministratorReadsTheCommentsOfItsUnitsOnly(): void
    {
        self::assertSame([$this->mine], $this->listedIds($this->restrictedToken, [$this->mine, $this->theirs, $this->national]));
    }

    public function testAnAdministratorOfNoUnitReadsEverything(): void
    {
        $ids = $this->listedIds($this->authenticateAsAdmin(), [$this->mine, $this->theirs, $this->national]);

        self::assertSame([$this->mine, $this->theirs, $this->national], $ids);
    }

    private function comment(int $orderId): int
    {
        $comment = (new OrderComment())->setOrderId($orderId)->setComment('comment');
        $comment->save($this->getPropelConnection());

        return $comment->getId();
    }

    /**
     * @param list<int> $among
     *
     * @return list<int>
     */
    private function listedIds(string $token, array $among): array
    {
        $response = $this->jsonRequest('GET', '/api/admin/order-comments', token: $token, format: 'json');

        self::assertSame(200, $response->getStatusCode(), (string) $response->getContent());

        $ids = array_map(
            static fn (array $row): int => (int) $row['id'],
            json_decode((string) $response->getContent(), true, flags: \JSON_THROW_ON_ERROR),
        );
        $ids = array_values(array_intersect($ids, $among));
        sort($ids);

        return $ids;
    }
}
