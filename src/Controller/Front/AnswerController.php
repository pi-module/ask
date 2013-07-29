<?php
/**
 * Ask answer controller
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
use Module\Ask\Form\AnswerForm;
use Module\Ask\Form\AnswerFilter;

class AnswerController extends ActionController
{
    protected $questionColumns = array('id', 'type', 'pid', 'answer', 'author', 'point', 'count', 'hits',
        'status', 'create', 'update', 'title', 'slug', 'content');

    public function indexAction()
    {

        $questionId = $this->params('question');
        if (!isset($questionId) || !$questionId) {
            $message = __('Please select a question');
            return $this->jump(array('route' => '.ask', 'module' => $this->params('module'), 'controller' => 'index'), $message);
        }
        // Set question
        $question = $this->getModel('question')->find($questionId)->toArray();
        $question['content'] = Pi::service('markup')->render($question['content'], 'html', 'markdown');
        // get info
        $form = new AnswerForm('Answer');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new AnswerFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
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
                $values['type'] = 'A';
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('question')->find($values['id']);
                } else {
                    $row = $this->getModel('question')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Check it save or not
                if ($row->id) {
                    // update answer count
                    $this->getModel('question')->update(array('answer' => $question['answer'] + 1), array('id' => $question['id']));
                    // set message
                    $message = __('Your selected question edit successfully.');
                    $class = 'alert-success';
                    $this->jump(array('route' => '.ask', 'module' => $params['module'], 'controller' => 'question', 'slug' => $question['slug']), $message);
                } else {
                    $message = __('Story data not saved.');
                    $class = 'alert-error';
                }
            } else {
                $message = __('Invalid data, please check and re-submit.');
                $class = 'alert-error';
            }
        } else {
            $values['pid'] = $question['id'];
            $form->setData($values);
            $message = '';
            $this->view()->assign('content', $question['content']);
            ;
        }
        $this->view()->assign('form', $form);
        $this->view()->assign('title', sprintf(__('Answer to %s'), $question['title']));
        $this->view()->assign('message', $message);
        $this->view()->setTemplate('answer_index');
    }
}