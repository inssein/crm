<?php

namespace OroCRM\Bundle\CaseBundle\Tests\Unit\Form\Type;

use OroCRM\Bundle\CaseBundle\Form\Type\CaseItemType;

class CaseItemTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CaseItemType
     */
    protected $formType;

    protected function setUp()
    {
        $this->formType = new CaseItemType();
    }

    /**
     * @param array $widgets
     *
     * @dataProvider formTypeProvider
     */
    public function testBuildForm(array $widgets)
    {
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $builder->expects($this->exactly(sizeof($widgets)))
            ->method('add')
            ->will($this->returnSelf());

        foreach ($widgets as $key => $widget) {
            $builder->expects($this->at($key))
                ->method('add')
                ->with($this->equalTo($widget))
                ->will($this->returnSelf());
        }

        $this->formType->buildForm($builder, []);
    }

    public function formTypeProvider()
    {
        return [
            'all' => [
                'widgets' => [
                    'order',
                    'cart',
                    'lead',
                    'opportunity',
                ]
            ]
        ];
    }

    public function testGetName()
    {
        $this->assertEquals('orocrm_case_item', $this->formType->getName());
    }
}
