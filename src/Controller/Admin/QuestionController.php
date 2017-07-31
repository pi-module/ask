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
use Pi\Filter;
use Pi\Mvc\Controller\ActionController;
use Pi\Paginator\Paginator;
use Module\Ask\Form\UpdateForm;
use Module\Ask\Form\UpdateFilter;
use Zend\Json\Json;

class QuestionController extends ActionController
{
    public function indexAction()
    {
        // Get page
        $page = $this->params('page', 1);
        $module = $this->params('module');
        $status = $this->params('status');
        // Set info
        $offset = (int)($page - 1) * $this->config('admin_perpage');
        $order = array('time_create DESC', 'id DESC');
        $limit = intval($this->config('admin_perpage'));
        $question = array();
        // Set where
        $where = array();
        if (!empty($status)) {
            $where['status'] = $status;
        }
        // Set select
        $select = $this->getModel('question')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('question')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $question[$row->id] = Pi::api('question', 'ask')->canonizeQuestion($row);
            
        }
        // Set paginator
        $count = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
        $select = $this->getModel('question')->select()->where($where)->columns($count);
        $count = $this->getModel('question')->selectWith($select)->current()->count;
        $paginator = Paginator::factory(intval($count));
        $paginator->setItemCountPerPage($this->config('admin_perpage'));
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(array(
            'router'    => $this->getEvent()->getRouter(),
            'route'     => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params'    => array_filter(array(
                'module'        => $this->getModule(),
                'controller'    => 'question',
                'action'        => 'index',
                'status'        => $status,
            )),
        ));
        // Set view
        $this->view()->setTemplate('question-index');
        $this->view()->assign('questions', $question);
        $this->view()->assign('paginator', $paginator);
    }

    public function acceptAction()
    {
        // Get id and status
        $id = $this->params('id');
        $status = $this->params('status');
        $return = array();
        // set question
        $question = $this->getModel('question')->find($id);
        // Check
        if ($question && in_array($status, array(0, 1, 2, 3, 4, 5))) {
            // Accept
            $question->status = $status;
            // Save
            if ($question->save()) {
                $return['message'] = sprintf(__('%s question accept successfully'), $question->title);
                $return['ajaxstatus'] = 1;
                $return['id'] = $question->id;
                $return['questionstatus'] = $question->status;
            } else {
                $return['message'] = sprintf(__('Error in accept %s question'), $question->title);
                $return['ajaxstatus'] = 0;
                $return['id'] = 0;
                $return['questionstatus'] = $question->status;
            }
        } else {
            $return['message'] = __('Please select question');
            $return['ajaxstatus'] = 0;
            $return['id'] = 0;
            $return['questionstatus'] = 0;
        }
        return $return;
    }

    public function updateAction()
    {
        // Get id
        $id = $this->params('id');
        $module = $this->params('module');
        // find item
        $question = Pi::api('question', 'ask')->getQuestion($id);
        $form = new UpdateForm('question');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new UpdateFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Tag
                if (!empty($values['tag'])) {
                    $tag = explode('|', $values['tag']);
                    $values['tag'] = json::encode($tag);
                }
                // Set slug
                $slug = ($values['slug']) ? $values['slug'] : $values['title'];
                $slug = $slug . ' ' . $question['time_create'];
                $filter = new Filter\Slug;
                $values['slug'] = $filter($slug);
                // Set seo_title
                $title = ($values['seo_title']) ? $values['seo_title'] : $values['title'];
                $filter = new Filter\HeadTitle;
                $values['seo_title'] = $filter($title);
                // Set seo_keywords
                $keywords = ($values['seo_keywords']) ? $values['seo_keywords'] : $values['title'];
                $filter = new Filter\HeadKeywords;
                $filter->setOptions(array(
                    'force_replace_space' => true,
                ));
                $values['seo_keywords'] = $filter($keywords);
                // Set seo_description
                $description = ($values['seo_description']) ? $values['seo_description'] : $values['title'];
                $filter = new Filter\HeadDescription;
                $values['seo_description'] = $filter($description);
                // Set time
                $values['time_update'] = time();
                // Save values
                $row = $this->getModel('question')->find($values['id']);
                $row->assign($values);
                $row->save();
                // Tag
                if (isset($tag) && is_array($tag) && Pi::service('module')->isActive('tag')) {
                    Pi::service('tag')->update($module, $row->id, '', $tag);
                }
                // Check it save or not
                $message = __('Your selected item edit successfully');
                $url = array('', 'module' => $module, 'controller' => 'question', 'action' => 'index');
                $this->jump($url, $message);
            }
        } else {
            // Get tag list
            if (Pi::service('module')->isActive('tag')) {
                $tag = Pi::service('tag')->get($module, $question['id'], '');
                if (is_array($tag)) {
                    $question['tag'] = implode('|', $tag);
                }
            }
            // Set to form
            $form->setData($question);
        }
        // Set view
        $this->view()->setTemplate('question-update');
        $this->view()->assign('form', $form);
        $this->view()->assign('question', $question);
    }

    public function deleteAction()
    {
        // Get information
        $this->view()->setTemplate(false);
        $id = $this->params('id');
        $row = $this->getModel('question')->find($id);
        if ($row) {
            $row->delete();
            $this->jump(array('action' => 'index'), __('Your selected question deleted'));
        } else {
            $this->jump(array('action' => 'index'), __('Please select question'));	
        }	
    }
}