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

namespace Module\Ask\Controller\Feed;

use Pi;
use Pi\Mvc\Controller\FeedController;
use Pi\Feed\Model as DataModel;

class IndexController extends FeedController
{
    public function indexAction()
    {
        $feed = $this->getDataModel(array(
            'title'         => __('Ask feed'),
            'description'   => __('Recent Questions.'),
            'date_created'  => time(),
        ));
        $order = array('time_create DESC', 'id DESC');
        $where = array('status' => 1, 'type' => 'Q');
        $limit = intval($this->config('feed_num'));
        $select = $this->getModel('question')->select()->where($where)->order($order)->limit($limit);
        $rowset = $this->getModel('question')->selectWith($select);
        foreach ($rowset as $row) {
            $entry = array();
            $entry['title'] = $row->title;
            $entry['description'] = strtolower(trim($row->content));
            $entry['date_modified'] = (int)$row->time_create;
            $entry['link'] = Pi::url(Pi::service('url')->assemble('news', array(
                'module'        => $this->getModule(),
                'controller'    => 'question',
                'slug'          => $row->slug,
            )));
            $feed->entry = $entry;
        }
        return $feed;
    }
}