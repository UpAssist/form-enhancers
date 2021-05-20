<?php

namespace UpAssist\FormEnhancers\DataSource;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Form\Persistence\FormPersistenceManagerInterface;
use Neos\Neos\Service\DataSource\AbstractDataSource;

class FormsDataSource extends AbstractDataSource
{

    /**
     * @Flow\Inject
     * @var FormPersistenceManagerInterface
     */
    protected $formPersistenceManager;

    /**
     * @var string
     */
    static protected $identifier = 'upassist-formenhancers';

    /**
     * Get data
     *
     * @param NodeInterface $node The node that is currently edited (optional)
     * @param array $arguments Additional arguments (key / value)
     * @return array JSON serializable data
     */
    public function getData(NodeInterface $node = NULL, array $arguments = [])
    {
        $forms = $this->formPersistenceManager->listForms();
        $formIdentifiers = array();

        foreach ($forms as $key => $value) {
            $formIdentifiers[$key] = array(
                'label' => $value['name'],
                'value' => $key
            );
        }

        return $formIdentifiers;
    }

}
