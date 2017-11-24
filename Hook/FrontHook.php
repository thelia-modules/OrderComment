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
 * Class FrontHook
 * @package OrderComment\Hook
 * @author Etienne Perriere <eperriere@openstudio.fr>
 */
class FrontHook extends BaseHook
{
    public function onCartBottom(HookRenderEvent $event)
    {
        $event->add($this->render("OrderComment/cart-comment.html"));
    }

    public function onCartIncludeJs(HookRenderEvent $event)
    {
        $event->add(($this->addJS("OrderComment/assets/js/cart-comment-js.js")));
    }

    public function onDeliveryFormBottom(HookRenderEvent $event)
    {
        $event->add($this->render("OrderComment/comment-input.html"));
    }
}