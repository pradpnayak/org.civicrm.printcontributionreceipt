<?php
class CRM_PrintContributionReceipt_Utils {

  /**
   * Process and Download Contribution receipt.
   *
   */
  public static function downloadReceipt() {
    $contributionId = CRM_Utils_Request::retrieve(
      'id',
      'Positive',
      CRM_Core_DAO::$_nullObject,
      FALSE
    );
    self::printPDF($contributionId);
  }

  /**
   * Print Contribution receipt.
   *
   * @param int $contributionId
   *
   */
  public static function printPDF($contributionId) {
    $params = ['output' => 'pdf_receipt'];
    $message = [];
    $template = CRM_Core_Smarty::singleton();

    $contactId = civicrm_api3('Contribution', 'getvalue', [
      'return' => "contact_id",
      'id' => $contributionId,
    ]);

    $elements = CRM_Contribute_Form_Task_PDF::getElements(
      [$contributionId],
      $params,
      [$contactId]
    );

    foreach ($elements['details'] as $contribID => $detail) {
      $input = $ids = $objects = [];

      if (in_array($detail['contact'], $elements['excludeContactIds'])) {
        continue;
      }

      $input['component'] = $detail['component'];

      $ids['contact'] = $detail['contact'];
      $ids['contribution'] = $contribID;
      $ids['contributionRecur'] = NULL;
      $ids['contributionPage'] = NULL;
      $ids['membership'] = CRM_Utils_Array::value('membership', $detail);
      $ids['participant'] = CRM_Utils_Array::value('participant', $detail);
      $ids['event'] = CRM_Utils_Array::value('event', $detail);

      if (!$elements['baseIPN']->validateData($input, $ids, $objects, FALSE)) {
        CRM_Core_Error::fatal();
      }

      $contribution = &$objects['contribution'];

      // set some fake input values so we can reuse IPN code
      $input['amount'] = $contribution->total_amount;
      $input['is_test'] = $contribution->is_test;
      $input['fee_amount'] = $contribution->fee_amount;
      $input['net_amount'] = $contribution->net_amount;
      $input['invoice_number'] = $contribution->invoice_number;
      $input['trxn_id'] = $contribution->trxn_id;
      $input['trxn_date'] = isset($contribution->trxn_date) ? $contribution->trxn_date : NULL;
      $input['contribution_status_id'] = $contribution->contribution_status_id;
      $input['paymentProcessor'] = empty($contribution->trxn_id) ? NULL :
        CRM_Core_DAO::singleValueQuery("SELECT payment_processor_id
          FROM civicrm_financial_trxn
          WHERE trxn_id = %1
          LIMIT 1", array(
            1 => array($contribution->trxn_id, 'String')));

      // CRM_Contribute_BAO_Contribution::composeMessageArray expects mysql
      // formatted date
      $objects['contribution']->receive_date = CRM_Utils_Date::isoToMysql(
        $objects['contribution']->receive_date
      );


      try {
        $paymentInfo = CRM_Contribute_BAO_Contribution::getPaymentInfo($contribID, 'Contribution', TRUE);
        $payments = [];
        foreach ($paymentInfo['transaction'] as $transactions) {
          $payments[] = $transactions;
        }
      }
      catch (Exception $e) {
        $payments = NULL;
      }

      $template->assign('trxnPaymentsList', $payments);
      $template->assign('invoiceNumber', $contribution->invoice_number);
      $values = [];

      $mail = CRM_Contribute_BAO_Contribution::sendMail(
        $input,
        $ids,
        $objects['contribution']->id,
        $values,
        $elements['createPdf']
      );

      if ($mail['html']) {
        $message[] = $mail['html'];
      }
      else {
        $message[] = nl2br($mail['body']);
      }

      // reset template values before processing next transactions
      $template->clearTemplateVars();
    }
    $fileName = "Contribution_Receipt_{$contribution->id}.pdf";
    $message = preg_replace('/(<html)(.+?xmlns=["\'].[^\s]+["\'])(.+)?(>)/', '\1\3\4', $message);
    CRM_Utils_PDF_Utils::html2pdf($message,
      $fileName,
      FALSE,
      NULL
    );
    CRM_Utils_System::civiExit();
  }

}
