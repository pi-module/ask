<?php
/**
 * Feed controller class
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
 * @subpackage      Controller
 * @version         $Id$
 */

namespace Module\Ask\Controller\Feed;

use Pi;
use Pi\Mvc\Controller\FeedController;

/**
 * Index action controller
 */
class IndexController extends FeedController
{
    /**
     * Create feeds for recent module updates
     *
     * @return array
     */
    public function indexAction()
    {
        $feed = array(
            'title' => __('Ask feed'),
            'description' => __('Recent questions.'),
            'date_created' => time(),
        );

        $columns = array('id', 'title', 'slug', 'content', 'create');
        $order = array('create DESC', 'id DESC');
        $where = array('status' => 1, 'type' => 'Q');
        $limit = intval($this->config('feed_num'));
        $select = $this->getModel('question')->select()->columns($columns)->where($where)->order($order)->limit($limit);
        $rowset = $this->getModel('question')->selectWith($select);
        foreach ($rowset as $row) {
            $entry[$row->id] = $row->toArray();
            $entry['title'] = $row['title'];
            $entry['description'] = $row['content'];
            $entry['date_modified'] = (int)$row['create'];
            $entry['link'] = $this->getHref($row['slug']);
            $feed['entries'][] = $entry;
        }
        return $feed;
    }

    protected function getHref($slug)
    {
        $uri = $this->url('.ask', array(
            'module' => $this->getModule(),
            'controller' => 'question',
            'action' => 'index',
            'slug' => $slug
        ));
        return Pi::url($uri, false);
    }
}