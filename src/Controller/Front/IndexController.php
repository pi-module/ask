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
namespace Module\Ask\Controller\Front;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Pi\Paginator\Paginator;
use Laminas\Db\Sql\Predicate\Expression;

class IndexController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Check
        if ($config['project_active']) {
            $projects = Pi::api('project', 'ask')->getProjectList();
            // Set view
            $this->view()->setTemplate('project-list');
            $this->view()->assign('projects', $projects);
            $this->view()->assign('config', $config);
            $this->view()->assign('title', __('List of all projects'));
        } else {
            // Set question info
            $where = array('status' => 1, 'type' => 'Q');
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
            $this->view()->assign('config', $config);
            $this->view()->assign('title', __('List of all questions'));
        }
    }

    public function askList($where)
    {
        // Set info
        $question = array();
        $page = $this->params('page', 1);
        $module = $this->params('module');
        $order = $this->params('order', 'create');
        $offset = (int)($page - 1) * $this->config('show_perpage');
        $limit = intval($this->config('show_perpage'));
        $order = $this->setOrder($order);
        // Get list of question
        $select = $this->getModel('question')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('question')->selectWith($select);
        foreach ($rowset as $row) {
            $question[$row->id] = Pi::api('question', 'ask')->canonizeQuestion($row);
        }
        // return question
        return $question;   
    }

    public function askPaginator($template, $where)
    {
        $template['module'] = $this->params('module');
        //$template['order'] = $this->params('order');
        $template['page'] = $this->params('page', 1);
        $template['slug'] = $this->params('slug');
        // get count     
        $columns = array('count' => new Expression('count(*)'));
        $select = $this->getModel('question')->select()->where($where)->columns($columns);
        $template['count'] = $this->getModel('question')->selectWith($select)->current()->count;
        // paginator
        $paginator = Paginator::factory(intval($template['count']));
        $paginator->setItemCountPerPage(intval($this->config('show_perpage')));
        $paginator->setCurrentPageNumber(intval($template['page']));
        $paginator->setUrlOptions(array(
            'router'    => $this->getEvent()->getRouter(),
            'route'     => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params'    => array_filter(array(
                'module'        => $this->getModule(),
                'controller'    => $template['controller'],
                'action'        => $template['action'],
                //'order'          => $template['order'],
                'slug'          => urlencode($template['slug']),
            )),
        ));
        return $paginator;
    }

    public function setOrder($order = 'create')
    {
        // Set order ', '', '', '
        switch ($order) {
            case 'answer':
                $order = array('answer DESC', 'id DESC');
                break;

            case 'hits':
                $order = array('hits DESC', 'id DESC');
                break;     
                
            case 'point':
                $order = array('point DESC', 'id DESC');
                break; 

            case 'create':
            default:
                $order = array('time_create DESC', 'id DESC');
                break;
        } 
        return $order;
    }
}