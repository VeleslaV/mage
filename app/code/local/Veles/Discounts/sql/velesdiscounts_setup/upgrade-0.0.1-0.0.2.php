<?php
    $installer = $this;
    $quoteTable = $installer->getTable('sales/quote');

    $installer->startSetup();

    $installer->getConnection()->addColumn($quoteTable, 'vd_coupon_code', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length' => '255',
        'nullable' => true,
        'default' => null,
        'comment' => 'VD Coupon Code'
    ));
    $installer->getConnection()->addColumn($quoteTable, 'vd_discount_percent', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'length' => '10,2',
        'nullable' => false,
        'comment' => 'VD Discount Percent'
    ));
    $installer->getConnection()->addColumn($quoteTable, 'vd_discount_amount', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'length' => '10,2',
        'nullable' => false,
        'comment' => 'VD Discount Amount'
    ));
    $installer->getConnection()->addColumn($quoteTable, 'base_vd_discount_amount', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'length' => '10,2',
        'nullable' => false,
        'comment' => 'Base VD Discount Amount'
    ));

    $installer->endSetup();