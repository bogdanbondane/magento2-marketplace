<?php
namespace WorldnetTPS\Subscription\Model;
use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    /**
     * Get Grid row currency labels array.
     * @return array
     */
    public function getCurrency()
    {
        $options = [ 'EUR' => __('Euro'),
                    'GBP' => __('Sterling'),
                    'USD' => __('US Dollar'),
                    'CAD' => __('Canadian Dollar'),
                    'AUD' => __('Australian Dollar'),
                    'DKK' => __('Danish Krone'),
                    'SEK' => __('Swedish Krona'),
                    'NOK' => __('Norwegian Krone')
                ];
        return $options;
    }

    /**
     * Get Grid row on update labels array.
     * @return array
     */
    public function getOnUpdate()
    {
        $options = ['1' => __('Continue Subscriptions'),'2' => __('Update Subscriptions')];
        return $options;
    }
    /**
     * Get Grid row on delete labels array.
     * @return array
     */
    public function getOnDelete()
    {
        $options = ['1' => __('Continue Subscriptions'),'2' => __('Finish Subscriptions')];
        return $options;
    }
    /**
     * Get Grid row period count labels array.
     * @return array
     */
    public function getPeriodCount()
    {
        $options = ['0' => __('Unlimited'),'1' => __('Limited')];
        return $options;
    }
    /**
     * Get Grid row period type labels array.
     * @return array
     */
    public function getPeriodType()
    {
        $options = ['2' => __('Weekly'),'3' => __('Bi-Weekly'),'4' => __('Monthly'),'5' => __('Quarterly'),'6' => __('Annually')];
        return $options;
    }
    /**
     * Get Grid row type labels array.
     * @return array
     */
    public function getType()
    {
        $options = ['1' => __('Automatic'),'2' => __('Manual'),'3' => __('Automatic (without amounts)')];
        return $options;
    }

    /**
     * Get Grid row status type labels array.
     * @return array
     */
    public function getOptionArray()
    {
        $options = ['1' => __('Enabled'),'0' => __('Disabled')];
        return $options;
    }

    /**
     * Get Grid row status labels array with empty value for option element.
     *
     * @return array
     */
    public function getAllOptions()
    {
        $res = $this->getOptions();
        array_unshift($res, ['value' => '', 'label' => '']);
        return $res;
    }

    /**
     * Get Grid row type array for option element.
     * @return array
     */
    public function getOptions()
    {
        $res = [];
        foreach ($this->getOptionArray() as $index => $value) {
            $res[] = ['value' => $index, 'label' => $value];
        }
        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getOptions();
    }
}