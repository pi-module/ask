<?php
/**
 * Ask module config
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

return array(
    'category' => array(
        array(
            'title' => __('Admin'),
            'name' => 'admin'
        ),
        array(
            'title' => __('Show'),
            'name' => 'show'
        ),
        array(
            'title' => __('Feed'),
            'name' => 'feed'
        ),
        array(
            'title' => __('Vote'),
            'name' => 'vote'
        ),
    ),
    'item' => array(
        // Admin
        'admin_perpage' => array(
            'category' => 'admin',
            'title' => __('Perpage'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 25
        ),
        // Show
        'show_perpage' => array(
            'category' => 'show',
            'title' => __('Perpage'),
            'description' => __('Number of question in each page'),
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 10
        ),
        'show_index' => array(
            'category' => 'show',
            'title' => __('Perpage'),
            'description' => __('Number of last question for show in index controller'),
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 50
        ),
        'show_tags' => array(
            'category' => 'show',
            'title' => __('Tags'),
            'description' => __('Number of tags in tag controller'),
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 50
        ),
        // Feed 
        'feed_icon' => array(
            'category' => 'feed',
            'title' => __('Show feed icon'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'feed_num' => array(
            'category' => 'feed',
            'title' => __('Feed number'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 10
        ),
        // Vote
        'vote_bar' => array(
            'category' => 'vote',
            'title' => __('Use vote system'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'vote_type' => array(
            'category' => 'vote',
            'title' => __('VoteBar type'),
            'description' => '',
            'filter' => 'string',
            'value' => 'plus',
            'edit' => array(
                'type' => 'select',
                'options' => array(
                    'options' => array(
                        'plus' => __('Plus'),
                        'star' => __('Star'),
                    ),
                ),
            ),
        ),
    ),
);