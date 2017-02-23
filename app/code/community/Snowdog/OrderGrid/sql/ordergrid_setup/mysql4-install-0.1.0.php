<?php
$installer = $this;

$installer->addAttribute('catalog_product', 'product_sell_type', array(
    'group' => 'General',
    'type' => Varien_Db_Ddl_Table::TYPE_VARCHAR,
    'backend' => '',
    'frontend' => '',
    'label' => 'Product Type',
    'input' => 'select',
    'class' => '',
    'source' => '',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible' => true,
    'required' => false,
    'user_defined' => true,
    'default' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => false,
    'unique' => false,
    'apply_to' => 'simple,configurable,virtual',
    'is_configurable' => false,
    'option' =>
        array(
            'values' =>
                array(
                    12 => 'Retail',
                    13 => 'Backbar',
                ),
        ),
));

$installer = new Mage_Sales_Model_Resource_Setup('core_setup');
/**
 * Add 'custom_attribute' attribute for entities
 */
$entities = array(
    'quote_item',
    'order_item'
);
$options = array(
    'type' => Varien_Db_Ddl_Table::TYPE_VARCHAR,
    'visible' => true,
    'required' => false
);
foreach ($entities as $entity) {
    $installer->addAttribute($entity, 'product_sell_type', $options);
}
$installer->endSetup();