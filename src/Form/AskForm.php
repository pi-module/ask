<?php
/**
 * Ask form
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Copyright (c) Pi Engine http://www.xoopsengine.org
 * @license         http://www.xoopsengine.org/license New BSD License
 * @author          Hossein Azizabadi <azizabadi@faragostaresh.com>
 * @since           3.0
 * @package         Module\Ask
 * @version         $Id$
 */

namespace Module\Ask\Form;

use Pi;
use Pi\Form\Form as BaseForm;

class AskForm extends BaseForm
{
    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new AskFilter;
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
        // id
        $this->add(array(
            'name' => 'user_id',
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
                'description' => __('Input title form and ajax search for find same quetions'),
                'class' => 'span9 title',
                'id' => 'searchinput',
                'autocomplete' => 'off',
                'data-provide' => 'typeahead',
                'data-items' => '10',
                'data-source' => '',
            )
        ));
        // content	  
        $this->add(array(
            'name' => 'content',
            'options' => array(
                'label' => __('Content'),
                'editor' => 'markitup',
                'set' => 'markdown',
            ),
            'attributes' => array(
                'type' => 'editor',
                'description' => '',
                'class' => 'textarea-large',
            )
        ));
        // tag
        if (Pi::service('module')->isActive('tag')) {
            $this->add(array(
                'name' => 'tag',
                'options' => array(
                    'label' => __('Tags'),
                ),
                'attributes' => array(
                    'type' => 'text',
                    'description' => '',
                )
            ));
        }
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Submit'),
                'class' => 'check btn',
            )
        ));
    }
}