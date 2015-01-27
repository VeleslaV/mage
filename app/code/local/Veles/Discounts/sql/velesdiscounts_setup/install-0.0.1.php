<?php
    $installer = $this;

    /* Module table initialization */
    $discountsTable = $installer->getTable('veles_discounts/veles_customers_discounts_table');
    $levelsTable = $installer->getTable('veles_discounts/veles_discounts_levels_table');

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
        ->addColumn('customer_discount_coupon', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
            'comment'   => 'Costumer Discount Coupon',
            'nullable'  => true,
        ));
    $installer->getConnection()->createTable($dTable);

    /* Customer discounts table fixtures */
    $newData = Mage::getModel('veles_discounts/discount');
    $newData->setCustomerId(140);
    $newData->setDiscountLevel(3);
    $newData->setCustomerDiscountCoupon("F98SDF7S9D8F");
    $newData->save();

    /* Discounts levels table setup */
    $installer->startSetup();
    $installer->getConnection()->dropTable($levelsTable);
    $lTable = $installer->getConnection()
        ->newTable($levelsTable)
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ))
        ->addColumn('level', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'comment'   => 'Level',
            'nullable'  => false,
        ));
    $installer->getConnection()->createTable($lTable);

    $installer->endSetup();