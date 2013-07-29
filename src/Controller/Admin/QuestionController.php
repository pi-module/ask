<?php
/**
 * Ask admin message controller
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Copyright (c) Pi Engine http://www.xoopsengine.org
 * @license         http://www.xoopsengine.org/license New BSD License
 * @author          Hossein Azizabadi <azizabadi@faragostaresh.com>
 * @since           3.0
 * @package         Module\Ask
 * @version         $Id$
 */

namespace Module\Ask\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;

class QuestionController extends ActionController
{
    public function indexAction()
    {
        // Get page
        $page = $this->params('p', 1);
        // Get info
        $order = array('create DESC', 'id DESC');
        $columns = array('id', 'type', 'answer', 'title', 'slug', 'status', 'create', 'author');
        $where = array('type' => 'Q');
        $offset = (int)($page - 1) * $this->config('admin_perpage');
        $limit = intval($this->config('admin_perpage'));
        // Set select
        $select = $this->getModel('question')->select()->columns($columns)->where($where)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('question')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $question[$row->id] = $row->toArray();
            $question[$row->id]['create'] = date('Y/m/d H:i:s', $question[$row->id]['create']);
            if ($question[$row->id]['type'] == 'Q') {
                $question[$row->id]['url'] = $this->url('.ask', array('module' => $this->params('module'), 'controller' => 'question', 'slug' => $question[$row->id]['slug']));
            }
        }
        // Set paginator
        $select = $this->getModel('question')->select()->columns(array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)')))->where($where);
        $count = $this->getModel('question')->selectWith($select)->current()->count;
        $paginator = \Pi\Paginator\Paginator::factory(intval($count));
        $paginator->setItemCountPerPage($this->config('admin_perpage'));
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(array(
            // Use router to build URL for each page
            'pageParam' => 'p',
            'totalParam' => 't',
            'router' => $this->getEvent()->getRouter(),
            'route' => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params' => array(
                'module' => $this->getModule(),
                'controller' => 'question',
                'action' => 'index',
            ),
        ));
        // Set view
        $this->view()->setTemplate('question_index');
        $this->view()->assign('questions', $question);
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('module', $this->params('module'));
    }
    
    public function acceptAction()
    {
        // Get id and status
        $id = $this->params('id');
        $status = $this->params('status');
        // set message
        $message = $this->getModel('question')->find($id);
        // Check
        if ($message && in_array($status, array(0, 1, 2, 3, 4, 5))) {
            // Accept
            $message->status = $status;
            // Save
            if ($message->save()) {
                $ajaxmessage = sprintf(__('question %s accept successfully'), $message->id);
                $ajaxstatus = 1;
            } else {
                $ajaxmessage = sprintf(__('Error in accept question %s'), $message->id);
                $ajaxstatus = 0;
            }
        } else {
            $ajaxmessage = __('Please select message');
            $ajaxstatus = 0;
        }

        return array(
            'status' => $ajaxstatus,
            'message' => $ajaxmessage,
        );
    }

    public function deleteAction()
    {
        /*
           * not completed and need confirm option
           */
        // Get information
        $this->view()->setTemplate(false);
        $id = $this->params('id');
        $row = $this->getModel('question')->find($id);
        if ($row) {
            // Remove content
            $row->delete();
            $this->jump(array('action' => 'index'), __('Your selected question deleted'));
        } else {
            $this->jump(array('action' => 'index'), __('Please select question'));	
        }	
    }
}