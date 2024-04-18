<?php

namespace Studodev\FormUtilBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileAcceptExtension extends AbstractTypeExtension
{
    private MimeTypes $mimeTypesHelper;

    public function __construct(
        private readonly bool $enableAcceptAttribute,
        private readonly ValidatorInterface $validator
    ) {
        $this->mimeTypesHelper = MimeTypes::getDefault();
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (!$this->enableAcceptAttribute) {
            return;
        }

        $mimeTypes = $this->computeFromForm($form);
        if (count($mimeTypes) < 1) {
            $mimeTypes = $this->computeFromDataClass($form);
        }

        if (count($mimeTypes) < 1) {
            return;
        }

//        $view->vars['attr']['accept'] = implode(',', $mimeTypes);
    }

    private function computeFromForm(FormInterface $form): array
    {
        $constraints = $form->getConfig()->getOption('constraints');
        $constraint = $this->findFileConstraint($constraints);
        if (!$constraint) {
            return [];
        }

        return $this->computeMimeTypes($constraint);
    }

    private function computeFromDataClass(FormInterface $form): array
    {
        $dataClass = $form->getParent()->getConfig()->getDataClass();
        $propertyName = $form->getName();

        $dataClassMetadata = $this->validator->getMetadataFor($dataClass);
        $propertyMetadata = $dataClassMetadata->getPropertyMetadata($propertyName);

        foreach ($propertyMetadata as $metadata) {
            $constraint = $this->findFileConstraint($metadata->constraints);
            if (!$constraint) {
                return [];
            }

            return $this->computeMimeTypes($constraint);
        }

        return [];
    }

    private function findFileConstraint(array $constraints): ?File
    {
        foreach ($constraints as $constraint) {
            if ($constraint instanceof File) {
                return $constraint;
            }
        }

        return null;
    }

    private function computeMimeTypes(File $constraint): array
    {
        $mimeTypes = [];
        if (!empty($constraint->extensions)) {
            if (is_array($constraint->extensions)) {
                foreach ($constraint->extensions as $key => $extension) {
                    if (!is_string($key)) {
                        $mimeTypes = array_merge($mimeTypes, $this->mimeTypesHelper->getMimeTypes($extension));
                    } else {
                        $mimeTypes = array_merge($mimeTypes, (array) $extension);
                    }
                }

                return array_unique($mimeTypes);
            } else {
                return $this->mimeTypesHelper->getMimeTypes($constraint->extensions);
            }
        }

        if (!empty($constraint->mimeTypes)) {
            return array_unique((array) $constraint->mimeTypes);
        }

        return [];
    }

    public static function getExtendedTypes(): iterable
    {
        return [FileType::class];
    }
}
