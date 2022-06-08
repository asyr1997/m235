<?php
/**
 * @package   Born_SurpriseDrop
 * @author    Manish Bhojwani (Manish.Bhojwani@BornGroup.com)
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */
namespace Born\SurpriseDrop\Model\Config\Source;

use Born\SurpriseDrop\Model\SurpriseDropFactory;
use Magento\Framework\Option\ArrayInterface;

/**
 * Class SurpriseDropOptions
 * Born\SurpriseDrop\Model\Config\Source
 */
class SurpriseDropOptions implements ArrayInterface
{
    /**
     * @var SurpriseDropFactory
     */
    private $surpriseDropFactory;

    /**
     * constructor
     * @param SurpriseDropFactory $surpriseDropFactory
     */
    public function __construct(
        SurpriseDropFactory $surpriseDropFactory
    ) {
        $this->surpriseDropFactory = $surpriseDropFactory;
    }

    public function toOptionArray()
    {
        $surpriseDropCollection = $this->surpriseDropFactory->create();
        $surpriseDropCollection = $surpriseDropCollection->getCollection();
        $surpriseDropArr = [];
        foreach ($surpriseDropCollection as $surpriseDrop)
        {
            $surpriseDropArr[] = [
                'value' => $surpriseDrop->getId(),
                'label' => $surpriseDrop->getTitle()
            ];
        }

        return $surpriseDropArr;
    }
}
