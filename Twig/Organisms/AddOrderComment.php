<?php

namespace OrderComment\Twig\Organisms;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use OrderComment\Service\OrderCommentService;
use TwigEngine\Service\FormService;

#[AsLiveComponent(name: 'AddOrderComment', template: '@OrderCommentModule/components/AddOrderComment.html.twig')]
class AddOrderComment extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp(writable: true)]
    public string $comment = '';

    public ?int $cartId = null;


    public function __construct(
        private readonly FormService $formService,
        private readonly RequestStack $requestStack,
        private readonly OrderCommentService $orderCommentService
    ) {}

    public function mount(): void
    {
        $session = $this->requestStack->getSession();
        $existingComment = $session->get('order_comment', '');

        if ($existingComment && empty($this->comment)) {
            $this->comment = $existingComment;
        }
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->formService->getFormByName('order_comment_form');
    }

    #[LiveAction]
    public function save(): void
    {
        try {
            $this->submitForm();
            if ($this->getForm()->isSubmitted() && $this->getForm()->isValid()) {
                $data = $this->getForm()->getData();

                $this->comment = $data['comment'];
                $this->orderCommentService->saveComment($this->comment);

            }
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }
}