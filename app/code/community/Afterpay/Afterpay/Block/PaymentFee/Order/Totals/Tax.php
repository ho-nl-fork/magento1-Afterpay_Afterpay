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
 
 class Afterpay_Afterpay_Block_PaymentFee_Order_Totals_Tax extends Mage_Adminhtml_Block_Sales_Order_Totals_Tax
{
    /**
     * Get full information about taxes applied to order
     *
     * @return array
     */
    public function getFullTaxInfo()
    {
        /** @var $source Mage_Sales_Model_Order */
        $source = $this->getOrder();

        $taxClassAmount = array();
        if ($source instanceof Mage_Sales_Model_Order) {
            $taxClassAmount = Mage::helper('tax')->getCalculatedTaxes($source);
            if (empty($taxClassAmount)) {
                $rates = Mage::getModel('sales/order_tax')->getCollection()->loadByOrder($source)->toArray();
                $taxClassAmount =  Mage::getSingleton('tax/calculation')->reproduceProcess($rates['items']);
            } else {
                
                $foundTitle = false;
                foreach($taxClassAmount as $taxClassAmountItem) {
                        if ($taxClassAmountItem['title'] == 'AfterPay Servicekosten Tax') {
                                $foundTitle = true;
                        }
                }

                if ($source->getBasePaymentFeeTax() && $foundTitle == false) {

                    $paymentFeeTax = array(
                        array(
                            'tax_amount'      => $source->getPaymentFeeTax(),
                            'base_tax_amount' => $source->getBasePaymentFeeTax(),
                            'title'           => 'AfterPay Servicekosten Tax',
                            'percent'         => 21,
                        ),
                    );
                    $taxClassAmount = array_merge($paymentFeeTax, $taxClassAmount);
                }
            }
        }

        return $taxClassAmount;
    }
}