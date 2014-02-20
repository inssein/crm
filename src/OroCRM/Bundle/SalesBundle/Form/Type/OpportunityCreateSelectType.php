<?php

namespace OroCRM\Bundle\SalesBundle\Form\Type;

use Oro\Bundle\FormBundle\Form\Type\OroEntityCreateOrSelectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyPath;

class OpportunityCreateSelectType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'class' => 'OroCRM\Bundle\SalesBundle\Entity\Opportunity',
                'create_entity_form_type' => 'orocrm_sales_opportunity',
                'grid_name' => 'sales-opportunity-grid',
                'view_widgets' => array(
                    array(
                        'route_name' => 'orocrm_sales_opportunity_info',
                        'route_parameters' => array(
                            'id' => new PropertyPath('id')
                        ),
                        'grid_row_to_route' => array(
                            'id' => 'id'
                        ),
                    )
                ),
            )
        );
    }

    public function getParent()
    {
        return OroEntityCreateOrSelectType::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'orocrm_sales_opportunity_create_select';
    }
}
