<?php
/**
 * Copyright (c) 2017. UpAssist
 * For more information http://www.upassist.com
 */

namespace UpAssist\FormEnhancers\Domain\Repository;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class FormEntryRepository extends Repository
{
    /**
     * Persist all
     * @return void
     */
    public function persistAll()
    {
        $this->persistenceManager->persistAll();
    }

    /**
     * @param string $formIdentifier
     * @return void
     */
    public function removeAllByFormIdentifier($formIdentifier)
    {
        foreach ($this->findByFormIdentifier($formIdentifier) as $object) {
            $this->remove($object);
        }
    }
}
