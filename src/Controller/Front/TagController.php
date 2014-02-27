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

class TagController extends ActionController
{
    public function indexAction()
    {
        /*
           * This is a sample page
           * This page view is like index and list view and show list of questions from selected tag
           * And list of question id's needed from tag module
           * Paginator and story array perhaps needed rewrite for use low memory
           */

        // Get info from url
        $slug = $this->params('slug');
        $module = $this->params('module');
        $page = $this->params('page', 1);
        // Check slug
        if (empty($slug)) {
            $this->jump(array('route' => '.ask', 'module' => $module, 'controller' => 'index'), __('The tag not found.'));
        }
        // Get order
        $selectOrder = $this->params('order', 'create');
        if (!in_array($selectOrder, array('create', 'hits', 'point', 'answer'))) {
            $selectOrder = 'create';
        }
        // Get page ID or slug from url
        $params = $this->params()->fromRoute();
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Set offset
        $offset = (int)($page - 1) * $config['show_perpage'];
        // Get photo Id from tag module
        $tags = Pi::service('tag')->getList($module, $slug, null, $config['show_tags'], $offset);
        // Check slug
        if (empty($tags)) {
            $this->jump(array('route' => '.ask', 'module' => $module, 'controller' => 'index'), __('The tag not found.'));
        }
        foreach ($tags as $tag) {
            $tagId[] = $tag['item'];
        }
        // Set info
        $order = array($selectOrder . ' DESC', 'id DESC');
        $columns = array('id', 'answer', 'author', 'point', 'count', 'hits', 'create', 'title', 'slug', 'tags');
        $where = array('status' => 1, 'type' => 'Q', 'id' => $tagId);
        $limit = intval($config['show_index']);
        // Get list of story
        $select = $this->getModel('question')->select()->columns($columns)->where($where)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('question')->selectWith($select);
        foreach ($rowset as $row) {
            $question[$row->id] = $row->toArray();
            $question[$row->id]['create'] = date('Y/m/d', $question[$row->id]['create']);
            $question[$row->id]['tags'] = Json::decode($question[$row->id]['tags']);
            $question[$row->id]['url'] = $this->url('.ask', array('module' => $module, 'controller' => 'question', 'slug' => $question[$row->id]['slug']));
            $writer = Pi::model('user_account')->find($question[$row->id]['author'])->toArray();
            $question[$row->id]['identity'] = $writer['identity'];
            $question[$row->id]['labelpoint'] = HtmlClass::TabLabel($question[$row->id]['point']);
            $question[$row->id]['labelanswer'] = HtmlClass::TabLabel($question[$row->id]['answer']);
            $question[$row->id]['labelhits'] = HtmlClass::TabLabel($question[$row->id]['hits']);
        }
        // Set paginator
        $select = $this->getModel('question')->select()->columns(array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)')))->where($where);
        $count = $this->getModel('question')->selectWith($select)->current()->count;
        $paginator = \Pi\Paginator\Paginator::factory(intval($count));
        $paginator->setItemCountPerPage($config['show_perpage']);
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(array(
            'template' => $this->url('.ask', array('module' => $module, 'controller' => 'tag', 'slug' => urlencode($slug), 'order' => $selectOrder, 'page' => '%page%')),
        ));
        // Tab urls
        $url = array(
            'create' => $this->url('.ask', array('module' => $module, 'controller' => 'tag', 'slug' => urlencode($slug), 'order' => 'create')),
            'vote' => $this->url('.ask', array('module' => $module, 'controller' => 'tag', 'slug' => urlencode($slug), 'order' => 'point')),
            'hits' => $this->url('.ask', array('module' => $module, 'controller' => 'tag', 'slug' => urlencode($slug), 'order' => 'hits')),
            'answer' => $this->url('.ask', array('module' => $module, 'controller' => 'tag', 'slug' => urlencode($slug), 'order' => 'answer')),
        );
        // Main url
        $mainurl = array(
            'title' => __('Back to question list'),
            'url' => $this->url('.ask', array('module' => $module, 'controller' => 'index')),
        );
        // Set view
        $this->view()->headTitle($slug);
        $this->view()->headDescription($slug, 'set');
        $this->view()->headKeywords($slug, 'set');
        $this->view()->setTemplate('question_list');
        $this->view()->assign('questions', $question);
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('config', $config);
        $this->view()->assign('url', $url);
        $this->view()->assign('mainurl', $mainurl);
        $this->view()->assign('tabclass', HtmlClass::TabClass($selectOrder));
    }
}