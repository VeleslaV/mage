<?php
    $installer = $this;

    /* Module table initialization */
    $discountsTable = $installer->getTable('veles_discounts/veles_customers_discounts_table');

    /* Customer discounts table setup */
    $installer->startSetup();
    $installer->getConnection()->dropTable($discountsTable);
    $dTable = $installer->getConnection()
        ->newTable($discountsTable)
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ))
        ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'comment'   => 'Customer Id',
            'nullable'  => false,
        ))
        ->addColumn('discount_level', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'comment'   => 'Costumer Discount Level',
            'default'  => '0',
            'nullable'  => true,
        ))
        ->addColumn('customer_orders_quantity', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'comment'   => 'Customer Orders Quantity',
            'default'  => '0',
            'nullable'  => true,
        ))
        ->addColumn('customer_orders_value', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'comment'   => 'Customer Orders Total Value',
            'default'  => '0',
            'nullable'  => true,
        ))
        ->addColumn('customer_discount_coupon', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
            'comment'   => 'Costumer Discount Coupon',
            'nullable'  => true,
        ));
    $installer->getConnection()->createTable($dTable);

    /* Customer discounts table fixtures */
    $newData = Mage::getModel('veles_discounts/discount');
    $newData->setCustomerId(140);
    $newData->setDiscountLevel(3);
    $newData->setCustomerOrdersQuantity(4);
    $newData->setCustomerOrdersValue(222);
    $newData->setCustomerDiscountCoupon("F98SDF7S9D8F");
    $newData->save();

    $installer->endSetup();