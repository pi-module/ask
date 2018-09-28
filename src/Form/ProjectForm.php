<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Ask\Form;

use Pi;
use Pi\Form\Form as BaseForm;

class ProjectForm extends BaseForm
{
    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new ProjectFilter;
        }
        return $this->filter;
    }

    public function init()
    {
        // id
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        // title
        $this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => __('Title'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // slug
        $this->add(array(
            'name' => 'slug',
            'options' => array(
                'label' => __('slug'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => __('Used on URL value : must be unique, short, and user oriented'),
            )
        ));
        // text_description
        $this->add(array(
            'name' => 'text_description',
            'options' => array(
                'label' => __('Description'),
                'editor' => 'html',
                'set' => '',
            ),
            'attributes' => array(
                'type' => 'editor',
                'description' => '',
            )
        ));
        // main_image
        $this->add(array(
            'name' => 'main_image',
            'type' => 'Module\Media\Form\Element\Media',
            'options' => array(
                'label' => __('Main image'),
                'media_gallery' => false,
                'media_season' => false,
                'media_season_recommended' => false,
                'is_freemium' => true,
                'can_connect_lists' => false,
            ),
        ));
        // additional_images
        $this->add(array(
            'name' => 'additional_images',
            'type' => 'Module\Media\Form\Element\Media',
            'options' => array(
                'label' => __('Additional images'),
                'media_gallery' => true,
                'media_season' => false,
                'media_season_recommended' => false,
                'is_freemium' => true,
                'can_connect_lists' => false,
            ),
        ));
        // status
        $this->add(array(
            'name' => 'status',
            'type' => 'select',
            'options' => array(
                'label' => __('Status'),
                'value_options' => array(
                    1 => __('Published'),
                    2 => __('Pending review'),
                    3 => __('Draft'),
                    4 => __('Private'),
                    5 => __('Remove'),
                ),
            ),
            'attributes' => array(
                'required' => true,
            ),
        ));
        // manager
        $this->add(array(
            'name' => 'manager',
            'options' => array(
                'label' => __('Manager'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => __('Project manager user id'),
            )
        ));
        // extra_seo
        $this->add(array(
            'name' => 'extra_seo',
            'type' => 'fieldset',
            'options' => array(
                'label' => __('SEO options'),
            ),
        ));
        // seo_title
        $this->add(array(
            'name' => 'seo_title',
            'options' => array(
                'label' => __('Meta Title'),
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => '2',
                'cols' => '40',
                'description' => __('Displayed in search Engine result pages as Title : between 10 to 70 character. If empty, will be popuated automaticaly by main title value'),
            )
        ));
        // seo_keywords
        $this->add(array(
            'name' => 'seo_keywords',
            'options' => array(
                'label' => __('Meta Keywords'),
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => '2',
                'cols' => '40',
                'description' => __('Not used anymore by search engines : between 5 to 12 words / left it empty, it will be automaticaly populated with main title values'),
            )
        ));
        // seo_description
        $this->add(array(
            'name' => 'seo_description',
            'options' => array(
                'label' => __('Meta Description'),
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => '3',
                'cols' => '40',
                'description' => __('Displayed in search Engine result pages : quick summary to incitate user to click, between 80 to 160 character'),
            )
        ));
        // Save
        $this->add(array(
            'name'          => 'submit',
            'type'          => 'submit',
            'attributes'    => array(
                'value' => __('Submit'),
            )
        ));
    }
}