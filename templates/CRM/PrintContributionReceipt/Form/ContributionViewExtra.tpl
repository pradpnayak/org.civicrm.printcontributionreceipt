<a class="button no-popup download_receipt_link"
  href='{crmURL p="civicrm/contribution/downloadreceipt" q="reset=1&id=`$contribution_id`"}'>
  <i class="crm-i fa-print"></i>
  {ts}Download Receipt{/ts}
</a>
{literal}
<script type="text/javascript">
  CRM.$(function($) {
    $('div.crm-submit-buttons').append($('a.download_receipt_link'));
  });
</script>
{/literal}
