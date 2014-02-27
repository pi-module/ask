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
    // route name
    'ask'  => array(
        'name'      => 'ask',
        'type'      => 'Module\Ask\Route\Ask',
        'options'   => array(
            'route'     => '/ask',
            'defaults'  => array(
                'module'        => 'ask',
                'controller'    => 'index',
                'action'        => 'index'
            )
        ),
    )
);