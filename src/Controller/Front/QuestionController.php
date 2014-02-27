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
use Zend\Json\Json;

class QuestionController extends ActionController
{
    public function indexAction()
    {
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
        // Update Hits
        $this->getModel('question')->update(array('hits' => $question['hits'] + 1), array('id' => $question['id']));
        // Set date
        $question['time_create'] = _date($question['time_create']);
        // Set markup
        $question['content'] = Pi::service('markup')->render($question['content'], 'html', 'text');
        // Set vote
        if (Pi::service('module')->isActive('vote')) {
            $vote['point'] = $question['point'];
            $vote['count'] = $question['count'];
            $vote['item'] = $question['id'];
            $vote['table'] = 'question';
            $vote['module'] = $module;
            $vote['type'] = 'plus';
            $this->view()->assign('vote', $vote);
        }
        // Get answers
        if ($question['answer'] > 0) {
            $answers = array();
            $where = array('status' => 1, 'pid' => $question['id'], 'type' => 'A');
            $order = array('point DESC', 'id DESC');
            $select = $this->getModel('question')->select()->where($where)->order($order);
            $rowset = $this->getModel('question')->selectWith($select);
            foreach ($rowset as $row) {
                $answers[$row->id] = $row->toArray();
                $answers[$row->id]['time_create'] = _date($answers[$row->id]['time_create']);
                $answers[$row->id]['content'] = Pi::service('markup')->render($answers[$row->id]['content'], 'html', 'text');
                $answers[$row->id]['vote'] = array(
                    'point'  => $answers[$row->id]['point'],
                    'count'  => $answers[$row->id]['count'],
                    'item'   => $answers[$row->id]['id'],
                    'table'  => 'question',
                    'module' => $module,
                    'type'   => 'plus',
                );
            }
            $this->view()->assign('answers', $answers);
        }
        // Set view
        $this->view()->headTitle($question['seo_title']);
        $this->view()->headDescription($question['seo_keywords'], 'set');
        $this->view()->headKeywords($question['seo_description'], 'set');
        $this->view()->setTemplate('question_index');
        $this->view()->assign('question', $question);
        $this->view()->assign('config', $config);
    }
}