<?php
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('shipping_matrixrate')} ADD `hide_in_front` varchar(32)
");

$installer->endSetup();