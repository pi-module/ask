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
use Laminas\Json\Json;

class QuestionController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $slug = $this->params('slug');
        $module = $this->params('module');
        // Get Module Config
        $config = Pi::service('registry')->config->read($module);
        // Get user id
        $uid = Pi::user()->getId();
        // Find question
        $question = Pi::api('question', 'ask')->getQuestion($slug, 'slug');
        // Check status
        if (empty($question)) {
            $this->getResponse()->setStatusCode(404);
            $this->terminate(__('The question not found.'), '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Get project
        $question['is_manager'] = false;
        if ($question['project_id'] > 0) {
            $question['project'] = Pi::api('project', 'ask')->getProject($question['project_id']);
            if ($question['project']['user']['id'] == $question['user']['id']) {
                $question['is_manager'] = true;
            }
        }
        // Check access
        $access = false;
        $userIsManager = false;
        if (isset($question['project'])
            && $question['project']['manager'] == $uid
            && in_array($question['status'], array(1, 2))
            && $config['auto_approval'] == 2) {
            $access = true;
            $userIsManager = true;
        } elseif ($question['status'] == 1) {
            $access = true;
        }
        if (!$access) {
            $this->getResponse()->setStatusCode(404);
            $this->terminate(__('The question not found.'), '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Update Hits
        $this->getModel('question')->increment('hits', array('id' => $question['id']));
        // Set vote
        if (Pi::service('module')->isActive('vote') && $config['vote_bar']) {
            $question['vote'] = array(
                'point'  => $question['point'],
                'count'  => $question['count'],
                'item'   => $question['id'],
                'table'  => 'question',
                'module' => $module,
                'type'   => 'plus',
                'class'  => 'btn-group-vertical',
            );
        }

        // Get answers
        if ($question['answer'] > 0) {
            $answers = array();
            $where = array('status' => 1, 'question_id' => $question['id'], 'type' => 'A');
            if ($userIsManager) {
                $where['status'] = array(1, 2);
            }
            $order = array('point DESC', 'id DESC');
            $select = $this->getModel('question')->select()->where($where)->order($order);
            $rowset = $this->getModel('question')->selectWith($select);
            foreach ($rowset as $row) {
                $answers[$row->id] = Pi::api('question', 'ask')->canonizeQuestion($row);

                $answers[$row->id]['is_manager'] = false;
                if ($question['project']['user']['id'] == $answers[$row->id]['user']['id']) {
                    $answers[$row->id]['is_manager'] = true;
                }

                if (Pi::service('module')->isActive('vote') && $config['vote_bar']) {
                    $answers[$row->id]['vote'] = array(
                        'point'  => $answers[$row->id]['point'],
                        'count'  => $answers[$row->id]['count'],
                        'item'   => $answers[$row->id]['id'],
                        'table'  => 'question',
                        'module' => $module,
                        'type'   => 'plus',
                        'class'  => 'btn-group-vertical',
                    );
                }
            }
            $this->view()->assign('answers', $answers);
        }
        // Set view
        $this->view()->headTitle($question['seo_title']);
        $this->view()->headDescription($question['seo_keywords'], 'set');
        $this->view()->headKeywords($question['seo_description'], 'set');
        $this->view()->setTemplate('question-single');
        $this->view()->assign('question', $question);
        $this->view()->assign('config', $config);
        $this->view()->assign('userIsManager', $userIsManager);
    }

    public function reviewAction()
    {
        // Get info from url
        $module = $this->params('module');
        $id = $this->params('id');
        $status = $this->params('status');
        // Get Module Config
        $config = Pi::service('registry')->config->read($module);
        // Get user id
        $uid = Pi::user()->getId();
        // Check auto approval
        if ($config['auto_approval'] == 1) {
            return false;
        }
        // Find question
        $question = Pi::api('question', 'ask')->getQuestion($id);
        // Check question
        if (empty($question) && $question['status'] != 2) {
            return false;
        }
        //
        $mainQuestion = array();
        if ($question['type'] == 'A') {
            $mainQuestion = Pi::api('question', 'ask')->getQuestion($question['question_id']);
            $question['project_id'] = $mainQuestion['project_id'];
        }
        // Get project
        $project = Pi::api('project', 'ask')->getProject($question['project_id']);
        // Check question
        if (empty($project) || $project['status'] != 1) {
            return false;
        }
        // Check status
        if (!in_array($status, array('confirm', 'reject'))) {
            return false;
        }
        // Check manager
        if ($project['manager'] != $uid) {
            return false;
        }
        // Back
        switch ($question['type']) {
            case 'Q':
                switch ($status) {
                    case 'confirm':
                        // Update status
                        $this->getModel('question')->update(
                            array('status' => 1),
                            array('id' => $question['id'])
                        );
                        // Set jump
                        $url = $question['questionUrl'];
                        $message = __('Question confirmed !');
                        break;

                    case 'reject':
                        // Update status
                        $this->getModel('question')->update(
                            array('status' => 5),
                            array('id' => $question['id'])
                        );
                        // Set jump
                        $url = $project['projectUrl'];
                        $message = __('Question rejected !');
                        break;
                }
                break;

            case 'A':
                switch ($status) {
                    case 'confirm':
                        // Update status
                        $this->getModel('question')->update(
                            array('status' => 1),
                            array('id' => $question['id'])
                        );
                        // Set jump
                        $url = $mainQuestion['questionUrl'];
                        $message = __('Answer confirmed !');
                        break;

                    case 'reject':
                        // Update status
                        $this->getModel('question')->update(
                            array('status' => 5),
                            array('id' => $question['id'])
                        );
                        // Set jump
                        $url = $mainQuestion['questionUrl'];
                        $message = __('Answer rejected !');
                        break;
                }
                break;
        }
        //
        Pi::api('notification', 'ask')->reviewQuestion($status, $project, $question, $mainQuestion);
        // Set jump
        $this->jump($url, $message);
    }
}