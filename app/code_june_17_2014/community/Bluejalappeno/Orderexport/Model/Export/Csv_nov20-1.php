<?php
/**
 * Magento Bluejalappeno Order Export Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Bluejalappeno
 * @package    Bluejalappeno_OrderExport
 * @copyright  Copyright (c) 2010 Wimbolt Ltd (http://www.bluejalappeno.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Genevieve Eddison <sales@bluejalappeno.com>
 * */
class Bluejalappeno_Orderexport_Model_Export_Csv extends Bluejalappeno_Orderexport_Model_Export_Abstractcsv
{
    const ENCLOSURE = '"';
    const DELIMITER = ',';

    /**
     * Concrete implementation of abstract method to export given orders to csv file in var/export.
     *
     * @param $orders List of orders of type Mage_Sales_Model_Order or order ids to export.
     * @return String The name of the written csv file in var/export
     */
    public function exportOrders($orders)
    {
        $fileName = 'order_export_'.date("Ymd_His").'.csv';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');
        
        echo $fromDate=date("Y-m-d 00:00:01", strtotime('oct 16 2013'));echo '<br/>';
        echo $toDate=date("Y-m-d 23:59:59", strtotime('now'));
         
        $this->writeHeadRow($fp);
        $orders = Mage::getModel('sales/order')->getCollection()
              ->addAttributeToFilter('created_at', array('from'=>$fromDate, 'to'=>$toDate));
        
      
        foreach ($orders as $order) {
      //  echo "<pre>";
      // print_r($order);die();
                $this->writeOrder($order, $fp);
        }

        fclose($fp);

        return $fileName;
    }

    /**
	 * Writes the head row with the column names in the csv file.
	 *
	 * @param $fp The file handle of the csv file
	 */
    protected function writeHeadRow($fp)
    {
        fputcsv($fp, $this->getHeadRowValues(), self::DELIMITER, self::ENCLOSURE);
    }

    /**
	 * Writes the row(s) for the given order in the csv file.
	 * A row is added to the csv file for each ordered item.
	 *
	 * @param Mage_Sales_Model_Order $order The order to write csv of
	 * @param $fp The file handle of the csv file
	 */
    protected function writeOrder($order, $fp)
    {
        $common = $this->getCommonOrderValues($order);

        $orderItems = $order->getItemsCollection();
        $itemInc = 0;
        $record=array();
        foreach ($orderItems as $item)
        {
            if (!$item->isDummy()) {
                $record = array_merge($record, $this->getOrderItemValues($item, $order));
               
            }
        }
       $completerecords = array_merge($common, $record);
       // echo "<pre>";
        //print_r($record);die('tesgt');
         fputcsv($fp, $completerecords, self::DELIMITER, self::ENCLOSURE);
    }

    /**
	 * Returns the head column names.
	 *
	 * @return Array The array containing all column names
	 */
    protected function getHeadRowValues()
    {
        return array(
            'import_id',
            'customer_id',
            'order_date',
            'delivery_date',
            'payment_date',
            'bill_first_name',
            'bill_last_name',
            'bill_street1',
            'bill_street2',
            'bill_city',
            'bill_state',
            'bill_zip',
            'bill_phone',
            'bill_email',
            'ship_first_name',
            'ship_last_name',
            'ship_street1',
            'ship_street2',
             'ship_city',
              'ship_state',
            'ship_zip',
              'shipping_method',
            'shipping_amt',
            'tax_code',
            'gift_card',
            'order_total',
            'transaction_id',
            'payee_name',
            'cc_number',
            'cc_exp',
            'payment_type',
            'product_notes',
            'product_1_sku',
            'product_1_name',
            'product_1_qty',
            'product_1_price',
            'product_1_customimage',
            'product_2_sku',
            'product_2_name',
            'product_2_qty',
            'product_2_price',
            'product_2_customimage',
            'product_3_sku',
            'product_3_name',
            'product_3_qty',
            'product_3_price',
            'product_3_customimage',
            'product_4_sku',
            'product_4_name',
            'product_4_qty',
            'product_4_price',
            'product_4_customimage',
             'product_5_sku',
            'product_5_name',
            'product_5_qty',
            'product_5_price',
            'product_5_customimage',
            'product_6_sku',
            'product_6_name',
            'product_6_qty',
            'product_6_price',
            'product_6_customimage',
            'product_7_sku',
            'product_7_name',
            'product_7_qty',
            'product_7_price',
            'product_7_customimage',
             'product_8_sku',
            'product_8_name',
            'product_8_qty',
            'product_8_price',
            'product_8_customimage',
             'product_9_sku',
            'product_9_name',
            'product_9_qty',
            'product_9_price',
            'product_9_customimage',
            'product_10_sku',
            'product_10_name',
            'product_10_qty',
            'product_10_price',
            'product_10_customimage'
        );
    }

