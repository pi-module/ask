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

class QuestionController extends ActionController
{
    public function indexAction()
    {
        // Get question ID or slug from url
        $params = $this->params()->fromRoute();
        // Find story
        $question = $this->getModel('question')->find($params['slug'], 'slug')->toArray();
        // Get Module Config
        $config = Pi::service('registry')->config->read($params['module']);
        // Check page
        if (!$question || $question['status'] != 1) {
            $this->jump(array('route' => '.ask', 'module' => $params['module'], 'controller' => 'index'), __('The question not found.'));
        }
        // Update Hits
        $this->getModel('question')->update(array('hits' => $question['hits'] + 1), array('id' => $question['id']));
        // Set date
        $question['create'] = date('Y/m/d H:i:s', $question['create']);
        // Set markup
        $question['content'] = Pi::service('markup')->render($question['content'], 'html', 'markdown');
        // Get writer identity
        $writer = Pi::model('user_account')->find($question['author'])->toArray();
        $question['identity'] = $writer['identity'];
        unset($writer);
        // Set question vote
        if ($config['vote_bar'] && Pi::service('module')->isActive('vote')) {
            $vote['point'] = $question['point'];
            $vote['count'] = $question['count'];
            $vote['item'] = $question['id'];
            $vote['module'] = $params['module'];
            $vote['type'] = $config['vote_type'];
            $vote['table'] = 'question';
            $this->view()->assign('vote', $vote);
        }
        // Get answers
        if ($question['answer'] > 0) {
            $where = array('status' => 1, 'pid' => $question['id'], 'type' => 'A');
            $selectChild = $this->getModel('question')->select()->where($where)->order(array('point DESC'));
            $rowset = $this->getModel('question')->selectWith($selectChild);
            foreach ($rowset as $row) {
                $answers[$row->id] = $row->toArray();
                $answers[$row->id]['create'] = date('Y/m/d', $answers[$row->id]['create']);
                $answers[$row->id]['content'] = Pi::service('markup')->render($answers[$row->id]['content'], 'html', 'markdown');
                $writer = Pi::model('user_account')->find($answers[$row->id]['author'])->toArray();
                $answers[$row->id]['identity'] = $writer['identity'];
                $answers[$row->id]['vote'] = array(
                    'point' => $answers[$row->id]['point'],
                    'count' => $answers[$row->id]['count'],
                    'item' => $answers[$row->id]['id'],
                    'module' => $params['module'],
                    'type' => $config['vote_type'],
                    'table' => 'question',
                );
            }
            $this->view()->assign('answers', $answers);
        }
        
        // Set keywords
        $keywords = _strip($question['title']);
        $keywords = strtolower(trim($keywords));
        $keywords = array_unique(array_filter(explode(' ', $keywords)));
        $keywords = implode(',', $keywords);
        
        // Set description
        $description = _strip($question['title']);
        $description = strtolower(trim($description));
        $description = preg_replace('/[\s]+/', ' ', $description);
 
        // Set view
        $this->view()->headTitle($question['title']);
        $this->view()->headDescription($description, 'set');
        $this->view()->headKeywords($keywords, 'set');
        $this->view()->setTemplate('question_index');
        $this->view()->assign('question', $question);
        $this->view()->assign('config', $config);
        $this->view()->assign('tags', Pi::service('tag')->get($params['module'], $question['id'], ''));
    }
}