<?php
/**
 * Custom Product Preview
 *
 * @category:    Aitoc
 * @package:     Aitoc_Aitcg
 * @version      11.2.2
 * @license:     AJifvLXz2Jhov40GDpSzkNqfs4dkmEPJtRhHJxJI2y
 * @copyright:   Copyright (c) 2013 AITOC, Inc. (http://www.aitoc.com)
 */
class Aitoc_Aitcg_Model_Mysql4_Sharedimage extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('aitcg/sharedimage', 'shared_img_id');

        // The primary key is not an auto_increment field
        $this->_isPkAutoIncrement = false;
    }
}