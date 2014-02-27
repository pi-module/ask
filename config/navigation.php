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
    'admin' => array(
        'last' => array(
            'label'         => __('List of Question'),
            'route'         => 'admin',
            'module'        => 'ask',
            'controller'    => 'question',
            'action'        => 'index',
        ),
        'tools' => array(
            'label'         => __('Tools'),
            'route'         => 'admin',
            'module'        => 'ask',
            'controller'    => 'tools',
            'action'        => 'index',
        ),
    ),
);