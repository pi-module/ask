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
use Module\Ask\Form\AskForm;
use Module\Ask\Form\AskFilter;
use Zend\Json\Json;

class SubmitController extends ActionController
{
    protected $questionColumns = array(
        'id', 'type', 'pid', 'answer', 'uid', 'point', 'count', 'favorite', 'hits', 'status',
        'time_create', 'time_update', 'title', 'slug', 'content', 'tags', 'seo_title',
        'seo_keywords','seo_description'
    );

    public function indexAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Set form
        $form = new AskForm('Ask');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new AskFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Set just category fields
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->questionColumns)) {
                        unset($values[$key]);
                    }
                }
                // Set seo_title
                $values['slug'] = Pi::api('text', 'ask')->slug($values['title'] . ' ' . _date());
                $values['seo_title'] = Pi::api('text', 'ask')->title($values['title']);
                $values['seo_keywords'] = Pi::api('text', 'ask')->keywords($values['title']);
                $values['seo_description'] = Pi::api('text', 'ask')->description($values['title']);
                $values['time_create'] = time();
                $values['time_update'] = time();
                $values['uid'] = Pi::user()->getId();
                $values['status'] = $this->config('auto_approval');
                $values['type'] = 'Q';
                // Save values
                $row = $this->getModel('question')->createRow();
                $row->assign($values);
                $row->save();
                // Check it save or not
                if ($this->config('auto_approval')) {
                    $message = __('Your ask new question successfully, Other users can view and answer it');                 
                } else {
                    $message = __('Your ask new question successfully, But it need review and publish by website admin');                  
                }
                $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
                $this->jump($url, $message);
            }
        }
        // Set header
        $title = __('Ask a new Question');
        $seo_title = Pi::api('text', 'ask')->title($title);
        $seo_keywords = Pi::api('text', 'ask')->keywords($title);
        $seo_description = Pi::api('text', 'ask')->description($title);
        // Set view
        $this->view()->headTitle($seo_title);
        $this->view()->headDescription($seo_keywords, 'set');
        $this->view()->headKeywords($seo_description, 'set');
        $this->view()->setTemplate('submit_index');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', $title);
        $this->view()->assign('message', $message);
        $this->view()->assign('class', $class);
    }

    public function searchAction()
    {
        /* if ($this->request->isPost()) {
            $params = $this->params()->fromPost();
            if (isset($params['key']) && !empty($params['key'])) {
                // Get info
                $order = array('point DESC', 'id DESC');
                $columns = array('id', 'title', 'slug');
                $where = array('status' => 1, 'type' => 'Q', 'title LIKE ?' => $params['key'] . '%');
                // Set select
                $select = $this->getModel('question')->select()->columns($columns)->where($where)->order($order)->limit(10);
                $rowset = $this->getModel('question')->selectWith($select);
                foreach ($rowset as $row) {
                    $question[$row->id] = $row->toArray();
                    $result[] = $question[$row->id]['title'];
                }
                // return result
                echo Json::encode($result);
            }
        } */
    }
}