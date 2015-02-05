<?php
    $installer = $this;
    $quoteTable = $installer->getTable('sales/quote');
    $discountsTable = $installer->getTable('veles_discounts/veles_customers_discounts_table');

    $installer->startSetup();

    $installer->getConnection()->dropColumn($quoteTable, 'vd_coupon_code');
    $installer->getConnection()->dropColumn($quoteTable, 'vd_discount_percent');
    $installer->getConnection()->dropColumn($quoteTable, 'vd_discount_amount');
    $installer->getConnection()->dropColumn($quoteTable, 'base_vd_discount_amount');
    $installer->getConnection()->dropColumn($discountsTable, 'customer_discount_coupon');

    $installer->endSetup();