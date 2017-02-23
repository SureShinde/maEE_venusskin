<?php
$installer = $this;
$installer->startSetup();
$sql = <<<SQLTEXT
create table snow_hideproduct(
id int not null auto_increment,
product_id int,
customer_group varchar(100),
primary key(id)
);

SQLTEXT;

$installer->run($sql);

$installer->addAttribute('catalog_product', 'snow_hide_group', array(
    'label' => 'Hide from Customer Groups',
    'group' => 'General',
    'type' => 'text',
    'input' => 'multiselect',
    'source' => 'Snowdog_HideProduct_Model_Entity_Attribute_Source_Customergroup_Withdefault',
    'backend' => 'Snowdog_HideProduct_Model_Entity_Attribute_Backend_Customergroups',
    'frontend' => '',
    'input_renderer' => 'snowdog_hideproduct/adminhtml_data_form_customergroup',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'required' => 0,
    'user_defined' => 0,
    'filterable_in_search' => 0,
    'is_configurable' => 0,
    'used_in_product_listing' => 1,
));



$installer->endSetup();
