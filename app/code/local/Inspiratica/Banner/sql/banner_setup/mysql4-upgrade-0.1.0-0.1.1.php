<?php
$installer = $this;

$installer->startSetup();

$installer->run("ALTER TABLE {$this->getTable('banner')} ADD COLUMN `store_ids` VARCHAR(255) NOT NULL DEFAULT '0' AFTER `blocks`;");

$installer->endSetup();
