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

namespace OrderComment\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Form\BaseForm;

class CommentForm extends BaseForm
{
    public function __construct(protected Session $session)
    {
    }

    public function buildForm(): void
    {
        $this->formBuilder
            ->add(
                'comment',
                TextareaType::class,
                [
                    'required' => true,
                    'data' => $this->session?->get('order_comment'),
                    'label' => 'Date et heure du retrait',
                    'attr' => [
                        'placeholder' => 'Indiquez nous la date el l\'heure souhaitées pour le retrait de votre commande',
                    ]
                ]
            );
    }

    public static function getName(): string
    {
        return 'order_comment_form';
    }
}
