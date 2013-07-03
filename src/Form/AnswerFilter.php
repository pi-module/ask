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
use Zend\InputFilter\InputFilter;

class AnswerFilter extends InputFilter
{
    public function __construct()
    {
        // id
        $this->add(array(
            'name' => 'id',
            'required' => false,
        ));
        // pid
        $this->add(array(
            'name' => 'pid',
            'required' => true,
        ));
        // content
        $this->add(array(
            'name' => 'content',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
    }
}    	