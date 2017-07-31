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
use Pi\Filter;
use Pi\Mvc\Controller\ActionController;
use Zend\Json\Json;

class QuestionController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $slug = $this->params('slug');
        $module = $this->params('module');
        // Get Module Config
        $config = Pi::service('registry')->config->read($module);
        // Find question
        $question = Pi::api('question', 'ask')->getQuestion($slug, 'slug');
        // Check status
        if (!$question || $question['status'] != 1) {
            $this->jump(array('', 'module' => $module, 'controller' => 'index'), __('The question not found.'));
        }
        // Update Hits
        $this->getModel('question')->increment('hits', array('id' => $question['id']));
        // Update Hits
        $this->getModel('question')->update(array('hits' => $question['hits'] + 1), array('id' => $question['id']));
        // Set vote
        if (Pi::service('module')->isActive('vote')) {
            $vote = array(
                'point'  => $question['point'],
                'count'  => $question['count'],
                'item'   => $question['id'],
                'table'  => 'question',
                'module' => $module,
                'type'   => 'plus',
            );
            $this->view()->assign('vote', $vote);
        }
        // Get answers
        if ($question['answer'] > 0) {
            $answers = array();
            $where = array('status' => 1, 'question_id' => $question['id'], 'type' => 'A');
            $order = array('point DESC', 'id DESC');
            $select = $this->getModel('question')->select()->where($where)->order($order);
            $rowset = $this->getModel('question')->selectWith($select);
            foreach ($rowset as $row) {
                $answers[$row->id] = Pi::api('question', 'ask')->canonizeQuestion($row);
                if (Pi::service('module')->isActive('vote')) {
                    $answers[$row->id]['vote'] = array(
                        'point'  => $answers[$row->id]['point'],
                        'count'  => $answers[$row->id]['count'],
                        'item'   => $answers[$row->id]['id'],
                        'table'  => 'question',
                        'module' => $module,
                        'type'   => 'plus',
                    );
                }
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