<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 05/11/2014
 * Time: 11:26
 */

namespace OrderComment\Form;


use Thelia\Form\BaseForm;

class CommentForm extends BaseForm
{
    public function buildForm()
    {
        $this->formBuilder
            ->add(
                'comment',
                'textarea',
                array(
                    'required' => false
                )
            );
    }

    public function getName()
    {
        return "order_comment_form";
    }
}
