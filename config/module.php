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
    'meta'  => array(
        'title'         => _a('Ask'),
        'description'   => _a('Ask and Answer'),
        'version'       => '0.2.7',
        'license'       => 'New BSD',
        'logo'          => 'image/logo.png',
        'readme'        => 'docs/readme.txt',
        'demo'          => 'http://pialog',
        'icon'          => 'fa-database',
        'clonable'      => true,
    ),
    // Author information
    'author'    => array(
        'dev'           => 'Hossein Azizabadi',
        'email'         => 'azizabadi@faragostaresh.com',
        'architect'     => '@voltan',
        'design'        => '@voltan'
    ),
    // Resource
    'resource'      => array(
        'database'      => 'database.php',
        'config'        => 'config.php',
        'permission'    => 'permission.php',
        'page'          => 'page.php',
        'navigation'    => 'navigation.php',
        'route'         => 'route.php',
    )
);