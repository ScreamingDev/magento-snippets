<?php

$attributes = array(
    'customer' => array(
        'customer_number' => array(
            'type'         => 'varchar',
            'label'        => 'Customer Number',
            'input'        => 'text',
            'global'       => 1,
            'visible'      => 1,
            'required'     => 0,
            'user_defined' => 1,
            'default'      => '0',
        ),
    ),
);

$defaultUsedInForms = array(
    'customer_account_create',
    'customer_account_edit',
    'adminhtml_customer',
);

foreach ($attributes as $entityTypeId => $entity)
{
    foreach ($entity as $code => $attr)
    {
        $attribute = $eavConfig->getAttribute($entityTypeId, $code);

        if (!$attribute)
        {
            $setup->addAttribute($entityTypeId, $code, $attr);
        }
        else
        {
            $setup->updateAttribute($entityTypeId, $code, $attr);
        }

        // see /htdocs/app/code/core/Mage/Customer/sql/customer_setup/mysql4-data-upgrade-1.4.0.0.13-1.4.0.0.14.php:60

        $attribute = $eavConfig->getAttribute($entityTypeId, $code);
        if (!$attribute)
        {
            continue;
        }

        if (false === ($attribute->getData('is_system') == 1 && $attribute->getData('is_visible') == 0))
        {
            $usedInForms = $defaultUsedInForms;
            if (!empty($data['adminhtml_only']))
            {
                $usedInForms = array('adminhtml_customer');
            }
            else
            {
                $usedInForms[] = 'adminhtml_customer';
            }
            if (!empty($data['admin_checkout']))
            {
                $usedInForms[] = 'adminhtml_checkout';
            }
            $attribute->setData('used_in_forms', $usedInForms);
        }
        $attribute->save();
    }
}
