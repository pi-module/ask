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
use Pi\Filter;
use Pi\Mvc\Controller\ActionController;
use Module\Ask\Form\AnswerForm;
use Module\Ask\Form\AnswerFilter;

class AnswerController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $slug = $this->params('slug');
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Check ask
        if (!$config['question_answer']) {
            $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
            $this->jump($url, __('Answer question not active'), 'error');
        }
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Find story
        $question = Pi::api('question', 'ask')->getQuestion($slug, 'slug');
        // Check page
        if (empty($question) || $question['status'] != 1) {
            $message = __('The question not found.');
            $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
            $this->jump($url, $message);
        }
        // Check project
        $project = array();
        if ($config['project_active'] && $question['project_id']) {
            // Get topic information from model
            $project = Pi::api('project', 'ask')->getProject($question['project_id']);
            // Check slug set
            if (empty($project) || $project['status'] != 1) {
                $this->getResponse()->setStatusCode(404);
                $this->terminate(__('Project not set.'), '', 'error-404');
                $this->view()->setLayout('layout-simple');
                return;
            }
        }
        // get info
        $form = new AnswerForm('Answer');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new AnswerFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Set title
                $values['title'] = sprintf(__('Answer to %s'), $question['title']);
                // Set time
                $values['time_create'] = time();
                $values['time_update'] = time();
                // Set slug
                $filter = new Filter\Slug;
                $slug = sprintf('%s-%s', $values['title'], _date($values['time_create']));
                $values['slug'] = $filter($slug);
                // Set seo_title
                $filter = new Filter\HeadTitle;
                $values['seo_title'] = $filter($values['title']);
                // Set seo_keywords
                $filter = new Filter\HeadKeywords;
                $filter->setOptions(array(
                    'force_replace_space' => true,
                ));
                $values['seo_keywords'] = $filter($values['title']);
                // Set seo_description
                $filter = new Filter\HeadDescription;
                $values['seo_description'] = $filter($values['title']);
                // Set info
                $values['uid'] = Pi::user()->getId();
                $values['status'] = $this->config('auto_approval');
                $values['type'] = 'A';
                $values['project_id'] = $project['id'];
                // Update answer
                $this->getModel('question')->update(
                    array('answer' => $question['answer'] + 1),
                    array('id' => $question['id'])
                );
                // Save values
                $row = $this->getModel('question')->createRow();
                $row->assign($values);
                $row->save();
                // Set question
                $answer = Pi::api('question', 'ask')->canonizeQuestion($row);
                // Send notification
                Pi::api('notification', 'ask')->askQuestion($question, $answer, $project);
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
            $values['question_id'] = $question['id'];
            $form->setData($values);
        }
        // Set header and title
        $title = sprintf(__('Answer to %s'), $question['title']);
        // Set seo_keywords
        $filter = new Filter\HeadKeywords;
        $filter->setOptions(array(
            'force_replace_space' => true
        ));
        $seoKeywords = $filter($title);
        // Set view
        $this->view()->headTitle($title);
        $this->view()->headDescription($title, 'set');
        $this->view()->headKeywords($seoKeywords, 'set');
        $this->view()->setTemplate('answer-index');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', $title);
        $this->view()->assign('question', $question);
        $this->view()->assign('config', $config);
    }
}