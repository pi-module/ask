<?php
/**
 * Answer form
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

class AnswerForm extends BaseForm
{
    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new AnswerFilter;
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
        // pid
        $this->add(array(
            'name' => 'pid',
            'attributes' => array(
                'type' => 'hidden',
            ),
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
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Submit'),
            )
        ));
    }
}