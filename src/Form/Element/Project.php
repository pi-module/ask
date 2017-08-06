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
namespace Module\Ask\Form\Element;

use Pi;
use Zend\Form\Element\Select;

class Project extends Select
{
    public function getValueOptions()
    {
        if (empty($this->valueOptions)) {
            $list = array();
            $columns = array('id', 'title');
            $order = array('title ASC', 'id DESC');
            $select = Pi::model('project', 'ask')->select()->columns($columns)->order($order);
            $rowset = Pi::model('project', 'ask')->selectWith($select);
            foreach ($rowset as $row) {
                $list[$row->id] = $row->title;
            }
            $this->valueOptions = $list;
        }
        return $this->valueOptions;
    }
}