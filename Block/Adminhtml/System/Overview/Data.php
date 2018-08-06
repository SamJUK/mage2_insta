<?php

namespace SamJ\Instagram\Block\Adminhtml\System\Overview;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use SamJ\Instagram\Model\Instagram;

class Data extends AbstractFieldArray
{
    /** @var Instagram */
    private $instagram;


    /**
     * Data constructor.
     * @param Instagram $instagram
     * @param Context $context
     * @param array $data
     */
    public function __construct(Instagram $instagram, Context $context, array $data = [])
    {
        $this->instagram = $instagram;
        parent::__construct($context, $data);
    }


    /**
     * We need to create atleast 1 column otherwise we get an error :)
     */
    protected function _construct(){
        $this->addColumn('blank', ['label' => __('blank')]);
        parent::_construct();
    }


    /**
     * Instead of rendering the inner contents render our own stuff
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @param string $html
     * @return string
     */
    protected function _decorateRowHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element, $html)
    {
        return '<tr id="row_' . $element->getHtmlId() . '">' . $this->getInformationHtml() . '</tr>';
    }

    /**
     * Build the HTML for our custom information row
     * @return string
     */
    private function getInformationHtml()
    {
        $html = '';
        $html .= $this->getAuthButtonHtml();
        $html .= $this->getStatusHtml();
        return $html;
    }


    /**
     * Build the HTML for our authorize button
     *
     * @return string
     */
    private function getAuthButtonHtml()
    {
        $template = '
            <a style="white-space: nowrap;" href="%1$s">
                <button id="save" title="Save Config" type="button" class="action-default scalable save primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button">
                <span class="ui-button-text"><span>Reauthorize</span></span>
                </button>
            </a>';

        return sprintf( $template, $this->instagram->getAuthUrl() );
    }


    /**
     * Get the HTML for our access token status indicator
     *
     * @return string
     */
    private function getStatusHtml()
    {
        $validity = $this->instagram->isAccessTokenValid()
            ? '<span style="color:green;">Valid</span>'
            : '<span style="color:red;">Invalid</span>';

        $template = ' <strong>Access Token Status: </strong> %s ';
        return sprintf( $template, $validity );
    }

}