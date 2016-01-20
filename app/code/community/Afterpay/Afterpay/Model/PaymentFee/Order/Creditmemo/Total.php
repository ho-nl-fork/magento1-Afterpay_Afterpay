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
 
class Afterpay_Afterpay_Model_PaymentFee_Order_Creditmemo_Total extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    /**
     * Retrieves Payment Fee values, calculates the amount that can be refunded
     * 
     * @param Mage_Sales_Model_Order_Creditmemo $invoice
     */
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        
        $order = $creditmemo->getOrder();

        //retreive all base fee-related values from order
        $basePaymentFee             = $order->getBasePaymentFeeInvoiced();
        $basePaymentFeeRefunded     = $order->getBasePaymentFeeRefunded();
        $basePaymentFeeTax          = $order->getBasePaymentFeeTaxInvoiced();
        $basePaymentFeeTaxRefunded  = $order->getBasePaymentFeeTaxRefunded();
        
        //retreive all fee-related values from order
        $paymentFee                 = $order->getPaymentFeeInvoiced();
        $paymentFeeRefunded         = $order->getPaymentFeeRefunded();
        $paymentFeeTax              = $order->getPaymentFeeTaxInvoiced();
        $paymentFeeTaxRefunded      = $order->getPaymentFeeTaxRefunded();
        
        //get current creditmemo totals
        $baseRefundTotal             = $creditmemo->getBaseGrandTotal();
        $creditmemoTotal             = $creditmemo->getGrandTotal();
        
        $baseTaxAmountTotal          = $creditmemo->getBaseTaxAmount();
        $taxAmountTotal              = $creditmemo->getTaxAmount();

        //calculate how much needs to be creditmemod
        $basePaymentFeeToRefund     = $basePaymentFee - $basePaymentFeeRefunded;
        $paymentFeeToRefund         = $paymentFee - $paymentFeeRefunded;
        
        $basePaymentFeeTaxToRefund  = $basePaymentFeeTax - $basePaymentFeeTaxRefunded;
        $paymentFeeTaxToRefund      = $paymentFeeTax - $paymentFeeTaxRefunded;
        
        $baseRefundTotal            += $basePaymentFeeToRefund;
        $creditmemoTotal            += $paymentFeeToRefund;
        
        $baseTaxAmountTotal         += $basePaymentFeeTaxToRefund;
        $taxAmountTotal             += $paymentFeeTaxToRefund;
        
        //set the new creditmemod values
        $creditmemo->setBaseGrandTotal($baseRefundTotal + $basePaymentFeeTaxToRefund);
        $creditmemo->setGrandTotal($creditmemoTotal + $paymentFeeTaxToRefund);
        
        $creditmemo->setBaseTaxAmount($baseTaxAmountTotal);
        $creditmemo->setTaxAmount($taxAmountTotal);

        $creditmemo->setBasePaymentFee($basePaymentFeeToRefund);
        $creditmemo->setPaymentFee($paymentFeeToRefund);
        
        $creditmemo->setBasePaymentFeeTax($basePaymentFeeTax);
        $creditmemo->setPaymentFeeTax($paymentFeeTax);
        
        return $this;
    }
}