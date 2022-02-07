<?php

namespace OrderComment\Loop;

use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

class SessionOrderCommentLoop extends BaseLoop implements ArraySearchLoopInterface
{
    public function buildArray()
    {
        $item = ['comment' => $this->requestStack->getSession()->get('order-comment')];

        return $item;
    }

    public function parseResults(LoopResult $loopResult)
    {
        $item = $loopResult->getResultDataCollection();

        $loopResultRow = new LoopResultRow();
        $loopResultRow->set('ORDER_COMMENT', $item['comment']);

        $loopResult->addRow($loopResultRow);

        return $loopResult;
    }

    public function getArgDefinitions()
    {
        return new ArgumentCollection();
    }
}
