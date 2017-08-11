<?php
/**
 * Copyright (c) 2017. UpAssist
 * For more information http://www.upassist.com
 */

namespace UpAssist\FormEnhancers\Domain\Repository;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class FormEntryRepository extends Repository
{
    /**
     * @return object
     */
    public function getFirst() {
        $query = $this->createQuery();
        return $query->execute()->getFirst();
    }

    /**
     * Persist all
     */
    public function persistAll()
    {
        $this->persistenceManager->persistAll();
    }
}
