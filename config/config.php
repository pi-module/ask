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
            'title'  => _a('Admin'),
            'name'   => 'admin'
        ),
        array(
            'title'  => _a('Show'),
            'name'   => 'show'
        ),
        array(
            'title'  => _a('Question'),
            'name'   => 'question'
        ),
        array(
            'title'  => _a('Feed'),
            'name'   => 'feed'
        ),
        array(
            'title'  => _a('Vote'),
            'name'   => 'vote'
        ),
    ),
    'item' => array(
        // Admin
        'admin_perpage' => array(
            'category'     => 'admin',
            'title'        => _a('Perpage'),
            'description'  => '',
            'edit'         => 'text',
            'filter'       => 'number_int',
            'value'        => 50
        ),
        // Show
        'show_perpage' => array(
            'category'     => 'show',
            'title'        => _a('Perpage'),
            'description'  => _a('Number of questions in each page'),
            'edit'         => 'text',
            'filter'       => 'number_int',
            'value'        => 10
        ),
        'show_info' => array(
            'category'     => 'show',
            'title'        => _a('Show information'),
            'description'  => '',
            'edit'         => 'checkbox',
            'filter'       => 'number_int',
            'value'        => 1
        ),
        'show_tags' => array(
            'category'     => 'show',
            'title'        => _a('Tags'),
            'description'  => _a('Number of tags in tag controller'),
            'edit'         => 'text',
            'filter'       => 'number_int',
            'value'        => 50
        ),
        'view_breadcrumbs' => array(
            'category'     => 'show',
            'title'        => _a('Show breadcrumbs'),
            'description'  => '',
            'edit'         => 'checkbox',
            'filter'       => 'number_int',
            'value'        => 1
        ),
        // Question
        'question_ask' => array(
            'category'     => 'question',
            'title'        => _a('Can ask'),
            'description'  => '',
            'edit'         => 'checkbox',
            'filter'       => 'number_int',
            'value'        => 1
        ),
        'question_answer' => array(
            'category'     => 'question',
            'title'        => _a('Can answer'),
            'description'  => '',
            'edit'         => 'checkbox',
            'filter'       => 'number_int',
            'value'        => 1
        ),
        'auto_approval' => array(
            'title'        => _a('Automatic approval'),
            'description'  => '',
            'edit'         => array(
                'type'     => 'select',
                'options'     => array(
                    'options' => array(
                        0     => _a('All questions and answers need admin review before publish'),
                        1     => _a('Automatic approval all questions and answers'),
                    ),
                ),
            ),
            'filter'       => 'number_int',
            'value'        => 1,
            'category'     => 'question',
        ),
        // Feed 
        'feed_icon' => array(
            'category'     => 'feed',
            'title'        => _a('Show feed icon'),
            'description'  => '',
            'edit'         => 'checkbox',
            'filter'       => 'number_int',
            'value'        => 1
        ),
        'feed_num' => array(
            'category'     => 'feed',
            'title'        => _a('Feed number'),
            'description'  => '',
            'edit'         => 'text',
            'filter'       => 'number_int',
            'value'        => 10
        ),
        // Vote
        'vote_bar' => array(
            'category'     => 'vote',
            'title'        => _a('Use vote system'),
            'description'  => '',
            'edit'         => 'checkbox',
            'filter'       => 'number_int',
            'value'        => 1
        ),
    ),
);