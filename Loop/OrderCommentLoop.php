<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 14/10/2014
 * Time: 11:25
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
     * this method returns a Propel ModelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria()
    {
        $orderCommentQuery = OrderCommentQuery::create()->filterByOrderId($this->getOrderId());

        return $orderCommentQuery;
    }

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        /** @var \OrderComment\Model\OrderComment $orderComment */
        foreach ($loopResult->getResultDataCollection() as $orderComment) {

            $loopResultRow = new LoopResultRow($orderComment);

            $loopResultRow->set("ORDER_COMMENT", $orderComment->getComment());
            $loopResult->addRow($loopResultRow);

        }

        return $loopResult;
    }
}
