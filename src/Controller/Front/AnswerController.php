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
use Module\Ask\Form\AnswerForm;
use Module\Ask\Form\AnswerFilter;

class AnswerController extends ActionController
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
        // Get info from url
        $slug = $this->params('slug');
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Find story
        $question = $this->getModel('question')->find($slug, 'slug')->toArray();
        // Check page
        if (!$question || $question['status'] != 1) {
            $message = __('The question not found.');
            $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
            $this->jump($url, $message);
        }
        // Set date
        $question['create'] = _date($question['create']);
        // Set markup
        $question['content'] = Pi::service('markup')->render($question['content'], 'html', 'text');
        // get info
        $form = new AnswerForm('Answer');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new AnswerFilter);
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
                $values['title'] = sprintf(__('Answer to %s'), $question['title']);
                $values['slug'] = Pi::api('text', 'ask')->slug($values['title'] . ' ' . _date());
                $values['seo_title'] = Pi::api('text', 'ask')->title($values['title']);
                $values['seo_keywords'] = Pi::api('text', 'ask')->keywords($values['title']);
                $values['seo_description'] = Pi::api('text', 'ask')->description($values['title']);
                $values['time_create'] = time();
                $values['time_update'] = time();
                $values['uid'] = Pi::user()->getId();
                $values['status'] = $this->config('auto_approval');
                $values['type'] = 'A';
                // Update answer
                $this->getModel('question')->update(array('answer' => $question['answer'] + 1), array('id' => $question['id']));
                // Save values
                $row = $this->getModel('question')->createRow();
                $row->assign($values);
                $row->save();
                // Check it save or not
                if ($this->config('auto_approval')) {
                    $message = __('Your new answer to this question save successfully, and show under question');
                } else {
                    $message = __('Your new answer to this question save successfully, But it need review and publish by website admin');
                }
                $url = array('', 'module' => $module, 'controller' => 'question', 'action' => 'index', 'slug' => $question['slug']);
                $this->jump($url, $message);
            }
        } else {
            $values['pid'] = $question['id'];
            $form->setData($values);
        }
        // Set header
        $title = sprintf(__('Answer to %s'), $question['title']);
        $seo_title = Pi::api('text', 'ask')->title($title);
        $seo_keywords = Pi::api('text', 'ask')->keywords($title);
        $seo_description = Pi::api('text', 'ask')->description($title);
        // Set view
        $this->view()->headTitle($seo_title);
        $this->view()->headDescription($seo_keywords, 'set');
        $this->view()->headKeywords($seo_description, 'set');
        $this->view()->setTemplate('answer_index');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', $title);
        $this->view()->assign('question', $question);
    }
}