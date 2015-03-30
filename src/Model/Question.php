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

namespace Module\Ask\Model;

use Pi\Application\Model\Model;

class Question extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $columns = array(
        'id', 'type', 'pid', 'answer', 'uid', 'point', 'count', 'favorite', 'hits', 'status',
        'time_create', 'time_update', 'title', 'slug', 'content', 'tag', 'seo_title',
        'seo_keywords','seo_description'
    );
}