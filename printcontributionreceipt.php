<?php

require_once 'printcontributionreceipt.civix.php';
use CRM_Printcontributionreceipt_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function printcontributionreceipt_civicrm_config(&$config) {
  _printcontributionreceipt_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function printcontributionreceipt_civicrm_xmlMenu(&$files) {
  _printcontributionreceipt_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function printcontributionreceipt_civicrm_install() {
  _printcontributionreceipt_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function printcontributionreceipt_civicrm_postInstall() {
  _printcontributionreceipt_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function printcontributionreceipt_civicrm_uninstall() {
  _printcontributionreceipt_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function printcontributionreceipt_civicrm_enable() {
  _printcontributionreceipt_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function printcontributionreceipt_civicrm_disable() {
  _printcontributionreceipt_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function printcontributionreceipt_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _printcontributionreceipt_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function printcontributionreceipt_civicrm_managed(&$entities) {
  _printcontributionreceipt_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function printcontributionreceipt_civicrm_caseTypes(&$caseTypes) {
  _printcontributionreceipt_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function printcontributionreceipt_civicrm_angularModules(&$angularModules) {
  _printcontributionreceipt_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function printcontributionreceipt_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _printcontributionreceipt_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function printcontributionreceipt_civicrm_entityTypes(&$entityTypes) {
  _printcontributionreceipt_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_links().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_links
 */
function printcontributionreceipt_civicrm_links($op, $objectName, $objectId, &$links, &$mask, &$values) {
  if ('contribution.selector.row' == $op
    && $objectName == 'Contribution'
  ) {
    $links[] = [
      'name' => 'Download Receipt',
      'url' => 'civicrm/contribution/downloadreceipt',
      'qs' => 'reset=1&id=%%id%%',
      'title' => ts('Download Receipt'),
      'ref' => 'no-popup',
    ];
  }
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */
function printcontributionreceipt_civicrm_buildForm($formName, &$form) {
  if ('CRM_Contribute_Form_ContributionView' == $formName) {
    CRM_Core_Region::instance('page-body')->add([
      'template' => 'CRM/PrintContributionReceipt/Form/ContributionViewExtra.tpl',
    ]);
  }
}
