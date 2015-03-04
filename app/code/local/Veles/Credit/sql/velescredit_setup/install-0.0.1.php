<?php
    $installer = $this;

    $installer->startSetup();

    $installer->run("
        ALTER TABLE `".$this->getTable('sales/order')."` ADD `credit_amount` DECIMAL( 10, 2 ) NOT NULL;
        ALTER TABLE `".$this->getTable('sales/order')."` ADD `base_credit_amount` DECIMAL( 10, 2 ) NOT NULL;

        ALTER TABLE `".$this->getTable('sales/order')."` ADD `credit_amount_granted` DECIMAL( 10, 2 ) NOT NULL;
        ALTER TABLE `".$this->getTable('sales/order')."` ADD `base_credit_amount_granted` DECIMAL( 10, 2 ) NOT NULL;

        ALTER TABLE `".$this->getTable('sales/order')."` ADD `credit_amount_invoiced` DECIMAL( 10, 2 ) NOT NULL;
        ALTER TABLE `".$this->getTable('sales/order')."` ADD `base_credit_amount_invoiced` DECIMAL( 10, 2 ) NOT NULL;

        ALTER TABLE `".$this->getTable('sales/order')."` ADD `credit_amount_refunded` DECIMAL( 10, 2 ) NOT NULL;
        ALTER TABLE `".$this->getTable('sales/order')."` ADD `base_credit_amount_refunded` DECIMAL( 10, 2 ) NOT NULL;

        ALTER TABLE `".$this->getTable('sales/quote')."` ADD `credit_amount` DECIMAL( 10, 2 ) NOT NULL;
        ALTER TABLE `".$this->getTable('sales/quote')."` ADD `base_credit_amount` DECIMAL( 10, 2 ) NOT NULL;

        ALTER TABLE `".$this->getTable('sales/quote_address')."` ADD `credit_amount` DECIMAL( 10, 2 ) NOT NULL;
        ALTER TABLE `".$this->getTable('sales/quote_address')."` ADD `base_credit_amount` DECIMAL( 10, 2 ) NOT NULL;

        ALTER TABLE `".$this->getTable('sales/invoice')."` ADD `credit_amount` DECIMAL( 10, 2 ) NOT NULL;
        ALTER TABLE `".$this->getTable('sales/invoice')."` ADD `base_credit_amount` DECIMAL( 10, 2 ) NOT NULL;

        ALTER TABLE `".$this->getTable('sales/creditmemo')."` ADD `credit_amount` DECIMAL( 10, 2 ) NOT NULL;
        ALTER TABLE `".$this->getTable('sales/creditmemo')."` ADD `base_credit_amount` DECIMAL( 10, 2 ) NOT NULL;
    ");

    $installer->endSetup();