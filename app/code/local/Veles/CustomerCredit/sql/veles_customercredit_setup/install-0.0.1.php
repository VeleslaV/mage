<?php
    $installer = $this;

    $installer->startSetup();

    $installer->run("
        ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `costumer_credit_amount` DECIMAL( 10, 2 ) NOT NULL;
        ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `costumer_credit_amount_refunded` DECIMAL( 10, 2 ) NOT NULL;
        ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `base_costumer_credit_amount` DECIMAL( 10, 2 ) NOT NULL;
        ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `base_costumer_credit_amount_refunded` DECIMAL( 10, 2 ) NOT NULL;
        ALTER TABLE  `".$this->getTable('sales/quote_address')."` ADD  `costumer_credit_amount` DECIMAL( 10, 2 ) NOT NULL;
        ALTER TABLE  `".$this->getTable('sales/quote_address')."` ADD  `base_costumer_credit_amount` DECIMAL( 10, 2 ) NOT NULL;
        ALTER TABLE  `".$this->getTable('sales/invoice')."` ADD  `costumer_credit_amount` DECIMAL( 10, 2 ) NOT NULL;
        ALTER TABLE  `".$this->getTable('sales/invoice')."` ADD  `base_costumer_credit_amount` DECIMAL( 10, 2 ) NOT NULL;
        ALTER TABLE  `".$this->getTable('sales/creditmemo')."` ADD  `costumer_credit_amount` DECIMAL( 10, 2 ) NOT NULL;
        ALTER TABLE  `".$this->getTable('sales/creditmemo')."` ADD  `base_costumer_credit_amount` DECIMAL( 10, 2 ) NOT NULL;
    ");

    $installer->endSetup();