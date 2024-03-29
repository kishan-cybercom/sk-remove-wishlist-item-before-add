<?php
/**
 * @package   SK\RemoveWishlistItemBeforeAdd
 * @author    Kishan Savaliya <kishansavaliyakb@gmail.com>
 */

namespace SK\RemoveWishlistItemBeforeAdd\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;

class RemoveWishlistItemBeforeAdd implements ObserverInterface
{
	protected $customerSession;
	
	protected $wishlist;
	
	public function __construct(
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Wishlist\Model\Wishlist $wishlist
	) {
		$this->customerSession = $customerSession;
        $this->wishlist = $wishlist;
	}

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
    	$customerId = $this->customerSession->getCustomer()->getId();
    	$currentProductId = $observer->getRequest()->getParam('product');
    	
    	if($customerId){
    		$currentCustomerWishlist = $this->wishlist->loadByCustomerId($customerId);
	        $wishlistItems = $currentCustomerWishlist->getItemCollection();

	        foreach ($wishlistItems as $wishlistItem) {
	            if ($wishlistItem->getProductId() == $currentProductId) {
	                $wishlistItem->delete();
	            }
	        }
    	}
		
        return $observer;
    }  
}