    /**
	 * Returns the values which are identical for each row of the given order. These are
	 * all the values which are not item specific: order data, shipping address, billing
	 * address and order totals.
	 *
	 * @param Mage_Sales_Model_Order $order The order to get values from
	 * @return Array The array containing the non item specific values
	 */
    protected function getCommonOrderValues($order)
    {
        $payarry=$order->getPayment()->getData();
                 $cardinfo = $payarry['additional_information'];
          echo '<pre>';
        foreach($cardinfo as $key => $cardinfo)
        {               
            foreach ($cardinfo as $key1 => $value){
                 $cc_exp_month = $value['cc_exp_month'];
                 $cc_last4 = $value['cc_last4'];
                 $cc_exp_year = $value['cc_exp_year'];
                 $last_trans_id = $value['last_trans_id'];
                 
            }
          
        }
       
        $shippingAddress = !$order->getIsVirtual() ? $order->getShippingAddress() : null;
        $billingAddress = $order->getBillingAddress();
        return array(
            $order->getRealOrderId(),
            $order->getCustomerId(),
            Mage::helper('core')->formatDate($order->getCreatedAt(), 'medium', true),
            $order->getDeliveryDate(),
            Mage::helper('core')->formatDate($order->getCreatedAt(), 'medium', true),
            $billingAddress->getName(),
            $billingAddress ? $billingAddress->getLastName() : '',
             $billingAddress ? $billingAddress->getStreet(1) : '',
            $billingAddress ? $billingAddress->getStreet(2) : '',
            $billingAddress->getData("city"),
            $billingAddress->getRegionCode(),
            $billingAddress->getData("postcode"),
            $billingAddress->getData("telephone"),
            $order->getCustomerEmail(),
            $shippingAddress ? $shippingAddress->getName() : '',
            $shippingAddress ? $shippingAddress->getName1() : '',
            $shippingAddress ? $shippingAddress->getStreet(1) : '',
            $shippingAddress ? $shippingAddress->getStreet(2) : '',
            $shippingAddress ? $shippingAddress->getData("city") : '',
            $shippingAddress ? $shippingAddress->getData("postcode") : '',
            $shippingAddress ? $shippingAddress->getRegionCode() : '',
            $this->getShippingMethod($order),
            $this->formatPrice($order->getData('shipping_amount'), $order),
            'Tax Code '.$this->formatPrice($order->getData('tax_amount'), $order),
            'Gift Card '.$order->getGiftCardsAmount(),
             $this->formatPrice($order->getData('grand_total'), $order),
            $last_trans_id ? $last_trans_id : '',
            $payment['cc_owner'] ? $payment['cc_owner'] : '',
            $cc_last4 ? 'XXXXXXXXXXXX'.$cc_last4 : '',
            $cc_exp_year ? $cc_exp_month.'-'.$cc_exp_year : '',
            $this->getPaymentMethod($order),
            'product_notes',
         );
    }

    /**
	 * Returns the item specific values.
	 *
	 * @param Mage_Sales_Model_Order_Item $item The item to get values from
	 * @param Mage_Sales_Model_Order $order The order the item belongs to
	 * @return Array The array containing the item specific values
	 */
    protected function getOrderItemValues($item, $order, $itemInc=1)
    {
 echo "<pre>";
 //print_r($item->getData());
 
 if ($item->getProductOptions()){
     foreach($item->getProductOptions() as $values){
            $imageOption = $values['options'] ? $values['options'] :'';
            if(!empty($imageOption)){
            foreach ($imageOption as $imgValues) {
                $img_data = $imgValues['img_data'] ? $imgValues['img_data'] :'';
                $arrImage = current(json_decode($img_data));
                $uploadedImage = $arrImage->src;
            }
          }
        } 
 }
//echo $options['options'];die();
 //print_r($item->getProductOptions());
  
        return array(
            $this->getItemSku($item),
            $item->getName(),
            (int)$item->getQtyOrdered(),
            $this->formatPrice($item->getOriginalPrice(), $order),
            $uploadedImage
            //$this->formatPrice($item->getData('price'), $order),
        );
    }
}
?>