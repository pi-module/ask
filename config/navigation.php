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
    'admin' => array(
        'question' => array(
            'label'         => _a('List of Questions'),
            'permission'    => array(
                'resource'  => 'question',
            ),
            'route'         => 'admin',
            'module'        => 'ask',
            'controller'    => 'question',
            'action'        => 'index',
            'params'        => array(
                'type'    => 'all',
            ),
            'pages' => array(
                'all' => array(
                    'label'         => _a('List of all Questions and Answers'),
                    'permission'    => array(
                        'resource'  => 'question',
                    ),
                    'route'         => 'admin',
                    'module'        => 'ask',
                    'controller'    => 'question',
                    'action'        => 'index',
                    'params'        => array(
                        'type'    => 'all',
                    ),
                ),
                'question' => array(
                    'label'         => _a('List of just Questions'),
                    'permission'    => array(
                        'resource'  => 'question',
                    ),
                    'route'         => 'admin',
                    'module'        => 'ask',
                    'controller'    => 'question',
                    'action'        => 'index',
                    'params'        => array(
                        'type'    => 'question',
                    ),
                ),
                'answer' => array(
                    'label'         => _a('List of just Answers'),
                    'permission'    => array(
                        'resource'  => 'question',
                    ),
                    'route'         => 'admin',
                    'module'        => 'ask',
                    'controller'    => 'question',
                    'action'        => 'index',
                    'params'        => array(
                        'type'    => 'answer',
                    ),
                ),
                'update' => array(
                    'label'         => _a('New question'),
                    'permission'    => array(
                        'resource'  => 'question',
                    ),
                    'route'         => 'admin',
                    'module'        => 'ask',
                    'controller'    => 'question',
                    'action'        => 'update',
                ),
            ),
        ),
        'project' => array(
            'label'         => _a('projects'),
            'permission'    => array(
                'resource'  => 'project',
            ),
            'route'         => 'admin',
            'module'        => 'ask',
            'controller'    => 'project',
            'action'        => 'index',
            'pages' => array(
                'project' => array(
                    'label'         => _a('projects'),
                    'permission'    => array(
                        'resource'  => 'project',
                    ),
                    'route'         => 'admin',
                    'module'        => 'ask',
                    'controller'    => 'project',
                    'action'        => 'index',
                ),
                'update' => array(
                    'label'         => _a('New project'),
                    'permission'    => array(
                        'resource'  => 'project',
                    ),
                    'route'         => 'admin',
                    'module'        => 'ask',
                    'controller'    => 'project',
                    'action'        => 'update',
                ),
            ),
        ),
    ),
);