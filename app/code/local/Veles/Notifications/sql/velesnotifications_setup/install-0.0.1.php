<?php
    $installer = $this;

    /** Module tables initialization **/
    $eventsTable = $installer->getTable('veles_notifications/veles_notifications_events_table');
    $rulesTable = $installer->getTable('veles_notifications/veles_notifications_rules_table');
    $queueTable = $installer->getTable('veles_notifications/veles_notifications_queue_table');

    /** Notification rules table setup **/
    $installer->startSetup();

    /* <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Events >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> */

    /**
     * Events table
     */
        $installer->getConnection()->dropTable($eventsTable);
        $eTable = $installer->getConnection()
            ->newTable($eventsTable)
            ->addColumn('event_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                'identity'  => true,
                'nullable'  => false,
                'primary'   => true
            ))
            ->addColumn('event_title', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
                'comment'   => 'Event Title',
                'nullable'  => false
            ))
            ->addColumn('event_name', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
                'comment'   => 'Event Name',
                'nullable'  => false
            ))
            ->addColumn('event_status', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                'comment'   => 'Event Status',
                'default'  => '1',
                'nullable'  => false
            ));
        $installer->getConnection()->createTable($eTable);

    /**
     * Set events data
     */
        $events = array(
            array(
                'event_title' => 'Product Add To Cart After',
                'event_name' => 'checkout_cart_product_add_after',
                'event_status' => '1',
            ),
            array(
                'event_title' => 'Order Shipped After',
                'event_name' => 'sales_order_shipment_save_after',
                'event_status' => '1',
            )
        );

        foreach ($events as $event) {
            $eventModel = Mage::getModel('veles_notifications/event');
            $eventModel->setData($event);
            $eventModel->save();
        }

    /* <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Rules >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> */

    /**
     * Rules table
     **/
        $installer->getConnection()->dropTable($rulesTable);
        $rTable = $installer->getConnection()
            ->newTable($rulesTable)
            ->addColumn('rule_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                'identity'  => true,
                'nullable'  => false,
                'primary'   => true
            ))
            ->addColumn('rule_title', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
                'comment'   => 'Rule Title',
                'nullable'  => true
            ))
            ->addColumn('event_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                'comment'   => 'Event Id',
                'nullable'  => false
            ))
            ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                'comment'   => 'Store Id',
                'nullable'  => false
            ))
            ->addColumn('group_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                'comment'   => 'Customers Group Id',
                'nullable'  => false
            ))
            ->addColumn('sender_name', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
                'comment'   => 'Sender Name',
                'nullable'  => false
            ))
            ->addColumn('sender_email', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
                'comment'   => 'Sender Email',
                'nullable'  => false
            ))
            ->addColumn('email_template_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                'comment'   => 'Email Template Id',
                'nullable'  => false
            ))
            ->addColumn('send_at', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
                'comment'   => 'Send At',
                'nullable'  => false
            ))
            ->addColumn('rule_status', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                'comment'   => 'Rule Status',
                'default'  => '1',
                'nullable'  => false
            ));
        $installer->getConnection()->createTable($rTable);

    /* <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Queue >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> */

    /**
     * Queue table
     **/
        $installer->getConnection()->dropTable($queueTable);
        $qTable = $installer->getConnection()
            ->newTable($queueTable)
            ->addColumn('queue_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                'identity'  => true,
                'nullable'  => false,
                'primary'   => true
            ))
            ->addColumn('quote_or_order', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
                'comment'   => 'Quote Or Order',
                'nullable'  => false
            ))
            ->addColumn('rule_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                'comment'   => 'Rule Id',
                'nullable'  => false
            ))
            ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                'comment'   => 'Order Id',
                'nullable'  => false
            ))
            ->addColumn('quote_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                'comment'   => 'Quote Id',
                'nullable'  => false
            ))
            ->addColumn('customer_name', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
                'comment'   => 'Customer Name',
                'nullable'  => false
            ))
            ->addColumn('customer_email', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
                'comment'   => 'Customer Email',
                'nullable'  => false
            ))
            ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                'comment'   => 'Created At',
                'default' => null,
                'nullable' => true
            ))
            ->addColumn('scheduled_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                'comment'   => 'Scheduled At',
                'default' => null,
                'nullable' => true
            ))
            ->addColumn('queue_status', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                'comment'   => 'Queue Status',
                'default'  => '0',
                'nullable'  => false
            ));
        $installer->getConnection()->createTable($qTable);

    $installer->endSetup();