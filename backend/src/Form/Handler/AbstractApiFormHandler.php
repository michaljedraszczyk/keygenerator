<?php

namespace App\Form\Handler;

use SoftPassio\Components\Form\Handler\AbstractFormHandler;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

abstract class AbstractApiFormHandler extends AbstractFormHandler
{
    /** @var bool */
    protected $isSuccess = true;

    public function getErrorsAsString()
    {
        $message = '';
        foreach ($this->errors as $error) {
            if (is_string($error)) {
                $message .= $this->translator->trans($error).', ';
                continue;
            }
            if (!is_array($error)) {
                $message .= implode(', ', $error);
            } else {
                if (1 === count($error)) {
                    if (is_string(array_values($error)[0])) {
                        $message .= ', '.array_values($error)[0];
                        continue;
                    }
                    foreach (array_values($error) as $messages) {
                        $message .= implode(', ', $messages);
                    }
                    continue;
                }
                foreach ($error as $messages) {
                    $message .= implode(', ', $messages);
                }
            }
        }

        return rtrim(implode(' ', explode(', ', $message)));
    }

    protected function addFieldError(string $field, string $message, array $parameters = []): void
    {
        $this->isSuccess = false;
        if (!$this->form->has($field)) {
            return;
        }
        $formError = new FormError($this->translator->trans($message, $parameters));

        $this->form->get($field)->addError($formError);
    }

    protected function addFormError($message, array $params = [])
    {
        $this->isSuccess = false;
        $message = $this->translator->trans($message, $params, 'forms');
        $this->form->addError(new FormError($message));
    }

    protected function addChildFormError($child, $message, array $params = [])
    {
        $this->isSuccess = false;
        $message = $this->translator->trans($message, $params, 'forms');
        $children = explode('.', $child);

        $form = $this->form;
        foreach ($children as $child) {
            $form = $form->get($child);
        }

        $form->addError(new FormError($message));
    }

    /**
     * Build inheritance form errors.
     *
     * @param FormInterface $form
     *
     * @return array
     */
    protected function getErrorsFromForm(FormInterface $form)
    {
        $errors = [];
        /* @var FormError $error */

        if (0 === $form->getErrors()->count() && $form->isRoot() && !$form->isValid()) {
            $errors['_self'][] = 'Empty post data';
        }

        foreach ($form->getErrors() as $error) {
            $key = !$error->getOrigin()->getName() ? '_self' : $error->getOrigin()->getName();
            $errors[$key][] = $this->translator->trans($error->getMessage());
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    unset($errors['_self']);
                    $errors = array_merge_recursive($errors, $childErrors);
                }
            }
        }

        $finalErrors = [];
        foreach ($errors as $key => $error) {
            if (is_array($error) && 1 === count($error)) {
                $finalErrors[$key] = $error[0];
            } else {
                $finalErrors[$key] = $error;
            }
        }

        return $finalErrors;
    }

    public function process()
    {
        $this->validateForm();
        $this->form->handleRequest($this->request);

        if ($this->form->isSubmitted() && !$this->isValid()) {
            $this->errors = $this->getErrorsFromForm($this->form);

            return false;
        }

        $response = $this->success();

        if (!$response) {
            $this->errors = $this->getErrorsFromForm($this->form);

            return false;
        }

        return $response;
    }
}
