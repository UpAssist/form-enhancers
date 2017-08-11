<?php
namespace UpAssist\FormEnhancers\Domain\Model;

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
/**
 * @Flow\Entity
 */
class FormEntry {
    /**
     * @var string The formIdentifier as determined in the yaml
     */
   protected $formIdentifier;

    /**
     * @var \DateTime The creation date time of the entry
     */
   protected $creationDateTime;

    /**
     * @ORM\Column(type="flow_json_array")
     * @var array<mixed> The formValues
     */
   protected $formValues;

    /**
     * FormEntry constructor.
     */
    public function __construct()
   {
       $this->creationDateTime = new \DateTime();
   }

    /**
     * @return string
     */
    public function getFormIdentifier()
    {
        return $this->formIdentifier;
    }

    /**
     * @param string $formIdentifier
     * @return FormEntry
     */
    public function setFormIdentifier($formIdentifier)
    {
        $this->formIdentifier = $formIdentifier;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDateTime()
    {
        return $this->creationDateTime;
    }

    /**
     * @param \DateTime $creationDateTime
     * @return FormEntry
     */
    public function setCreationDateTime($creationDateTime)
    {
        $this->creationDateTime = $creationDateTime;
        return $this;
    }

    /**
     * @return array
     */
    public function getFormValues()
    {
        return $this->formValues;
    }

    /**
     * @param array $formValues
     * @return FormEntry
     */
    public function setFormValues($formValues)
    {
        $this->formValues = $formValues;
        return $this;
    }

}