<?php

namespace Studodev\FormUtilBundle\Tests\Form\Extension;

use PHPUnit\Framework\TestCase;
use Studodev\FormUtilBundle\Form\Extension\FileAcceptExtension;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormView;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validation;

class FileAcceptExtensionTest extends TestCase
{
    public function testWithMimeTypes(): void
    {
        $formFactory = $this->createFormFactory();

        $mimeTypes = ['application/pdf', 'application/x-pdf'];
        $fileConstraint = new File(mimeTypes: $mimeTypes);
        $formView = $this->createFormView($formFactory, $fileConstraint);
        $fileInputAttrs = $formView->children['file']->vars['attr'];

        $this->assertArrayHasKey('accept', $fileInputAttrs);
        $this->assertEquals(implode(',', $mimeTypes), $fileInputAttrs['accept']);
    }

    public function testWithExtensions(): void
    {
        $formFactory = $this->createFormFactory();

        $extensions = ['mp4', 'avi', 'webm'];
        $fileConstraint = new File(extensions: $extensions);
        $formView = $this->createFormView($formFactory, $fileConstraint);
        $fileInputAttrs = $formView->children['file']->vars['attr'];

        $mimeTypes = $this->getMimeTypesFromExtensions($extensions);
        $this->assertArrayHasKey('accept', $fileInputAttrs);
        $this->assertEquals(implode(',', $mimeTypes), $fileInputAttrs['accept']);
    }

    public function testWithExtension(): void
    {
        $formFactory = $this->createFormFactory();

        $extension = 'docx';
        $fileConstraint = new File(extensions: $extension);
        $formView = $this->createFormView($formFactory, $fileConstraint);
        $fileInputAttrs = $formView->children['file']->vars['attr'];

        $mimeTypes = $this->getMimeTypesFromExtensions($extension);
        $this->assertArrayHasKey('accept', $fileInputAttrs);
        $this->assertEquals(implode(',', $mimeTypes), $fileInputAttrs['accept']);
    }

    public function testExtensionDisabled(): void
    {
        $formFactory = $this->createFormFactory(false);

        $mimeTypes = ['image/png'];
        $fileConstraint = new File(mimeTypes: $mimeTypes);
        $formView = $this->createFormView($formFactory, $fileConstraint);
        $fileInputAttrs = $formView->children['file']->vars['attr'];

        $this->assertArrayNotHasKey('accept', $fileInputAttrs);
    }

    private function createFormFactory(bool $enableAcceptAttribute = true): FormFactoryInterface
    {
        $validator = Validation::createValidator();

        return Forms::createFormFactoryBuilder()
            ->addExtension(new ValidatorExtension($validator))
            ->addTypeExtension(new FileAcceptExtension($enableAcceptAttribute, $validator))
            ->getFormFactory()
        ;
    }

    private function createFormView(FormFactoryInterface $formFactory, File $fileConstraint): FormView
    {
        return $formFactory->createBuilder()
            ->add('file', FileType::class, [
                'constraints' => [
                    $fileConstraint,
                ],
            ])
            ->add('submit', SubmitType::class)
            ->getForm()
            ->createView()
        ;
    }

    private function getMimeTypesFromExtensions(array|string $extensions): array
    {
        if (is_string($extensions)) {
            $extensions = [$extensions];
        }

        $mimeTypeHelper = MimeTypes::getDefault();
        $mimeTypes = [];

        foreach ($extensions as $extension) {
            $mimeTypes = array_merge($mimeTypes, $mimeTypeHelper->getMimeTypes($extension));
        }

        return $mimeTypes;
    }
}
