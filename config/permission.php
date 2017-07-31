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
    // Front section
    'front' => array(
        'public'    => array(
            'title'         => _a('Global public resource'),
            'access'        => array(
                'guest',
                'member',
            ),
        ),
        'submit' => array(
            'title'         => _a('Submit'),
            'access'        => array(
                'member',
            ),
        ),
        'answer' => array(
            'title'         => _a('Answer'),
            'access'        => array(
                'member',
            ),
        ),
    ),
    // Admin section
    'admin' => array(
        'question'       => array(
            'title'         => _a('List of Questions'),
            'access'        => array(
                //'admin',
            ),
        ),
        'project'       => array(
            'title'         => _a('projects'),
            'access'        => array(
                //'admin',
            ),
        ),
        'tools'       => array(
            'title'         => _a('Tools'),
            'access'        => array(
                //'admin',
            ),
        ),
    ),
);