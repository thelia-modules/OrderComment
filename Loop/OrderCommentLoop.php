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

namespace OrderComment\Loop;

use OrderComment\Model\OrderCommentQuery;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

class OrderCommentLoop extends BaseLoop implements PropelSearchLoopInterface
{
    /**
     * @return \Thelia\Core\Template\Loop\Argument\ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('order_id', null, true)
        );
    }

    /**
     * this method returns a Propel ModelCriteria.
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria()
    {
        $orderCommentQuery = OrderCommentQuery::create()->filterByOrderId($this->getOrderId());

        return $orderCommentQuery;
    }

    /**
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        /** @var \OrderComment\Model\OrderComment $orderComment */
        foreach ($loopResult->getResultDataCollection() as $orderComment) {
            $loopResultRow = new LoopResultRow($orderComment);

            $loopResultRow->set('ORDER_COMMENT', $orderComment->getComment());
            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}
