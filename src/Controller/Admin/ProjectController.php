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
namespace Module\Ask\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;

class ProjectController extends ActionController
{
    public function indexAction()
    {
        $this->view()->setTemplate('project-index');
    }

    public function updateAction()
    {
        $this->view()->setTemplate('project-update');
    }
}