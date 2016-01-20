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
 
class Afterpay_Afterpay_Model_Observer_Capture extends Mage_Core_Model_Abstract
{
    public function sales_order_invoice_register(Varien_Event_Observer $observer)
    {
        //to prevent the script from running twice
        if (Mage::registry('captureStarted')) {
            Mage::unregister('captureStarted');
            return $this;
        }
        
        return $this->_capture($observer->getOrder(), $observer->getInvoice());
    }
    
    public function sales_order_payment_capture(Varien_Event_Observer $observer)
    {
        Mage::register('captureStarted', 1);
        
        return $this->_capture($observer->getInvoice()->getOrder(), $observer->getInvoice());
    }
    
    public function sales_order_invoice_save_before(Varien_Event_Observer $observer)
    {
        $invoice = $observer->getInvoice();
        
        if (!$invoice->getTransactionId() && $invoice->getOrder()->getAfterpayOrderReference()) {
            $invoice->setTransactionId($invoice->getOrder()->getAfterpayOrderReference());
        }
        
        return $this;
    }
    
    protected function _capture($order, $invoice)
    {    
        try {  
            if ($this->_captureIsAllowed($order, $invoice) !== true) {
                return $this;
            }
        
            $captureRequest = Mage::getModel('afterpay/request_capture'); 
            $captureRequest->setOrder($order)
                           ->setMethod($order->getPayment()->getMethod())
                           ->setInvoice($invoice);
            
            $result = $captureRequest->sendCaptureRequest();
        } catch (Exception $exception) {
            mage::helper('afterpay')->resetPaymentFeeInvoicedValues($order, $invoice);
            $invoice->cancel()->save();
            
            Mage::getSingleton('adminhtml/session')->addError($exception->getMessage());
            Mage::throwException($exception->getMessage());
        }
        
        if ($result === false) {
            mage::helper('afterpay')->resetPaymentFeeInvoicedValues($order, $invoice);
            $invoice->cancel()->save();
            
            Mage::throwException('Unable to capture this invoice');
        }
        
        return $this;
    }
    
    protected function _captureIsAllowed($order, $invoice)
    {
        $paymentMethodCode = $order->getpayment()->getMethod();
        
        if (strpos($paymentMethodCode, 'portfolio') === false) {
            return false;
        }
        
        if (Mage::getStoreConfig('afterpay/afterpay_capture/capture_mode', Mage::app()->getStore()->getId()) != '1') {
            return false;
        }
        
        if (
            $invoice->getBaseGrandTotal() - $order->getBaseGrandTotal() > 0.01 
            || $invoice->getBaseGrandTotal() - $order->getBaseGrandTotal() < -0.01
        ) {
            // Compatability Fix for TIG Buckaroo and PostNL
            if($invoice->getBaseGrandTotal - ($order->getBaseGrandTotal() + ($invoice->getPaymentFee() + $invoice->getPaymentFeeTax()) > 1)) {
                return true;
            }
            
            Mage::throwException('Can only capture full invoices. Partial invoices cannot be captured by AfterPay.');
            return false;
        }
        
        if (
            (isset($_POST['invoice']) && isset($_POST['invoice']['capture_case']))
            && $_POST['invoice']['capture_case'] != 'online'
        ) {
            return false;
        }
        
        return true;
    }
}