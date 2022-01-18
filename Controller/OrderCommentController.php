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

namespace OrderComment\Controller;

use Front\Front;
use OrderComment\Form\CommentForm;
use Propel\Runtime\Exception\PropelException;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Translation\Translator;
use Thelia\Form\Exception\FormValidationException;

/**
 * Class OrderCommentController.
 */
class OrderCommentController extends BaseFrontController
{
    public function setComment(Request $request)
    {
        $message = false;
        $commentForm = $this->createForm(CommentForm::getName());

        try {
            $form = $this->validateForm($commentForm);
            $data = $form->getData();
            $comment = $data['comment'];

            if (!empty($comment)) {
                $request->getSession()->set('order-comment', $comment);
            }

            return $this->generateRedirectFromRoute('order.delivery');
        } catch (FormValidationException $e) {
            $message = Translator::getInstance()->trans('Please check your input: %s', ['%s' => $e->getMessage()], Front::MESSAGE_DOMAIN);
        } catch (PropelException $e) {
            $this->getParserContext()->setGeneralError($e->getMessage());
        } catch (\Exception $e) {
            $message = Translator::getInstance()->trans('Sorry, an error occurred: %s', ['%s' => $e->getMessage()], Front::MESSAGE_DOMAIN);
        }

        if ($message !== false) {
            $commentForm->setErrorMessage($message);

            $this->getParserContext()
                ->addForm($commentForm)
                ->setGeneralError($message)
            ;

            return $this->generateRedirectFromRoute('cart.view');
        }
    }
}
