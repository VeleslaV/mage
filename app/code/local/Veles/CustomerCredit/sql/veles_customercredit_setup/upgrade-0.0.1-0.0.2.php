<?php
    $installer = $this;
    $tableCustomerCredit = $installer->getTable('customercredit/table_customercredit');

    $installer->startSetup();
    $installer->getConnection()->dropTable($tableCustomerCredit);
    $tableCC = $installer->getConnection()
        ->newTable($tableCustomerCredit)
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ))
        ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'comment'   => 'Customer Id',
            'nullable'  => false,
        ))
        ->addColumn('credit_amount', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'comment'   => 'Costumer Credit Amount',
            'default'  => '0',
            'nullable'  => true,
        ));
    $installer->getConnection()->createTable($tableCC);

    $installer->endSetup();