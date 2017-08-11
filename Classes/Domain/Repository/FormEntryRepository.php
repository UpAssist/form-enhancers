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
     * Persist all
     */
    public function persistAll()
    {
        $this->persistenceManager->persistAll();
    }
}
