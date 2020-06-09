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
    // Admin section
    'admin' => array(
        array(
            'title'         => _a('List of Questions'),
            'controller'    => 'question',
            'permission'    => 'question',
        ),
        array(
            'title'         => _a('projects'),
            'controller'    => 'project',
            'permission'    => 'project',
        ),
    ),
    // Front section
    'front' => array(
        array(
            'title'         => _a('Index page'),
            'controller'    => 'index',
            'permission'    => 'public',
            'block'         => 1,
        ),
        array(
            'title'         => _a('Project page'),
            'controller'    => 'project',
            'permission'    => 'public',
            'block'         => 1,
        ),
        array(
            'title'         => _a('Answer'),
            'controller'    => 'answer',
            'permission'    => 'answer',
            'block'         => 1,
        ),
        array(
            'title'         => _a('Question'),
            'controller'    => 'question',
            'permission'    => 'public',
            'block'         => 1,
        ),
        array(
            'title'         => _a('Submit question'),
            'controller'    => 'submit',
            'permission'    => 'submit',
            'block'         => 1,
        ),
        array(
            'title'         => _a('Tag'),
            'controller'    => 'tag',
            'permission'    => 'public',
            'block'         => 1,
        ),
    ),
);