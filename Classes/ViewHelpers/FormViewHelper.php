<?php
namespace UpAssist\FormEnhancers\ViewHelpers;

/*                                                                        *
 * This script belongs to the Neos Flow package "Neos.Form".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The Neos project - inspiring people to share!                         *
 *                                                                        */

use Neos\FluidAdaptor\Core\ViewHelper\Exception;

/**
 * @author support@simplyadmire.com
 */
class FormViewHelper extends \Neos\Form\ViewHelpers\FormViewHelper {

	/**
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerTagAttribute('target', 'string', 'Specifies where to display the response that is received after submitting the form');
		parent::initializeArguments();
	}

	/**
	 * Returns the action URI of the form tag.
	 * If the argument "actionUri" is specified, this will be returned
	 * Otherwise this creates the action URI using the UriBuilder
	 *
	 * @return string
	 * @throws Exception if the action URI could not be created
	 */
	protected function getFormActionUri() {
		if ($this->formActionUri !== NULL) {
			return $this->formActionUri;
		}
		if ($this->hasArgument('actionUri')) {
			$this->formActionUri = $this->arguments['actionUri'];
		} else {
			$uriBuilder = $this->controllerContext->getUriBuilder();
			$request = $this->controllerContext->getRequest()->getHttpRequest()->createActionRequest();
			$uriBuilder->setRequest($request);

			$uriBuilder
				->reset()
				->setSection($this->arguments['section'])
				->setCreateAbsoluteUri($this->arguments['absolute'])
				->setAddQueryString($this->arguments['addQueryString'])
				->setFormat($this->arguments['format']);
			if (is_array($this->arguments['additionalParams'])) {
				$uriBuilder->setArguments($this->arguments['additionalParams']);
			}
			if (is_array($this->arguments['argumentsToBeExcludedFromQueryString'])) {
				$uriBuilder->setArgumentsToBeExcludedFromQueryString($this->arguments['argumentsToBeExcludedFromQueryString']);
			}
			try {
				$this->formActionUri = $uriBuilder
					->uriFor($this->arguments['action'], $this->arguments['arguments'], $this->arguments['controller'], $this->arguments['package'], $this->arguments['subpackage']);
			} catch (\Exception $exception) {
				throw new Exception($exception->getMessage(), $exception->getCode(), $exception);
			}
		}
		return $this->formActionUri;
	}

	/**
	 * Retrieves the default field name prefix for this form
	 *
	 * @return string default field name prefix
	 */
	protected function getDefaultFieldNamePrefix() {
		return '';
	}

}