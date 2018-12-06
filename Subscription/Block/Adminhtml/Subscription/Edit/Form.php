<?php
namespace WorldnetTPS\Subscription\Block\Adminhtml\Subscription\Edit;


/**
 * Adminhtml Add New Row Form.
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry             $registry
     * @param \Magento\Framework\Data\FormFactory     $formFactory
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \WorldnetTPS\Subscription\Model\Status $options,
        array $data = []
    )
    {
        $this->_options = $options;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $model = $this->_coreRegistry->registry('row_data');
        $form = $this->_formFactory->create(
            ['data' => [
                'id' => 'edit_form',
                'enctype' => 'multipart/form-data',
                'action' => $this->getData('action'),
                'method' => 'post'
            ]
            ]
        );

        $form->setHtmlIdPrefix('worldnettps_subscription_');
        if ($model->getEntityId()) {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __(''), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        } else {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __(''), 'class' => 'fieldset-wide']
            );
        }

        if ($model->getEntityId()) {
            $fieldset->addField(
                'merchant_ref2',
                'text',
                [
                    'name' => 'merchant_ref2',
                    'label' => __('Merchant Ref'),
                    'id' => 'merchant_ref2',
                    'title' => __('Merchant Ref'),
                    'class' => 'required-entry',
                    'required' => true,
                    'disabled' => true
                ]
            );
            $fieldset->addField(
                'merchant_ref',
                'hidden',
                [
                    'name' => 'merchant_ref',
                    'label' => __(''),
                    'id' => 'merchant_ref',
                    'title' => __(''),
                    'class' => 'required-entry',
                    'required' => true,
                ]
            );
        } else {
            $fieldset->addField(
                'merchant_ref',
                'text',
                [
                    'name' => 'merchant_ref',
                    'label' => __('Merchant Ref'),
                    'id' => 'merchant_ref',
                    'title' => __('Merchant Ref'),
                    'class' => 'required-entry',
                    'required' => true,
                ]
            );
        }

        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Name'),
                'id' => 'name',
                'title' => __('Name'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'description',
            'text',
            [
                'name' => 'description',
                'label' => __('Description'),
                'id' => 'description',
                'title' => __('Description'),
                'class' => '',
                'required' => false,
            ]
        );

        $fieldset->addField(
            'type',
            'select',
            [
                'name' => 'type',
                'label' => __('Type'),
                'id' => 'type',
                'title' => __('Type'),
                'values' => $this->_options->getType(),
                'class' => '',
                'onchange' => 'WorldnetTPSSubscriptionType()'
            ]
        );

        $fieldset->addField(
            'period_type',
            'select',
            [
                'name' => 'period_type',
                'label' => __('Period Type'),
                'id' => 'period_type',
                'title' => __('Period Type'),
                'values' => $this->_options->getPeriodType(),
                'class' => '',
                'disabled' => $model->getEntityId()?true:false
            ]
        );

        $fieldset->addField(
            'period_count',
            'select',
            [
                'name' => 'period_count',
                'label' => __('Period Count'),
                'id' => 'period_count',
                'title' => __('Period Count'),
                'values' => $this->_options->getPeriodCount(),
                'class' => '',
                'onchange' => 'WorldnetTPSSubscriptionPeriodCount()'
            ]
        );

        $fieldset->addField(
            'period_length',
            'text',
            [
                'name' => 'period_length',
                'label' => __(''),
                'id' => 'period_length',
                'title' => __(''),
                'required' => false
            ]
        );

        $fieldset->addField(
            'recurring_price',
            'text',
            [
                'name' => 'recurring_price',
                'label' => __('Recurring Price'),
                'id' => 'recurring_price',
                'title' => __('Recurring Price'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'setup_price',
            'text',
            [
                'name' => 'setup_price',
                'label' => __('Setup Price'),
                'id' => 'setup_price',
                'title' => __('Setup Price'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        if ($model->getEntityId()) {
            $fieldset->addField(
                'currency2',
                'select',
                [
                    'name' => 'currency2',
                    'label' => __('Currency'),
                    'id' => 'currency2',
                    'title' => __('Currency'),
                    'class' => 'required-entry',
                    'required' => true,
                    'values' => $this->_options->getCurrency(),
                    'disabled' => true
                ]
            );
            $fieldset->addField(
                'currency',
                'hidden',
                [
                    'name' => 'currency',
                    'label' => __(''),
                    'id' => 'currency',
                    'title' => __(''),
                    'class' => 'required-entry',
                    'required' => true,
                ]
            );
        } else {
            $fieldset->addField(
                'currency',
                'select',
                [
                    'name' => 'currency',
                    'label' => __('Currency'),
                    'id' => 'currency',
                    'title' => __('Currency'),
                    'class' => 'required-entry',
                    'required' => true,
                    'values' => $this->_options->getCurrency(),
                ]
            );
        }

        $fieldset->addField(
            'on_update',
            'select',
            [
                'name' => 'on_update',
                'label' => __('On Update'),
                'id' => 'on_update',
                'title' => __('On Update'),
                'values' => $this->_options->getOnUpdate(),
                'class' => '',
            ]
        );

        $fieldset->addField(
            'on_delete',
            'select',
            [
                'name' => 'on_delete',
                'label' => __('On Delete'),
                'id' => 'on_delete',
                'title' => __('On Delete'),
                'values' => $this->_options->getOnDelete(),
                'class' => '',
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}