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
 
 class Afterpay_Afterpay_Block_PaymentFee_Order_Totals_Paymentfee extends Mage_Core_Block_Abstract
{
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->_order  = $parent->getOrder();
     
        if ($this->_order->getPaymentFee() < 0.01 || $this->_order->getPaymentFee() < 0.01) {
            return $this;
        }
        
        $feeLabel = Mage::helper('afterpay')->getfeeLabel($this->_order->getPayment()->getMethod());
        
        $paymentFee = new Varien_Object();
        $paymentFee->setLabel($feeLabel);
        $paymentFee->setValue($this->_order->getPaymentFee() + $this->_order->getPaymentFeeTax());
        $paymentFee->setBaseValue($this->_order->getBasePaymentFee() + $this->_order->getBasePaymentFeeTax());
        $paymentFee->setCode('payment_fee');
  
        $parent->addTotalBefore($paymentFee, 'tax');

        return $this;
    }
}