# Sales

## Order

### Add custom attribute to order

In the install script:

    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
    $setup->addAttribute('order', 'NEW_FIELD', array(
        'position'                   => 1,
        'type'                       => 'text',
        'label'                      => 'Your New Field',
        'global'                     => 1,
        'visible'                    => 1,
        'required'                   => 0,
        'user_defined'               => 1,
        'searchable'                 => 0,
        'filterable'                 => 0,
        'comparable'                 => 0,
        'visible_on_front'           => 1,
        'visible_in_advanced_search' => 0,
        'unique'                     => 0,
        'is_configurable'            => 0,
        'position'                   => 1,
    ));
    
### Attach files to the order mail

Rewrite the Mage_Sales_Model_Order in the config.xml but remember
to replace the "Acme_Foo" with your company and module name:

    <global>
        <models>
            <sales>
                <rewrite>
                    <order>Acme_Foo_Model_Order</order>
                </rewrite>
            </sales>
        </models>
    </global>
    
Now you need to extend `Mage_Sales_Model_Order` but with a copy
of the method "sendNewOrderEmail".
Right before `$mailer->send();` you place your lines to add attachements:

    class Acme_Foo_Model_Order extends Mage_Sales_Model_Order
    {
        public function sendNewOrderEmail()
        {
            // copy all of the original contents here !!!
            
            // right before `$mailer->send();` at the bottom you add:
            $mailer->addAttachment($fileContents, $attachementName);
            
            // and then the origin continues:
            $mailer->send();
            // ... and so on
        }
    }

## Quote

### Pipe attribute to quote 

In the config.xml:

    <global>
        <sales>
            <quote>
                <item>
                    <product_attributes>
                        <NEW_FIELD />
                    </product_attributes>
                </item>
            </quote>
        </sales>
    </global>
