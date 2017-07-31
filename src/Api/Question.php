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
namespace Module\Ask\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Zend\Json\Json;

/*
 * Pi::api('question', 'ask')->getQuestion($parameter, $type);
 * Pi::api('question', 'ask')->getListFromId($id);
 * Pi::api('question', 'ask')->canonizeQuestion($question);
 */

class Question extends AbstractApi
{
    public function getQuestion($parameter, $type = 'id')
    {
        // Get product
        $question = Pi::model('question', $this->getModule())->find($parameter, $type);
        $question = $this->canonizeQuestion($question);
        return $question;
    }

    public function getListFromId($id)
    {
        $list = array();
        $where = array('id' => $id, 'status' => 1);
        $select = Pi::model('question', $this->getModule())->select()->where($where);
        $rowset = Pi::model('question', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $list[$row->id] = $this->canonizeQuestion($row);
        }
        return $list;
    }

    public function canonizeQuestion($question)
    {
        // Check
        if (empty($question)) {
            return '';
        }
        // boject to array
        $question = $question->toArray();
        // Set times
        $question['time_create_view'] = _date($question['time_create']);
        $question['time_update_view'] = _date($question['time_update']);
        // Set tags
        if (!empty($question['tag'])) {
            $tags = Json::decode($question['tag']);
            $tags = array_unique($tags);
            foreach ($tags as $tag) {
                $tagList[] = array(
                    'term' => $tag,
                    'url'  => Pi::url(Pi::service('url')->assemble('ask', array(
                        'module'        => $this->getModule(),
                        'controller'    => 'tag',
                        'action'        => 'term',
                        'slug'          => $tag,
                    ))),
                );
            }
            $question['tag'] = $tagList;
        }
        // Set user
        $question['user'] = Pi::user()->get($question['uid'], array(
            'id', 'identity', 'name', 'email'
        ));
        /* $question['user']['avatar'] = Pi::service('user')->avatar($question['user']['id'], 'large', array(
            'alt' => $question['user']['name'],
            'class' => 'img-circle',
        ));
        $question['user']['profileUrl'] = Pi::url(Pi::service('user')->getUrl('profile', array(
            'id' => $question['user']['id'],
        ))); */
        // Set info for Q and A
        switch ($question['type']) {
            case 'Q':
                // Set text_description
                $question['text_description'] = Pi::service('markup')->render($question['text_description'], 'html', 'html');
                // Set question url
                $question['questionUrl'] = Pi::url(Pi::service('url')->assemble('ask', array(
                    'module'        => $this->getModule(),
                    'controller'    => 'question',
                    'slug'          => $question['slug'],
                )));
                break;
            
            case 'A':
                // Set text_description
                $question['text_description'] = Pi::service('markup')->render($question['text_description'], 'html', 'text');
                // Set question url
                $question['questionUrl'] = '#';
                break;
        }
        // return question
        return $question; 
    }
}