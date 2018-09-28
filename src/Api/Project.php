<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
namespace Module\Ask\Api;

use Pi;
use Pi\Application\Api\AbstractApi;

/*
 * Pi::api('project', 'ask')->getProject($parameter, $type);
 * Pi::api('project', 'ask')->getProjectList();
 * Pi::api('project', 'ask')->canonizeProject($project);
 */

class Project extends AbstractApi
{
    public function getProject($parameter, $type = 'id')
    {
        // Get project
        $project = Pi::model('project', $this->getModule())->find($parameter, $type);
        $project = $this->canonizeProject($project);
        return $project;
    }

    public function getProjectList()
    {
        $list = array();
        $where = array('status' => 1);
        $order = array('time_create DESC', 'id DESC');
        $select = Pi::model('project', $this->getModule())->select()->where($where)->order($order);
        $rowset = Pi::model('project', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $list[$row->id] = $this->canonizeProject($row);
        }
        return $list;
    }

    public function canonizeProject($project)
    {
        // Check
        if (empty($project)) {
            return '';
        }
        // boject to array
        $project = $project->toArray();
        // Set times
        $project['time_create_view'] = _date($project['time_create']);
        $project['time_update_view'] = _date($project['time_update']);
        // Set text_description
        $project['text_description'] = Pi::service('markup')->render($project['text_description'], 'html', 'html');
        // Set question url
        $project['projectUrl'] = Pi::url(Pi::service('url')->assemble('ask', array(
            'module'        => $this->getModule(),
            'controller'    => 'project',
            'slug'          => $project['slug'],
        )));
        // Set project manager
        if ($project['manager'] > 0) {
            $project['user'] = Pi::user()->get($project['manager'], array(
                'id', 'identity', 'name', 'email'
            ));
            $project['user']['avatar'] = Pi::service('user')->avatar($project['user']['id'], 'large', array(
                'alt' => $project['user']['name'],
                'class' => 'img-fluid rounded-circle',
            ));
            $project['user']['profileUrl'] = Pi::url(Pi::service('user')->getUrl('profile', array(
                'id' => $project['user']['id'],
            )));
        }
        // return question
        return $project;
    }
}