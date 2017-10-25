<?php


namespace HykeCorp\Quickorder\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    protected $_product;

    // protected $_productRepository;

        /**
              * @var \Magento\Checkout\Model\Cart
              */
    protected $cart;
    


    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
         \Magento\Catalog\Model\Product $product,
         \Magento\Checkout\Model\Cart $cart
         // \Magento\Framework\Data\Form\FormKey $formKey
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_product = $product;

        // $this->_productRepository = $productRepository;
        $this->cart = $cart;
        // $this->formKey = $formKey;

        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
// recieve data from ajax form
        $reqQoSku= $post = $this->getRequest()->getPostValue('qostockno1');
        //echo $reqQoSku;
        $reqQoQty= $post = $this->getRequest()->getPostValue('qoqty1');
        //echo $reqQoQty;

// check availability
        $sku ='#'.$reqQoSku;
        if($this->_product->getIdBySku($sku)) {
            //echo 'product exist';    
       
// Display selected product name
            $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();        
            $productRepository = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
            // get product by product sku
            $product = $productRepository->get($sku);
            //echo 'selected: '.$product->getName();
            $productName=$product->getName();
           $productId=$product->getId();

// check customer logged in state, if needed
                
                // $customerSession = $objectManager->get('Magento\Customer\Model\Session');
                // if($customerSession->isLoggedIn()) {
                //     echo "Logged in";
                // }
                // else{
                //     echo "Not logged in";
                // }

// Add to cart

    // get Formkey
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
                $FormKey = $objectManager->get('Magento\Framework\Data\Form\FormKey'); 
                $Formkeyget=$FormKey->getFormKey();
                //echo $Formkeyget;

                        

    //Add into cart
                    try {
                    $params = array(
                    'form_key' => $Formkeyget,
                    'product' =>$productId, //product Id
                    'qty'   =>$reqQoQty //quantity of product                
                    );  

                        $this->cart->addProduct($product, $params);
                        $this->cart->save();
                        //$this->messageManager->addSuccess(__('Item has been successfully added to cart.'));

                    $this->messageManager->addSuccess(__($productName.' is successfully added to cart .'));
                    } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->messageManager->addException(
                    $e,
                    __('%1', $e->getMessage())
                    );
                    } catch (\Exception $e) {
                    $this->messageManager->addException($e, __('Error in operation.'));
                    }

                    /*cart page*/
                    //$this->getResponse()->setRedirect('/checkout/cart/index');






        }

        else{
            echo "Product is not available";
        }










        //return $this->resultPageFactory->create();
    }
}
