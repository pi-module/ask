<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
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
    ),
);