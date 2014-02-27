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
use Zend\Db\Sql\Predicate\Expression;
use Zend\Json\Json;

class IndexController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Set product info
        $where = array('status' => 1, 'type' => 'Q');
        // Set paginator info
        $template = array(
            'controller' => 'index',
            'action' => 'index',
        );
        // Get product List
        $questions = $this->askList($where);
        // Get paginator
        $paginator = $this->askPaginator($template, $where);
        // Set order link
        $orderLink = array();
        $orderLink['answer'] = $this->url('', array(
            'module'      => $module, 
            'controller'  => 'index', 
            'action'      => 'index', 
            'order'        => 'answer'
        ));
        $orderLink['hits'] = $this->url('', array(
            'module'      => $module, 
            'controller'  => 'index', 
            'action'      => 'index', 
            'order'        => 'hits'
        ));
        $orderLink['point'] = $this->url('', array(
            'module'      => $module, 
            'controller'  => 'index', 
            'action'      => 'index', 
            'order'        => 'point'
        ));
        $orderLink['create'] = $this->url('', array(
            'module'      => $module, 
            'controller'  => 'index', 
            'action'      => 'index', 
            'order'        => 'create'
        ));
        $orderLink['active'] = $this->params('order', 'create');
        // Set view
        $this->view()->headTitle(__('Ask index seo title'));
        $this->view()->headDescription(__('ask index seo description'), 'set');
        $this->view()->headKeywords(__('ask index seo keywords'), 'set');
        $this->view()->setTemplate('question_list');
        $this->view()->assign('questions', $questions);
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('config', $config);
        $this->view()->assign('orderLink', $orderLink);   
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
            $question[$row->id] = $row->toArray();
            $question[$row->id]['time_create'] = _date($question[$row->id]['time_create']);
            $question[$row->id]['tags'] = Json::decode($question[$row->id]['tags']);
            $question[$row->id]['url'] = $this->url('', array('module' => $module, 'controller' => 'question', 'slug' => $question[$row->id]['slug']));
        }
        // return product
        return $question;   
    }

    public function askPaginator($template, $where)
    {
        $template['module'] = $this->params('module');
        $template['order'] = $this->params('order');
        $template['page'] = $this->params('page', 1);
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
                'order'          => $template['order'],
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