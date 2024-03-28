<?php

namespace Studodev\FormUtilBundle\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Studodev\FormUtilBundle\Validator\NotDisposableEmail;
use Studodev\FormUtilBundle\Validator\NotDisposableEmailValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class NotDisposableEmailValidatorTest extends TestCase
{
    public function testValidEmail(): void
    {
        $constraint = new NotDisposableEmail();
        $validator = $this->getValidator();

        $validator->validate('test@gmail.com', $constraint);
    }

    /**
     * @dataProvider invalidEmails
     */
    public function testInvalidEmail($email): void
    {
        $constraint = new NotDisposableEmail();
        $validator = $this->getValidator(true);

        $validator->validate($email, $constraint);
    }

    private function getValidator(bool $expectedViolation = false): NotDisposableEmailValidator
    {
        $validator = new NotDisposableEmailValidator();
        $context = $this->getContext($expectedViolation);
        $validator->initialize($context);

        return $validator;
    }

    private function getContext(bool $expectedViolation): ExecutionContextInterface
    {
        $context = $this->getMockBuilder(ExecutionContextInterface::class)->getMock();

        if ($expectedViolation) {
            $violation = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->getMock();
            $violation->expects($this->once())->method('addViolation');
            $violation->expects($this->once())->method('setParameter')->willReturn($violation);
            $context->expects($this->once())->method('buildViolation')->willReturn($violation);
        } else {
            $context->expects($this->never())->method('buildViolation');
        }

        return $context;
    }

    private function invalidEmails(): array
    {
        return [
            ['test@yopmail.com'],
            ['my-address@zoemail.org'],
            ['mail.address@ycare.de'],
            ['me@wuzup.net'],
        ];
    }
}
