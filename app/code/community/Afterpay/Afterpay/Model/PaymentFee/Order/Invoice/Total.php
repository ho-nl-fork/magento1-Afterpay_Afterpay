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
 
 class Afterpay_Afterpay_Model_PaymentFee_Order_Invoice_Total extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    /**
     * Retrieves Payment Fee values, calculates the amount that needs to be invoiced
     * 
     * @param Mage_Sales_Model_Order_Invoice $invoice
     */
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $order = $invoice->getOrder();
        
        //retrieve all base fee-related values from order
        $basePaymentFee             = $order->getBasePaymentFee();
        $basePaymentFeeInvoiced     = $order->getBasePaymentFeeInvoiced();
        $basePaymentFeeTax          = $order->getBasePaymentFeeTax();
        $basePaymentFeeTaxInvoiced  = $order->getBasePaymentFeeTaxInvoiced();
        
        //retrieve all fee-related values from order
        $paymentFee                 = $order->getPaymentFee();
        $paymentFeeInvoiced         = $order->getPaymentFeeInvoiced();
        $paymentFeeTax              = $order->getPaymentFeeTax();
        $paymentFeeTaxInvoiced      = $order->getPaymentFeeTaxInvoiced();
        
        //get current invoice totals
        $baseInvoiceTotal            = $invoice->getBaseGrandTotal();
        $invoiceTotal                = $invoice->getGrandTotal();
        
        $baseTaxAmountTotal          = $invoice->getBaseTaxAmount();
        $taxAmountTotal              = $invoice->getTaxAmount();

        //calculate how much needs to be invoiced
        $basePaymentFeeToInvoice    = $basePaymentFee - $basePaymentFeeInvoiced;
        $paymentFeeToInvoice        = $paymentFee - $paymentFeeInvoiced;
        
        $basePaymentFeeTaxToInvoice = $basePaymentFeeTax - $basePaymentFeeTaxInvoiced;
        $paymentFeeTaxToInvoice     = $paymentFeeTax - $paymentFeeTaxInvoiced;
        
        $basePaymentFeeTaxToInvoice -= $basePaymentFeeTaxInvoiced;
        $paymentFeeTaxToInvoice     -= $paymentFeeTaxInvoiced;
        
        $baseInvoiceTotal           += $basePaymentFeeToInvoice;
        $invoiceTotal               += $paymentFeeToInvoice;
        
        $invoice->setBaseGrandTotal($baseInvoiceTotal);
        $invoice->setGrandTotal($invoiceTotal);

        $invoice->setBaseTaxAmount($baseTaxAmountTotal);
        $invoice->setTaxAmount($taxAmountTotal);
        
        $invoice->setBasePaymentFee($basePaymentFeeToInvoice);
        $invoice->setPaymentFee($paymentFeeToInvoice);
        
        $invoice->setBasePaymentFeeTax($basePaymentFeeTaxToInvoice);
        $invoice->setPaymentFeeTax($paymentFeeTaxToInvoice);
        
        return $this;
    }
}