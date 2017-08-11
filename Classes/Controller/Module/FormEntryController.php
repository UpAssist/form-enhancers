<?php
/**
 * Copyright (c) 2017. UpAssist
 * For more information http://www.upassist.com
 */

namespace UpAssist\FormEnhancers\Controller\Module;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Persistence\QueryInterface;
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\Flow\Utility\Files;
use TYPO3\Neos\Controller\Module\AbstractModuleController;
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
     * @return  void
     */
    public function indexAction()
    {
        $this->formEntryRepository->setDefaultOrderings(['creationDateTime' => QueryInterface::ORDER_DESCENDING]);
        $entries = $this->formEntryRepository->findAll();
        $entry = $this->formEntryRepository->getFirst();
        $entryLabels = [];

        if ($entry instanceof FormEntry) {
            foreach ($entry->getFormValues() as $key => $value) {
                $entryLabels[] = $key;
            }
        }

        $this->view->assign('labels', $entryLabels);
        $this->view->assign('entries', $entries);
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
     * @return void
     */
    public function deleteAllAction() {
        $this->formEntryRepository->removeAll();
        $this->formEntryRepository->persistAll();
        $message = new Message('All entries successfully removed');
        $this->flashMessageContainer->addMessage($message);
        $this->redirect('index');
    }

    /**
     * @return void
     */
    public function exportAction()
    {
        require_once(Files::concatenatePaths([FLOW_PATH_PACKAGES, 'Libraries', 'os', 'php-excel', 'PHPExcel', 'PHPExcel.php']));

        $singleEntry = $this->formEntryRepository->getFirst();
        $columns = [];

        if ($singleEntry instanceof FormEntry) {
            foreach ($singleEntry->getFormValues() as $key => $value) {
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

        $this->formEntryRepository->setDefaultOrderings(['creationDateTime' => QueryInterface::ORDER_DESCENDING]);
        foreach ($this->formEntryRepository->findAll() as $formEntry) {
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
        $objPHPExcel->getActiveSheet()->setTitle('Form entries');

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="form_entries.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit();
    }

}