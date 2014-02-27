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

return array(
    'category' => array(
        array(
            'title' => __('Admin'),
            'name' => 'admin'
        ),
        array(
            'title' => __('Front'),
            'name' => 'front'
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
            'category' => 'front',
            'title' => __('Perpage'),
            'description' => __('Number of questions in each page'),
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 10
        ),
        'show_tags' => array(
            'category' => 'front',
            'title' => __('Tags'),
            'description' => __('Number of tags in tag controller'),
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 50
        ),
        'auto_approval' => array(
            'title' => __('Automatic approval'),
            'description' => '',
            'edit' => array(
                'type' => 'select',
                'options' => array(
                    'options' => array(
                        0 => __('All questions and answers need admin review before publish'),
                        1 => __('Automatic approval all questions and answers'),
                    ),
                ),
            ),
            'filter' => 'number_int',
            'value' => 1,
            'category' => 'front',
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
    ),
);