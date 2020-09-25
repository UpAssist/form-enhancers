<?php
/**
 * Copyright (c) 2017. UpAssist
 * For more information http://www.upassist.com
 */

namespace UpAssist\FormEnhancers\Domain\Repository;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Repository;
use UpAssist\FormEnhancers\Domain\Model\FormEntry;

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
   * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
   */
  public function removeAllByFormIdentifier($formIdentifier)
  {
    foreach ($this->findByFormIdentifier($formIdentifier) as $object) {
      $this->remove($object);
    }
  }

  /**
   * Returns an array containing distinct form identifiers
   *
   * @return array
   */
  public function findDistinctIdentifiers()
  {
    $query = $this->createQuery();
    $results = $query->execute()->toArray();
    $identifiers = [];

    /** @var FormEntry $result */
    foreach ($results as $result) {
      $identifiers[] = $result->getFormIdentifier();
    }

    return array_unique($identifiers);
  }
}
