<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Ask\Form;

use Pi;
use Pi\Form\Form as BaseForm;

class UpdateForm extends BaseForm
{
    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new UpdateFilter;
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
        // content
        $this->add(array(
            'name' => 'content',
            'options' => array(
                'label' => __('Question'),
                'editor' => 'html',
                'set' => '',
            ),
            'attributes' => array(
                'type' => 'editor',
                'description' => '',
            )
        ));
        // tag
        if (Pi::service('module')->isActive('tag')) {
            $this->add(array(
                'name' => 'tag',
                'type' => 'tag',
                'options' => array(
                    'label' => __('Tags'),
                ),
                'attributes' => array(
                    'id'          => 'tag',
                    'description' => __('Use `|` as delimiter to separate tag terms'),
                )
            ));
        }
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