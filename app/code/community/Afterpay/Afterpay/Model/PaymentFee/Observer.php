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
 
 class Afterpay_Afterpay_Model_PaymentFee_Observer extends Mage_Core_Model_Abstract 
{    
    /**
     * Collects paymentFee from quote/addresses to quote
     *
     * @param Varien_Event_Observer $observer
     */
    public function sales_quote_collect_totals_after(Varien_Event_Observer $observer) 
    {
        $quote = $observer->getEvent()->getQuote();
        
        $quote->setPaymentFee(0);
        $quote->setBasePaymentFee(0);
        $quote->setPaymentFeeTax(0);
        $quote->setBasePaymentFeeTax(0);
                
        foreach ($quote->getAllAddresses() as $address) 
        {
            if (!$quote->getPaymentFee()) {
                $quote->setPaymentFee((float) $address->getPaymentFee());
            }
            if (!$quote->getBasePaymentFee()) {
                $quote->setBasePaymentFee((float) $address->getBasePaymentFee());
            }
            if (!$quote->getPaymentFeeTax()) {
                $quote->setPaymentFeeTax((float) $address->getPaymentFeeTax());
            }
            if (!$quote->getBasePaymentFeeTax()) {
                $quote->setBasePaymentFeeTax((float) $address->getBasePaymentFeeTax());
            }
        }
        
        /* Bugfix for correct payment fee amount */
        if($quote->getPaymentFeeTax() !== 0)
        {
            $address->setTaxAmount($address->getTaxAmount() + $quote->getPaymentFeeTax());
            $address->setBaseTaxAmount($address->getBaseTaxAmount() + $quote->getBasePaymentFeeTax());
            $address->setSubTotalInclTax($address->getSubTotalInclTax() + $quote->getSubTotalInclTax());
        }
                
        $quote->save();
    }

    /**
     * Adds PaymentFee to order
     * 
     * @param Varien_Event_Observer $observer
     */
    public function sales_order_payment_place_end(Varien_Event_Observer $observer) 
    {
        $payment = $observer->getPayment();

        $order = $payment->getOrder();
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        
        if (!$quote->getId()) {
            $quote = Mage::getSingleton('adminhtml/session_quote')->getQuote();
        }
        
        $order->setBasePaymentFee($quote->getBasePaymentFee());
        $order->setPaymentFee($quote->getPaymentFee());
        
        $order->setBasePaymentFeeTax($quote->getBasePaymentFeeTax());
        $order->setPaymentFeeTax($quote->getPaymentFeeTax());
        
        $order->setBaseTaxAmount($order->getBaseTaxAmount());
        $order->setTaxAmount($order->getTaxAmount());
        
        $order->save();
        
        $info = $payment->getMethodInstance()->getInfoInstance();
        
        $info->setAdditionalInformation('payment_fee', $quote->getPaymentFee());
        $info->setAdditionalInformation('base_payment_fee', $quote->getBasePaymentFee());
        
        $info->setAdditionalInformation('payment_fee_tax', $quote->getPaymentFeeTax());
        $info->setAdditionalInformation('base_payment_fee_tax', $quote->getBasePaymentFeeTax());
        
        $info->save();
    }
    
    /**
     * Adds the payment fee to the creditmemo
     * 
     * @param Varien_Event_Observer $observer
     */
    public function paymentfee_order_creditmemo_refund_before(Varien_Event_Observer $observer)
    {
        $creditmemo = $observer->getCreditmemo();
        
        $paymentFee = Mage::getModel('afterpay/paymentFee_refund', $creditmemo);
        $creditmemo = $paymentFee->paymentFeeRefund();
    }
    
    /**
     * Updates the order with the newly invoiced values
     * 
     * @param Varien_Event_Observer $observer
     */
    public function sales_order_invoice_register(Varien_Event_Observer $observer)
    {
        $invoice = $observer->getInvoice();
        $order = $invoice->getOrder();
        
        $order->setBasePaymentFeeInvoiced($invoice->getBasePaymentFee());
        $order->setPaymentFeeInvoiced($invoice->getPaymentFee());
        
        $order->setBasePaymentFeeTaxInvoiced($invoice->getBasePaymentFeeTax());
        $order->setPaymentFeeTaxInvoiced($invoice->getPaymentFeeTax());
        $order->save();
    }
}