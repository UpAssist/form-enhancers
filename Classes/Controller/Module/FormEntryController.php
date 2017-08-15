<?php
/**
 * Copyright (c) 2017. UpAssist
 * For more information http://www.upassist.com
 */

namespace UpAssist\FormEnhancers\Controller\Module;

use Neos\Error\Messages\Message;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\QueryInterface;
use Neos\Neos\Controller\Module\AbstractModuleController;
use Neos\Utility\Files;
use Neos\Utility\ObjectAccess;
use UpAssist\FormEnhancers\Domain\Model\FormEntry;
use UpAssist\FormEnhancers\Domain\Repository\FormEntryRepository;

class FormEntryController extends AbstractModuleController
{

    /**
     * @Flow\Inject
     * @var FormEntryRepository
     */
    protected $formEntryRepository;

    /**
     * @Flow\InjectConfiguration(path="formEntriesFinisher.forms", package="UpAssist.FormEnhancers")
     * @var array
     */
    protected $formIdentifiers;

    /**
     * @return  void
     */
    public function indexAction()
    {
        $this->formEntryRepository->setDefaultOrderings(['creationDateTime' => QueryInterface::ORDER_DESCENDING]);
        $forms = [];
        // if formIdentifiers are defined, loop over them and assign them
        if ($this->formIdentifiers) {
            foreach ($this->formIdentifiers as $identifier) {
                $entries = $this->formEntryRepository->findByFormIdentifier($identifier);
                $entry = $entries[0];
                $entryColumns = [];

                if ($entry instanceof FormEntry) {
                    foreach ($entry->getFormValues() as $key => $value) {
                        $entryColumns[] = $key;
                    }
                    $forms[] = [
                        'formIdentifier' => $identifier,
                        'label' => $entry->getFormLabel(),
                        'columns' => $entryColumns,
                        'entries' => $entries
                    ];
                }

            }
        }

        $this->view->assign('forms', $forms);
    }

    /**
     * @param FormEntry $formEntry
     * @return void
     */
    public function deleteAction(FormEntry $formEntry) {
        $this->formEntryRepository->remove($formEntry);
        $this->formEntryRepository->persistAll();
        $message = new Message('The entry is successfully removed');
        $this->flashMessageContainer->addMessage($message);
        $this->redirect('index');
    }

    /**
     * @param string $formIdentifier
     * @return  void
     */
    public function deleteAllAction($formIdentifier = null) {
        if ($formIdentifier) {
            $this->formEntryRepository->removeAllByFormIdentifier($formIdentifier);
        } else {
            $this->formEntryRepository->removeAll();
        }
        $this->formEntryRepository->persistAll();
        $message = new Message('All entries successfully removed');
        $this->flashMessageContainer->addMessage($message);
        $this->redirect('index');
    }

    /**
     * @param string $formIdentifier
     * @return void
     */
    public function exportAction($formIdentifier = null)
    {
        require_once(Files::concatenatePaths([FLOW_PATH_PACKAGES, 'Libraries', 'os', 'php-excel', 'PHPExcel', 'PHPExcel.php']));

        $this->formEntryRepository->setDefaultOrderings(['creationDateTime' => QueryInterface::ORDER_DESCENDING]);

        $entries = $formIdentifier ? $this->formEntryRepository->findByFormIdentifier($formIdentifier) : $this->formEntryRepository->findAll();
        $columns = [];

        if ($entries[0] instanceof FormEntry) {
            foreach ($entries[0]->getFormValues() as $key => $value) {
                $columns[] =[ucfirst($key), $key];
            }
        }

        array_push($columns, ['Created','creationDateTime']);

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

        $rowCounter = 1;
        foreach ($columns as $column => $config) {
            $objPHPExcel->getActiveSheet()->setCellValue(chr(65 + $column) . $rowCounter, $config[0]);
			$objPHPExcel->getActiveSheet()->getColumnDimension(\PHPExcel_Cell::stringFromColumnIndex($column))->setAutoSize(TRUE);
        }


        $rowCounter = 2;

        foreach ($entries as $formEntry) {
            foreach ($columns as $column => $config) {
                $cellValue = ObjectAccess::getPropertyPath($formEntry->getFormValues(), $config[1]) ? ObjectAccess::getPropertyPath($formEntry->getFormValues(), $config[1]) : ObjectAccess::getPropertyPath($formEntry, $config[1]);

                if ($cellValue instanceof \DateTime) {
                    $cellValue = $cellValue->format('d-m-Y H:i');
                }

                $objPHPExcel->getActiveSheet()->setCellValue(
                    chr(65 + $column) . $rowCounter,
                    $cellValue
                );

                if (is_numeric($cellValue)) {
                    $objPHPExcel->getActiveSheet()->getCell(chr(65 + $column) . $rowCounter)->setDataType(\PHPExcel_Cell_DataType::TYPE_NUMERIC);
                } else {
                    $objPHPExcel->getActiveSheet()->getCell(chr(65 + $column) . $rowCounter)->setDataType(\PHPExcel_Cell_DataType::TYPE_STRING);
                }
            }
            $rowCounter++;
        }

        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle(substr($entries[0]->getFormLabel(), 0, 31));

        $fileName = $formIdentifier ? $formIdentifier : 'form_entries';
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit();
    }

}