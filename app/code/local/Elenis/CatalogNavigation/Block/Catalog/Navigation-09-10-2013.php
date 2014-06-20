<?php

class Elenis_CatalogNavigation_Block_Catalog_Navigation extends Mage_Catalog_Block_Navigation {

    public function getParentTopCategory($c) {
        if (Mage::helper('catalognavigation')->isModuleEnabled()) {
            if ($c->getLevel() == 2) {
                return $c;
            } else if ($c->getLevel() > 2) {
                $parentCategory = Mage::getModel('catalog/category')->load($c->getParentId());

                return $this->getParentTopCategory($parentCategory);
            }
        }
    }

    public function retrieveAllChilds($id = null, $childs = null) {
        try {


            $category = Mage::getModel('catalog/category')->load($id);
            return $category->getResource()->getChildren($category, true);
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return;
        }
    }

    public function get_categories($categories, $crcat) {


        $childrenIds = $categories->getChildren();

        $childrens = explode(",", $childrenIds);

        // echo "gere--".$categories->getId();
        //echo "<pre>";print_R(count($childrens));

        foreach ($childrens as $category) {
            $cat = Mage::getModel('catalog/category')->load($category);

            if ($cat->hasChildren() == "") {
                if ($crcat == $cat->getId()) {
                    $string.= '<a href="' . $cat->getURL() . '"><div class="selectcat"></div><dt>' . $cat->getName() . '</dt></a>';
                } else {
                    //  echo $cat->getId()."-".$cat->getParentId()."-".$category."<br/>";
                  // echo "<pre>";print_r(Mage::app()->getRequest()->getParam('id'));exit;
                    //if (!is_array(explode(",", $cat->getChildren()))) {
                    //echo "<pre>test1";print_r($cat->getChildrenCount());
                    if($cat->getChildrenCount()==0 && $cat->getLevel()==3){
                       // echo "<pre>1";print_r($childrens);exit;
                        $string.= "<dt><b>" . $cat->getName() . "</b></dt>";
                    } else {
                       //echo "<pre>2";print_r($childrens);exit;
                        $string.= '<a href="' . $cat->getURL() . '"><div class="unselectcat"></div><dt>' . $cat->getName() . '</dt></a>';
                    }
                    
                    
                }
                $string.= "<br />";
            }





            if ($cat->hasChildren()) {
                // $children = Mage::getModel('catalog/category')->load($cat->getId());


                $string.= "<dt><b>" . $cat->getName() . "</b></dt>";



                $string.= $this->get_categories($cat, $crcat);
            }
        }
       

        return $string;
    }

}
