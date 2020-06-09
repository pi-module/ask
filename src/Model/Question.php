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

namespace Module\Ask\Model;

use Pi\Application\Model\Model;

class Question extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $columns = array(
        'id',
        'type',
        'question_id',
        'project_id',
        'answer',
        'uid', 'point',
        'count',
        'favorite',
        'hits',
        'status',
        'time_create',
        'time_update',
        'title',
        'slug',
        'text_description',
        'tag',
        'seo_title',
        'seo_keywords',
        'seo_description',
        'main_image',
        'additional_images',
    );
}
