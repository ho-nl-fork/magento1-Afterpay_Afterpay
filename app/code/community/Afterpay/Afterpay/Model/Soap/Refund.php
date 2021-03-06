<?php 
/**
 * Copyright (c) 2011-2017  arvato Finance B.V.
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
 * @copyright   Copyright (c) 2011-2017 arvato Finance B.V.
 */
 
 class Afterpay_Afterpay_Model_Soap_Refund extends Afterpay_Afterpay_Model_Soap_Abstract
{
    protected $_isPartial = false;
    
    public function setIsPartial($isPartial)
    {
        $this->_isPartial = $isPartial;
        return $this;
    }
    
    public function getIsPartial()
    {
        return $this->_isPartial;
    }
    
    public function refundRequest()
    {
        $param        = $this->_addRefund();
        $paramName    = 'refundobject';
        
        if ($this->_isPartial) {
            $functionName = 'refundInvoice';
        } else {
            $functionName = 'refundFullInvoice';
        }
        
        return $this->soapRequest('service', $functionName, $paramName, $param);
    }
    
    protected function _addRefund()
    {
        $refundObject   = Mage::getModel('afterpay/soap_parameters_refund');
        $invoiceLines   = $this->_addInvoiceLines();
        $transactionKey = $this->_addTransactionKey();
        
        $refundObject->creditInvoicenNumber = $this->_vars['invoiceId'];
        $refundObject->invoicelines         = $invoiceLines;
        $refundObject->transactionkey       = $transactionKey;
        $refundObject->invoicenumber        = $this->_vars['invoiceId'];
        
        $refundObject = $this->_cleanEmptyValues($refundObject);
        
        return $refundObject;
    }
    
    protected function _addInvoiceLines()
    {
        $invoiceLines = array();
        
        if (!array_key_exists('orderLines', $this->_vars)) {
            return false;
        }
        
        foreach ($this->_vars['orderLines'] as $line) {
            if (empty($line)) {
                continue;
            }
            
            $invoiceLine = Mage::getModel('afterpay/soap_parameters_orderLine');
            
            $invoiceLine->articleDescription = preg_replace("/[^a-zA-Z0-9\_\-\s]/i", "", $line['articleDescription']);
            $invoiceLine->articleId          = $line['articleId'];
            $invoiceLine->quantity           = $line['quantity'];
            $invoiceLine->unitprice          = $line['unitPrice'];
            $invoiceLine->vatcategory        = $line['vatCategory'];
            
            $invoiceLine = $this->_cleanEmptyValues($invoiceLine);
            
            $invoiceLines[] = $invoiceLine;
        }
        
        $invoiceLines = $this->_cleanEmptyValues($invoiceLines);
        
        return $invoiceLines;
    }
    
    protected function _addTransactionKey()
    {
        $transactionKey = Mage::getModel('afterpay/soap_parameters_transactionKey');
        
        $transactionKey->ordernumber                = $this->_vars['orderNumber'];
        
        $transactionKey = $this->_cleanEmptyValues($transactionKey);
        
        return $transactionKey;
    }
}