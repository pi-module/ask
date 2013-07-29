<?php
/**
 * Ask profile controller
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
use Module\Ask\HtmlClass;
use Zend\Json\Json;

class ProfileController extends ActionController
{ 
	 public function indexAction()
    {
	     // Get info from url
        $slug = $this->params('slug');
        $module = $this->params('module');
        // Check slug
        if (empty($slug)) {
            $this->jump(array('route' => '.ask', 'module' => $module, 'controller' => 'index'), __('Please select user.'));
        }
        // Find user
        $user = Pi::model('user_account')->find($slug, 'identity');
        // Check slug
        if (empty($user)) {
            $this->jump(array('route' => '.ask', 'module' => $module, 'controller' => 'index'), __('Please select user.'));
        }
        // Set user array
        $user = $user->toArray();
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Set info
        $order = array('create DESC', 'id DESC');
        $columns = array('id', 'answer', 'author', 'point', 'count', 'hits', 'create', 'title', 'slug', 'content', 'tags');
        $limit = intval($config['show_index']);
        // Get list of questions
        $whereQ = array('status' => 1, 'type' => 'Q', 'author' => $user['id']);
        $select = $this->getModel('question')->select()->columns($columns)->where($whereQ)->order($order)->limit($limit);
        $rowset = $this->getModel('question')->selectWith($select);
        foreach ($rowset as $row) {
            $question[$row->id] = $row->toArray();
            $question[$row->id]['create'] = date('Y/m/d', $question[$row->id]['create']);
            $question[$row->id]['tags'] = Json::decode($question[$row->id]['tags']);
            $question[$row->id]['url'] = $this->url('.ask', array('module' => $module, 'controller' => 'question', 'slug' => $question[$row->id]['slug']));
            $question[$row->id]['identity'] = $user['identity'];
            $question[$row->id]['labelpoint'] = HtmlClass::TabLabel($question[$row->id]['point']);
            $question[$row->id]['labelanswer'] = HtmlClass::TabLabel($question[$row->id]['answer']);
            $question[$row->id]['labelhits'] = HtmlClass::TabLabel($question[$row->id]['hits']);
        }
        // Get list of answers
        $whereA = array('status' => 1, 'type' => 'A', 'author' => $user['id']);
        $select = $this->getModel('question')->select()->columns($columns)->where($whereA)->order($order)->limit($limit);
        $rowset = $this->getModel('question')->selectWith($select);
        foreach ($rowset as $row) {
            $answer[$row->id] = $row->toArray();
            $answer[$row->id]['create'] = date('Y/m/d', $answer[$row->id]['create']);
            $answer[$row->id]['tags'] = Json::decode($answer[$row->id]['tags']);
            $answer[$row->id]['identity'] = $user['identity'];
        }
        // Set view
        $this->view()->headTitle($user['identity'] . ' questions and answers');
        $this->view()->headDescription($user['identity'] . ' questions and answers', 'set');
        $this->view()->headKeywords($user['identity'] . ',question,answer,profile', 'set');
        $this->view()->setTemplate('profile_index');
        $this->view()->assign('questions', $question);
        $this->view()->assign('answers', $answer);
        $this->view()->assign('config', $config);
        $this->view()->assign('user', $user);
    }	
}