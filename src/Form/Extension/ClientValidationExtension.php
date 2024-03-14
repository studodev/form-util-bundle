<?php
namespace Studodev\FormUtilBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ClientValidationExtension extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (!$form->isRoot()) {
            return;
        }

        $view->vars['attr']['novalidate'] = null;
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}
