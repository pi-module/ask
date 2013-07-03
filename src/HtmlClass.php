<?php
/**
 * Ask html class
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

namespace Module\Ask;

use Pi;

class HtmlClass
{
    public static function TabClass($order)
    {
        $class = array('create' => '', 'vote' => '', 'hits' => '', 'answer' => '', 'tag' => '');
        switch ($order) {
            case 'create':
                $class['create'] = ' class="active"';
                break;

            case 'point':
                $class['vote'] = ' class="active"';
                break;

            case 'hits':
                $class['hits'] = ' class="active"';
                break;

            case 'answer':
                $class['answer'] = ' class="active"';
                break;
                
            case 'tag':
                $class['tag'] = ' class="active"';
                break;    
        }
        return $class;
    }

    public static function TabLabel($number)
    {
        if ($number == 0) {
            $class = 'label label-important';
        } elseif ($number > 0 && $number < 10) {
            $class = 'label label-warning';
        } elseif ($number >= 10 && $number < 50) {
            $class = 'label label-info';
        } elseif ($number >= 50) {
            $class = 'label label-success';
        } else {
            $class = 'label';
        }
        return $class;
    }
}	