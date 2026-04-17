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
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Thelia\Core\Install\Database;
use Thelia\Module\BaseModule;

class OrderComment extends BaseModule
{
    public function postActivation(ConnectionInterface $con = null): void
    {
        $wrapped = $con->getWrappedConnection();
        $stmt = $wrapped->query("SHOW TABLES LIKE 'order_comment'");
        if ($stmt->fetchColumn() === false) {
            $database = new Database($wrapped);
            $database->insertSql(null, [__DIR__.'/Config/TheliaMain.sql']);
        }
    }

    public static function configureServices(ServicesConfigurator $servicesConfigurator): void
    {
        $servicesConfigurator->load(self::getModuleCode().'\\', __DIR__)
            ->exclude([__DIR__.'/I18n/*'])
            ->autowire()
            ->autoconfigure();
    }
}
