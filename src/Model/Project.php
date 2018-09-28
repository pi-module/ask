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

class Project extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $columns = array(
        'id',
        'title',
        'slug',
        'text_description',
        'time_create',
        'time_update',
        'status',
        'seo_title',
        'seo_keywords',
        'seo_description',
        'manager',
        'main_image',
        'additional_images',
    );
}
