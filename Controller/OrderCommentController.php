<?php

namespace OrderComment\Controller;

use Front\Front;
use OrderComment\Form\CommentForm;
use Propel\Runtime\Exception\PropelException;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\Translation\Translator;
use Thelia\Form\Exception\FormValidationException;

/**
 * Class OrderCommentController
 * @package OrderComment\Controller
 */
class OrderCommentController extends BaseFrontController
{
    public function setComment()
    {
        $message = false;
        $commentForm = new CommentForm($this->getRequest());

        try {
            $form = $this->validateForm($commentForm);
            $data = $form->getData();
            $comment = $data['comment'];

            if (!empty($comment)) {
                $this->getRequest()->getSession()->set('order-comment', $comment);
            }

            return $this->generateRedirectFromRoute("order.delivery");

        } catch (FormValidationException $e) {
            $message = Translator::getInstance()->trans("Please check your input: %s", ['%s' => $e->getMessage()], Front::MESSAGE_DOMAIN);
        } catch (PropelException $e) {
            $this->getParserContext()->setGeneralError($e->getMessage());
        } catch (\Exception $e) {
            $message = Translator::getInstance()->trans("Sorry, an error occurred: %s", ['%s' => $e->getMessage()], Front::MESSAGE_DOMAIN);
        }

        if ($message !== false) {
            $commentForm->setErrorMessage($message);

            $this->getParserContext()
                ->addForm($commentForm)
                ->setGeneralError($message)
            ;

            return $this->generateRedirectFromRoute("cart.view");
        }
    }
}
