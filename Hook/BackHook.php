<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace OrderComment\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Class BackHook
 * @package OrderComment\Hook
 * @author Etienne Perriere <eperriere@openstudio.fr>
 */
class BackHook extends BaseHook
{
    public function onOrderEditAfterOrderProductList(HookRenderEvent $event)
    {
        $content = $this->render("order-edit.html");
        $event->add($content);
    }

    public function onOrderEditBillBottom(HookRenderEvent $event)
    {
        $content = $this->render("order-edit.html");
        $event->add($content);
    }

    public function onOrderTabContent(HookRenderEvent $event)
    {
        $content = $this->render("order-edit.html");
        $event->add($content);
    }

}