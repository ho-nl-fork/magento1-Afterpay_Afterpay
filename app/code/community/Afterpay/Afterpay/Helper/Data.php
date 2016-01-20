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
 
 class Afterpay_Afterpay_Helper_Data extends Mage_Core_Helper_Abstract
{    
    public function getFeeLabel($paymentMethodCode = false)
    {
        if ($paymentMethodCode) {
            $feeLabel = Mage::getStoreConfig('afterpay/afterpay_' . $paymentMethodCode . '/portfolio_payment_fee_label', Mage::app()->getStore()->getId());
            if (empty($feeLabel)) {
                $feeLabel = 'AfterPay servicekosten';
            }
        } else {
            $feeLabel = 'AfterPay servicekosten';
        }
        
        $feeLabel = $this->__($feeLabel);
        
        return $feeLabel;
    }
    
    public function resetPaymentFeeInvoicedValues($order, $invoice)
    {
        $basePaymentFee    = $invoice->getBasePaymentFee();
        $paymentFee        = $invoice->getPaymentFee();
        $basePaymentFeeTax = $invoice->getBasePaymentFeeTax();
        $paymentFeeTax     = $invoice->getPaymentFeeTax();
         
        $basePaymentFeeInvoiced    = $order->getBasePaymentFeeInvoiced();
        $paymentFeeInvoiced        = $order->getPaymentFeeInvoiced();
        $basePaymentFeeTaxInvoiced = $order->getBasePaymentFeeTaxInvoiced();
        $paymentFeeTaxInvoiced     = $order->getPaymentFeeTaxInvoiced();
         
        if ($basePaymentFeeInvoiced && $basePaymentFee && $basePaymentFeeInvoiced >= $basePaymentFee) {
            $order->setBasePaymentFeeInvoiced($basePaymentFeeInvoiced - $basePaymentFee)
                  ->setPaymentFeeInvoiced($paymentFeeInvoiced - $paymentFee)
                  ->setBasePaymentFeeTaxInvoiced($basePaymentFeeTaxInvoiced - $basePaymentFeeTax)
                  ->setBasePaymentFeeInvoiced($paymentFeeTaxInvoiced - $paymentFeeTax);
            $order->save();
        }
    }
    
    public function sendDebugEmail($email) 
    {
        $recipients = explode(',', Mage::getStoreConfig('afterpay/afterpay_general/debug_mail', Mage::app()->getStore()->getStoreId()));
        
        foreach($recipients as $recipient) {
            mail(
                trim($recipient), 
                'Afterpay Debug E-mail', 
                $email
            );
        }
    }
    
    public function isAdmin()
    {
        if(Mage::app()->getStore()->isAdmin()) {
            return true;
        }

        if(Mage::getDesign()->getArea() == 'adminhtml') {
            return true;
        }

        return false;
    }
    
    public function getAfterPayPaymentMethods()
    {
        $array = array(
            'portfolio_a',
            'portfolio_b',
            'portfolio_c',
            'portfolio_d',
            'portfolio_e',
            'portfolio_f',
            // 'portfolio_g',
            // 'portfolio_h',
            // 'portfolio_i',
            // 'portfolio_j',
            // 'portfolio_k',
            // 'portfolio_l',
        );
        
        return $array;
    }
    
    public function isEnterprise()
    {
        return (bool) Mage::getConfig()->getModuleConfig("Enterprise_Enterprise")->version;
    }
    
    public function log($message, $force = false)
    {
        Mage::log($message, Zend_Log::DEBUG, 'Afterpay_AfterPay.log', $force);
    }

    public function logException($exception)
    {
        if ($exception instanceof Exception) {
            Mage::log($exception->getMessage(), Zend_Log::ERR, 'Afterpay_AfterPay_Exception.log', true);
            Mage::log($exception->getTraceAsString(), Zend_Log::ERR, 'Afterpay_AfterPay_Exception.log', true);
        } else {
            Mage::log($exception, Zend_Log::ERR, 'Afterpay_AfterPay_Exception.log', true);
        }
    }
    
    public function getTaxClass($percentage)
    {
        if ($percentage < 5) {
            return '4';
        } elseif ($percentage < 20) {
            return '2';
        } else {
            return '1';
        }
    }
    
    public function getTaxClassByAmounts($price, $tax)
    {
        $priceExTax = $price - $tax;
        $onePercent = $priceExTax / 100;
        $taxPercentage = round($tax / $onePercent);

        return Mage::helper('afterpay')->getTaxClass($taxPercentage);
    }
}