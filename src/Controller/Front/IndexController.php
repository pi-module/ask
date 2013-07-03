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
use Module\Ask\HtmlClass;
use Zend\Json\Json;

class IndexController extends ActionController
{
    public function indexAction()
    {
        // Get page
        $page = $this->params('page', 1);
        // Get order
        $selectOrder = $this->params('order', 'create');
        if (!in_array($selectOrder, array('create', 'hits', 'point', 'answer'))) {
            $selectOrder = 'create';
        }
        // Get page ID or alias from url
        $params = $this->params()->fromRoute();
        // Get config
        $config = Pi::service('registry')->config->read($params['module']);
        // Set info
        $order = array($selectOrder . ' DESC', 'id DESC');
        $columns = array('id', 'answer', 'author', 'point', 'count', 'hits', 'create', 'title', 'alias', 'tags');
        $where = array('status' => 1, 'type' => 'Q');
        $limit = intval($config['show_index']);
        // Get list of story
        $select = $this->getModel('question')->select()->columns($columns)->where($where)->order($order)->limit($limit);
        $rowset = $this->getModel('question')->selectWith($select);
        foreach ($rowset as $row) {
            $question[$row->id] = $row->toArray();
            $question[$row->id]['create'] = date('Y/m/d', $question[$row->id]['create']);
            $question[$row->id]['tags'] = Json::decode($question[$row->id]['tags']);
            $question[$row->id]['url'] = $this->url('.ask', array('module' => $params['module'], 'controller' => 'question', 'alias' => $question[$row->id]['alias']));
            $writer = Pi::model('user_account')->find($question[$row->id]['author'])->toArray();
            $question[$row->id]['identity'] = $writer['identity'];
            $question[$row->id]['labelpoint'] = HtmlClass::TabLabel($question[$row->id]['point']);
            $question[$row->id]['labelanswer'] = HtmlClass::TabLabel($question[$row->id]['answer']);
            $question[$row->id]['labelhits'] = HtmlClass::TabLabel($question[$row->id]['hits']);
        }
        // Tab urls
        $url = array(
            'create' => $this->url('.ask', array('module' => $params['module'], 'controller' => 'index', 'order' => 'create')),
            'vote' => $this->url('.ask', array('module' => $params['module'], 'controller' => 'index', 'order' => 'point')),
            'hits' => $this->url('.ask', array('module' => $params['module'], 'controller' => 'index', 'order' => 'hits')),
            'answer' => $this->url('.ask', array('module' => $params['module'], 'controller' => 'index', 'order' => 'answer')),
        );
        // Main url
        $mainurl = array(
            'title' => __('Complete list of questions'),
            'url' => $this->url('.ask', array('module' => $params['module'], 'controller' => 'list')),
        );
        // Set view
        $this->view()->headTitle('ASK');
        $this->view()->headDescription('ASK', 'set');
        $this->view()->headKeywords('ASK', 'set');
        $this->view()->setTemplate('question_list');
        $this->view()->assign('questions', $question);
        $this->view()->assign('config', $config);
        $this->view()->assign('url', $url);
        $this->view()->assign('mainurl', $mainurl);
        $this->view()->assign('tabclass', HtmlClass::TabClass($selectOrder));
    }
}