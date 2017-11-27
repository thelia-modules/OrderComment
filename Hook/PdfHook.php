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
 * Class PdfHook
 * @package OrderComment\Hook
 * @author Etienne Perriere <eperriere@openstudio.fr>
 */
class PdfHook extends BaseHook
{

    /**
     * @param HookRenderEvent $event
     */
    public function onDeliveryAfterSummary(HookRenderEvent $event)
    {
        $order_id = intval($event->getArgument('order', null));

        $event->add($this->render('delivery.after-summary.html', ['order_id' => $order_id]));
    }
}
