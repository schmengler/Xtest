<?php

class Codex_Xtest_Xtest_Pageobject_Frontend_Product extends Codex_Xtest_Xtest_Pageobject_Abstract
{

    protected $_selectors = array(
        'add_to_cart_button' => '.add-to-cart-buttons button',
        'success_message'    => 'ul.messages li.success-msg'
    );

    /** @var  Mage_Catalog_Model_Product */
    protected $_product;

    public function openBySku( $sku )
    {
        $this->open( Mage::getModel('catalog/product')->getIdBySku( $sku ) );
    }

    public function open( $product_id )
    {
        $this->_product = Mage::getModel('catalog/product')->load( $product_id );
        if( $this->_product->getId() )
        {
            $this->url( $this->_product->getProductUrl() );
            return $this;
        }
        throw new Exception("product not found");
    }

    protected function getAddToCartForm()
    {
        $eForm = $this->byId('product_addtocart_form');
        return $eForm;
    }

    public function setQty( $qty )
    {
        $eQty = $this->getAddToCartForm()->byId('qty');
        $eQty->value( $qty);
        return $this;
    }

    public function pressAddToCart()
    {
        $elements = $this->findElementsByCssSelector($this->_selectors['add_to_cart_button'], $this->getAddToCartForm() );
        foreach( $elements AS $element )
        {
            if( $element->displayed() )
            {
                $element->click();
            }
        }
        return $this;
    }

    public function assertAddToCartMessageAppears()
    {
        $addToCartText = $this->byCssSelector($this->_selectors['success_message'])->text();
        $this->assertStringEndsWith( Mage::helper('checkout')->__("%s was added to your shopping cart.",''), $addToCartText );
        return $this;
    }

    public function getProductName()
    {
        return $this->byCssSelector('.product-name')->text();
    }

}