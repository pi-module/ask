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
        'question' => array(
            'label'         => _a('List of Questions'),
            'permission'    => array(
                'resource'  => 'question',
            ),
            'route'         => 'admin',
            'module'        => 'ask',
            'controller'    => 'question',
            'action'        => 'index',
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
        'tools' => array(
            'label'         => _a('Tools'),
            'permission'    => array(
                'resource'  => 'tools',
            ),
            'route'         => 'admin',
            'module'        => 'ask',
            'controller'    => 'tools',
            'action'        => 'index',
        ),
    ),
);