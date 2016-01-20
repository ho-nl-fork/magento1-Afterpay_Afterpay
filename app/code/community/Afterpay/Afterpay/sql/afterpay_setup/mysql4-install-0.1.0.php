<?php 
/**
 * Copyright (c) 2011-2015  arvato Finance B.V.
 *
 * AfterPay reserves all rights in the Program as delivered. The Program
 * or any portion thereof may not be reproduced in any form whatsoever without
 * the written consent of AfterPay.
 *
 * Disclaimer:
 * THIS NOTICE MAY NOT BE REMOVED FROM THE PROGRAM BY ANY USER THEREOF.
 * THE PROGRAM IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE PROGRAM OR THE USE OR OTHER DEALINGS
 * IN THE PROGRAM.
 *
 * @category    AfterPay
 * @package     Afterpay_Afterpay
 * @copyright   Copyright (c) 2011-2015 arvato Finance B.V.
 */
 
 $installer = $this;

$installer->startSetup();
$conn = $installer->getConnection();

/**
 * Add AfterPay columns
 */
$conn->addColumn(
    $installer->getTable('sales/order'),
    'afterpay_transaction_id',
    "varchar(32) null"
);
$conn->addColumn(
    $installer->getTable('sales/order'),
    'afterpay_order_reference',
    "varchar(255) null"
);
$conn->addColumn(
    $installer->getTable('sales/order'),
    'afterpay_capture_mode',
    "int(1) unsigned null"
);
$conn->addColumn(
    $installer->getTable('sales/order'),
    'afterpay_captured',
    "int(1) unsigned null"
);

/**
 * Add PaymentFee columns to sales/order
 */
$conn->addColumn(
    $installer->getTable('sales/order'),
    'payment_fee',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/order'),
    'base_payment_fee',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/order'),
    'payment_fee_invoiced',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/order'),
    'base_payment_fee_invoiced',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/order'),
    'payment_fee_tax',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/order'),
    'base_payment_fee_tax',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/order'),
    'payment_fee_tax_invoiced',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/order'),
    'base_payment_fee_tax_invoiced',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/order'),
    'payment_fee_refunded',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/order'),
    'base_payment_fee_refunded',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/order'),
    'payment_fee_tax_refunded',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/order'),
    'base_payment_fee_tax_refunded',
    "decimal(12,4) null"
);

/**
 * Add PaymentFee columns to sales/order_invoice
 */
$conn->addColumn(
    $installer->getTable('sales/invoice'),
    'payment_fee',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/invoice'),
    'base_payment_fee',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/invoice'),
    'payment_fee_tax',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/invoice'),
    'base_payment_fee_tax',
    "decimal(12,4) null"
);

/**
 * Add PaymentFee columns to sales/quote
 */
$conn->addColumn(
    $installer->getTable('sales/quote'),
    'payment_fee',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/quote'),
    'base_payment_fee',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/quote'),
    'payment_fee_tax',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/quote'),
    'base_payment_fee_tax',
    "decimal(12,4) null"
);

/**
 * Add PaymentFee columns to sales/quote_address
 */
$conn->addColumn(
    $installer->getTable('sales/quote_address'),
    'payment_fee',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/quote_address'),
    'base_payment_fee',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/quote_address'),
    'payment_fee_tax',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/quote_address'),
    'base_payment_fee_tax',
    "decimal(12,4) null"
);

/**
 * Add PaymentFee columns to sales/order_creditmemo
 */
$conn->addColumn(
    $installer->getTable('sales/creditmemo'),
    'payment_fee',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/creditmemo'),
    'base_payment_fee',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/creditmemo'),
    'payment_fee_tax',
    "decimal(12,4) null"
);
$conn->addColumn(
    $installer->getTable('sales/creditmemo'),
    'base_payment_fee_tax',
    "decimal(12,4) null"
);

$installer->endSetup();
