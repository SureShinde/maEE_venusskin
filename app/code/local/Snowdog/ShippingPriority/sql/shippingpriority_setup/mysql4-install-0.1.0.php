<?php
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('shipping_matrixrate')} ADD `priority` varchar(32)
");

$installer->addAttribute("quote", "shipping_priority", array("type" => "varchar"));
$installer->addAttribute("order", "shipping_priority", array("type" => "varchar"));
$installer->endSetup();