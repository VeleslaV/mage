<?php
    $installer = $this;
    $tableFAQ = $installer->getTable('velesfaq/table_faq');
    $tableCategories = $installer->getTable('velesfaq/table_faq_categories');
    $tableStatuses = $installer->getTable('velesfaq/table_faq_statuses');
    $tableCategoryQuestion = $installer->getTable('velesfaq/table_faq_category_question');

    $installer->startSetup();

    /* table_faq */
    $installer->getConnection()->dropTable($tableFAQ);
    $tableF = $installer->getConnection()
        ->newTable($tableFAQ)
        ->addColumn('question_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ))
        ->addColumn('user_email', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
            'comment'   => 'User Email',
            'nullable'  => false,
        ))
        ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
            'nullable'  => false,
        ))
        ->addColumn('question', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
            'nullable'  => false,
        ))
        ->addColumn('answer', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
            'nullable'  => false,
        ))
        ->addColumn('status', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'comment'   => 'Question Status',
            'default'  => '1',
            'nullable'  => false,
        ))
        ->addColumn('email_notification', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'comment'   => 'Email Notification',
            'default'  => '0',
            'nullable'  => false,
        ))
        ->addColumn('created', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
            'nullable'  => false,
        ));
    $installer->getConnection()->createTable($tableF);

    /* table_faq_categories */
    $installer->getConnection()->dropTable($tableCategories);
    $tableC = $installer->getConnection()
        ->newTable($tableCategories)
        ->addColumn('category_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ))
        ->addColumn('link', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
            'nullable'  => true,
        ))
        ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
            'nullable'  => false,
        ));
    $installer->getConnection()->createTable($tableC);

    /* table_faq_statuses */
    $installer->getConnection()->dropTable($tableStatuses);
    $tableS = $installer->getConnection()
        ->newTable($tableStatuses)
        ->addColumn('status_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ))
        ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
            'nullable'  => false,
        ));
    $installer->getConnection()->createTable($tableS);

    $newStatus1 = Mage::getModel('velesfaq/status');
    $newStatus1->setData('title', "New question");
    $newStatus1->save();

    $newStatus2 = Mage::getModel('velesfaq/status');
    $newStatus2->setData('title', "The question is answered");
    $newStatus2->save();

    $newStatus3 = Mage::getModel('velesfaq/status');
    $newStatus3->setData('title', "Approved as an important issue and handed the front-end");
    $newStatus3->save();

    $installer->endSetup();

    /* table_faq_category_question */
    $installer->getConnection()->dropTable($tableCategoryQuestion);
    $tableCQ = $installer->getConnection()
        ->newTable($tableCategoryQuestion)
        ->addColumn('cq_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ))
        ->addColumn('category_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'comment'   => 'Category Id',
            'nullable'  => false,
        ))
        ->addColumn('question_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'comment'   => 'Question Id',
            'nullable'  => false,
        ));
    $installer->getConnection()->createTable($tableCQ);

    $installer->endSetup();