<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Newsletter
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * FOREIGN KEY update
 *
 * @category   Mage
 * @package    Mage_Newsletter
 * @author     Magento Core Team <core@magentocommerce.com>
 */

$installer = $this;
/* @var $installer Mage_Tax_Model_Mysql4_Setup */

$installer->startSetup();

$table = $installer->getTable('newsletter_queue_link');

$installer->getConnection()->addKey($table, 'IDX_NEWSLETTER_QUEUE_LINK_SEND_AT', array('queue_id', 'letter_sent_at'));

$installer->endSetup();
