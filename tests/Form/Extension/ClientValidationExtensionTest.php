<?php

namespace Studodev\FormUtilBundle\Tests\Form\Extension;

use PHPUnit\Framework\TestCase;
use Studodev\FormUtilBundle\Form\Extension\ClientValidationExtension;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormView;

class ClientValidationExtensionTest extends TestCase
{
    public function testValidationDisabled(): void
    {
        $formFactory = $this->createFormFactory();
        $formView = $this->createFormView($formFactory);

        $this->assertArrayHasKey('novalidate', $formView->vars['attr']);
    }

    public function testValidationEnabled(): void
    {
        $formFactory = $this->createFormFactory(false);
        $formView = $this->createFormView($formFactory);

        $this->assertArrayNotHasKey('novalidate', $formView->vars['attr']);
    }

    private function createFormFactory(bool $disableValidation = true): FormFactoryInterface
    {
        return Forms::createFormFactoryBuilder()
            ->addTypeExtension(new ClientValidationExtension($disableValidation))
            ->getFormFactory()
        ;
    }

    private function createFormView(FormFactoryInterface $formFactory): FormView
    {
        return $formFactory->createBuilder()
            ->add('name', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm()
            ->createView()
        ;
    }
}
