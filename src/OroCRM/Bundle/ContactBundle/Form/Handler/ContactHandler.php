<?php

namespace OroCRM\Bundle\ContactBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Common\Persistence\ObjectManager;
use OroCRM\Bundle\ContactBundle\Entity\Contact;
use OroCRM\Bundle\AccountBundle\Entity\Account;

class ContactHandler
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     *
     * @param FormInterface $form
     * @param Request       $request
     * @param ObjectManager $manager
     */
    public function __construct(FormInterface $form, Request $request, ObjectManager $manager)
    {
        $this->form    = $form;
        $this->request = $request;
        $this->manager = $manager;
    }

    /**
     * Process form
     *
     * @param  Contact $entity
     * @return bool True on successful processing, false otherwise
     */
    public function process(Contact $entity)
    {
        $this->form->setData($entity);

        if (in_array($this->request->getMethod(), array('POST', 'PUT'))) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $appendAccounts = $this->form->get('appendAccounts')->getData();
                $removeAccounts = $this->form->get('removeAccounts')->getData();
                $this->onSuccess($entity, $appendAccounts, $removeAccounts);

                return true;
            }
        }

        return false;
    }

    /**
     * "Success" form handler
     *
     * @param Contact $entity
     * @param array $appendAccounts
     * @param array $removeAccounts
     */
    protected function onSuccess(Contact $entity, array $appendAccounts, array $removeAccounts)
    {
        $this->appendAccounts($entity, $appendAccounts);
        $this->removeAccounts($entity, $removeAccounts);
        $this->manager->persist($entity);
        $this->manager->flush();
    }

    /**
     * Append contacts to account
     *
     * @param Contact $contact
     * @param Account[] $accounts
     */
    protected function appendAccounts(Contact $contact, array $accounts)
    {
        foreach ($accounts as $account) {
            $contact->addAccount($account);
        }
    }

    /**
     * Remove contacts from account
     *
     * @param Contact $contact
     * @param Account[] $accounts
     */
    protected function removeAccounts(Contact $contact, array $accounts)
    {
        foreach ($accounts as $account) {
            $contact->removeAccount($account);
        }
    }
}