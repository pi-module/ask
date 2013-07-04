<?php
/**
 * Ask index controller
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

namespace Module\Ask\Controller\Front;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Module\Ask\Form\AskForm;
use Module\Ask\Form\AskFilter;
use Zend\Json\Json;

class SubmitController extends ActionController
{
    protected $questionColumns = array('id', 'type', 'pid', 'answer', 'author', 'point', 'count', 'hits',
        'status', 'create', 'update', 'title', 'alias', 'content', 'tags');

    public function indexAction()
    {
        // get info
        $params = $this->params()->fromRoute();
        $form = new AskForm('Ask');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new AskFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Tag
                $tag = explode(' ', $values['tag']);
                $values['tags'] = Json::encode($tag);
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->questionColumns)) {
                        unset($values[$key]);
                    }
                }
                // Set time
                if (empty($values['id'])) {
                    $values['create'] = $values['update'] = time();
                } else {
                    $values['update'] = time();
                }
                // Set user
                if (empty($values['id'])) {
                    $values['author'] = Pi::registry('user')->id;
                }
                // Set status
                $values['status'] = 1;
                // Set type
                $values['type'] = 'Q';
                // Set alias
                $values['alias'] = Pi::service('api')->ask(array('Text', 'alias'), $values['title'], $values['id'], $this->getModel('question'));
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('question')->find($values['id']);
                } else {
                    $row = $this->getModel('question')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Tag
                if (is_array($tag) && Pi::service('module')->isActive('tag')) {
                    if (empty($values['id'])) {
                        Pi::service('tag')->add($params['module'], $row->id, '', $tag);
                    } else {
                        Pi::service('tag')->update($params['module'], $row->id, '', $tag);
                    }
                }
                // Check it save or not
                if ($row->id) {
                    $message = __('Your selected question edit successfully.');
                    $class = 'alert-success';
                    $this->jump(array('route' => '.ask', 'module' => $params['module'], 'controller' => 'index'), $message);
                } else {
                    $message = __('Story data not saved.');
                    $class = 'alert-error';
                }
            } else {
                $message = __('Invalid data, please check and re-submit.');
                $class = 'alert-error';
            }
        } else {
            if (!empty($params['alias'])) {
                $values = $this->getModel('question')->find($params['alias'], 'alias')->toArray();
                $values['tag'] = implode(' ', Pi::service('tag')->get($params['module'], $values['id'], ''));
                $form->setData($values);
                $message = 'You can edit this question';
            } else {
                $message = 'You can add new question';
            }
            $class = 'alert-info';
        }
        $this->view()->setTemplate('submit_index');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Ask a Question'));
        $this->view()->assign('message', $message);
        $this->view()->assign('class', $class);
    }

    public function searchAction()
    {
        if ($this->request->isPost()) {
            $params = $this->params()->fromPost();
            if (isset($params['key']) && !empty($params['key'])) {
                // Get info
                $order = array('point DESC', 'id DESC');
                $columns = array('id', 'title', 'alias');
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
        }
    }
}