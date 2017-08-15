<?php
/**
 * Copyright (c) 2017. UpAssist
 * For more information http://www.upassist.com
 */

namespace UpAssist\FormEnhancers\Finishers;

use Neos\Flow\Annotations as Flow;
use Neos\Form\Core\Model\AbstractFinisher;
use UpAssist\FormEnhancers\Domain\Model\FormEntry;
use UpAssist\FormEnhancers\Domain\Repository\FormEntryRepository;

/**
 * Class FormEntryFinisher
 * @package UpAssist\FormEnhancers\Finishers
 */
class FormEntryFinisher extends AbstractFinisher
{

    /**
     * @Flow\Inject
     * @var FormEntryRepository
     */
    protected $formEntryRepository;

    /**
     *
     */
    protected function executeInternal()
    {
        $formRuntime = $this->finisherContext->getFormRuntime();
        $formValues = $formRuntime->getFormState()->getFormValues();

        $entry = new FormEntry();
        $entry->setFormIdentifier($formRuntime->getIdentifier());
        $entry->setFormLabel($this->parseOption('label'));
        $entry->setFormValues($formValues);

        $this->formEntryRepository->add($entry);
        $this->formEntryRepository->persistAll();
    }
}