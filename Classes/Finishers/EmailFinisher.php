<?php
namespace UpAssist\FormEnhancers\Finishers;

class EmailFinisher extends \Neos\Form\Finishers\EmailFinisher {

    /**
     * Extends the functionality of the default parseOption() method
     * by resolving values starting with fieldIdentifier: in the posted
     * form (if the value is available).
     *
     * @param string $optionName
     * @return mixed|string
     * @throws \Neos\Flow\Mvc\Exception\NoSuchArgumentException
     */
    protected function parseOption($optionName) {
        $value = parent::parseOption($optionName);

        if (!is_array($value) && substr($value, 0, 16) === 'fieldIdentifier:') {
            $formRuntime = $this->finisherContext->getFormRuntime();
            $field = str_replace('fieldIdentifier:', '', $value);
            if ($formRuntime->getRequest()->hasArgument($field)) {
                return $formRuntime->getRequest()->getArgument($field);
            }
        }

        return $value;
    }
}
