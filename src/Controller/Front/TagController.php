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

class TagController extends IndexController
{
    public function termAction()
    {
        // Get info from url
        $module = $this->params('module');
        $slug = $this->params('slug');
        // Check tag
        if (!Pi::service('module')->isActive('tag')) {
            $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
            $this->jump($url, __('Tag module not installed.'), 'error');
        }
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Check slug
        if (!isset($slug) || empty($slug)) {
            $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
            $this->jump($url, __('The tag not set.'), 'error');
        }
        // Get id from tag module
        $tagId = array();
        $tags = Pi::service('tag')->getList($slug, $module);
        foreach ($tags as $tag) {
            $tagId[] = $tag['item'];
        }
        // Check slug
        if (empty($tagId)) {
            $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
            $this->jump($url, __('The tag not found.'), 'error');
        }
        // Set question info
        $where = array('status' => 1, 'type' => 'Q', 'id' => $tagId);
        // Set paginator info
        $template = array(
            'controller' => 'tag',
            'action'     => 'term',
        );
        // Get question List
        $questions = $this->askList($where);
        // Get paginator
        $paginator = $this->askPaginator($template, $where);
        // Set header and title
        $title = sprintf(__('All questions from %s'), $slug);
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
        $this->view()->setTemplate('question_list');
        $this->view()->assign('questions', $questions);
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('config', $config);
        $this->view()->assign('title', $title);
    }

    public function listAction()
    {
        // Get info from url
        $module = $this->params('module');
        $tagList = array();
        // Check tag module install or not
        if (Pi::service('module')->isActive('tag')) {
            $where = array('module' => $module);
            $order = array('count DESC', 'id DESC');
            $select = Pi::model('stats', 'tag')->select()->where($where)->order($order);
            $rowset = Pi::model('stats', 'tag')->selectWith($select);
            foreach ($rowset as $row) {
                $tag = Pi::model('tag', 'tag')->find($row->term, 'term');
                $tagList[$row->id] = $row->toArray();
                $tagList[$row->id]['term'] = $tag['term'];
                $tagList[$row->id]['url'] = Pi::url($this->url('', array(
                    'controller'  => 'tag', 
                    'action'      => 'term', 
                    'slug'        => urldecode($tag['term'])
                )));
            }
        }
        // Set header and title
        $title = __('List of all used tags');
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
        $this->view()->setTemplate('tag_list');
        $this->view()->assign('title', $title);
        $this->view()->assign('tagList', $tagList);
    }
}