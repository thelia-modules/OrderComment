<?php

/*
 * This file is part of the Thelia package.
 * http://www.thelia.net
 *
 * (c) OpenStudio <info@thelia.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */

/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */

namespace OrderComment;

use OrderComment\Model\OrderCommentQuery;
use Propel\Runtime\Connection\ConnectionInterface;
use OrderComment\Api\Resource\OrderComment as OrderCommentResource;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Thelia\Core\Install\Database;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Module\BaseModule;

class OrderComment extends BaseModule
{
    public const MESSAGE_DOMAIN = 'ordercomment';

    public function postActivation(?ConnectionInterface $con = null): void
    {
        $wrapped = $con->getWrappedConnection();
        $stmt = $wrapped->query("SHOW TABLES LIKE 'order_comment'");
        if ($stmt->fetchColumn() === false) {
            $database = new Database($wrapped);
            $database->insertSql(null, [__DIR__.'/Config/TheliaMain.sql']);
        }
    }

    /**
     * The admin API refuses a resource no permission is declared for: an order comment
     * answers to the order permission, as the comment is shown on the order.
     */
    public static function configureContainer(ContainerConfigurator $containerConfigurator): void
    {
        $containerConfigurator
            ->parameters()
            ->set('thelia.api.admin_resources', [
                OrderCommentResource::class => AdminResources::ORDER,
            ]);
    }

    public static function configureServices(ServicesConfigurator $servicesConfigurator): void
    {
        $servicesConfigurator->load(self::getModuleCode().'\\', __DIR__)
            ->exclude([__DIR__.'/I18n/*', __DIR__.'/Tests/*'])
            ->autowire()
            ->autoconfigure();
    }
}
