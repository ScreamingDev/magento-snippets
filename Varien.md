# Create your own array-like collection

Pretty simple. Use the `Varien_Data_Collection`,
fill it with `Varien_Object`
but remember to provide an ID in every data-set.


    class MyOwn_Extension_Model_Foo_Collection
          extends Varien_Data_Collection
    {
        public $_collectionCache = array();

        public function load($printQuery = false, $logQuery = false)
        {
            if (!$this->isLoaded()) {
                $modulesArray = (array) Mage::getConfig()->getNode('modules')->children();

                foreach ($modulesArray as $name => $singleModule) {
                
                    $extensionModel = new Varien_Object();
                    $extensionModel->setData(
                        array(
                            'id' => uniqid(), // sometimes needed by Collection
                            'name' => $name,
                        )
                    );

                    $this->addItem($extensionModel);
                }
            }
        }
    }

