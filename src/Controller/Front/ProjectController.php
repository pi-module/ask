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

namespace Module\Ask\Controller\Front;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Pi\Paginator\Paginator;
use Laminas\Db\Sql\Predicate\Expression;

class ProjectController extends IndexController
{
    public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');
        $slug = $this->params('slug');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Get user id
        $uid = Pi::user()->getId();
        // Get topic information from model
        $project = Pi::api('project', 'ask')->getProject($slug, 'slug');
        // Check slug set
        if (empty($project) || $project['status'] != 1) {
            $this->getResponse()->setStatusCode(404);
            $this->terminate(__('Project not set.'), '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Set question info
        $where = array('status' => 1, 'type' => 'Q', 'project_id' => $project['id']);
        // check is project manager
        $userIsManager = false;
        if ($project['manager'] == $uid && $config['auto_approval'] == 2) {
            $userIsManager = true;
            $where['status'] = array(1, 2);
        }
        // Set paginator info
        $template = array(
            'controller' => 'index',
            'action'     => 'index',
        );
        // Get question List
        $questions = $this->askList($where);
        // Get paginator
        $paginator = $this->askPaginator($template, $where);
        // Set view
        $this->view()->setTemplate('question-list');
        $this->view()->assign('questions', $questions);
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('project', $project);
        $this->view()->assign('config', $config);
        $this->view()->assign('userIsManager', $userIsManager);
        $this->view()->assign('title', __('List of all questions'));
    }
}