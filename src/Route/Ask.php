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

namespace Module\Ask\Route;

use Pi\Mvc\Router\Http\Standard;

class Ask extends Standard
{
    /**
     * Default values.
     * @var array
     */
    protected $defaults = array(
        'module'        => 'ask',
        'controller'    => 'index',
        'action'        => 'index'
    );

    protected $controllerList = array(
        'answer', 'index', 'question', 'submit', 'tag', 'project'
    );

    /**
     * {@inheritDoc}
     */
    protected $structureDelimiter = '/';

    /**
     * {@inheritDoc}
     */
    protected function parse($path)
    {
        $matches = array();
        $parts = array_filter(explode($this->structureDelimiter, $path));

        // Set controller
        $matches = array_merge($this->defaults, $matches);
        if (isset($parts[0]) && in_array($parts[0], $this->controllerList)) {
            $matches['controller'] = $this->decode($parts[0]);
        }

        // Make Match
        if (isset($matches['controller'])) {
            switch ($matches['controller']) {
                case 'answer':
                    if (!empty($parts[1])) {
                        $matches['slug'] = $this->decode($parts[1]);
                    }
                    break;

                case 'index':
                    if (isset($parts[0]) && $parts[0] == 'order') {
                        $matches['order'] = $this->decode($parts[1]);
                    }
                    break;

                case 'question':
                    if (!empty($parts[1]) && $parts[1] == 'review') {
                        if (isset($parts[2]) && in_array($parts[2], array('confirm', 'reject'))
                            && isset($parts[3]) && !empty($parts[3])) {
                            $matches['action'] = 'review';
                            $matches['status'] = $this->decode($parts[2]);
                            $matches['id'] = intval($parts[3]);
                        }
                    } elseif (!empty($parts[1])) {
                        $matches['slug'] = $this->decode($parts[1]);
                    }
                    break;

                case 'project':
                    if (!empty($parts[1])) {
                        $matches['slug'] = $this->decode($parts[1]);
                    }
                    break;

                case 'submit':
                    if (!empty($parts[1])) {
                        $matches['slug'] = $this->decode($parts[1]);
                    }
                    break;

                case 'tag':
                    switch ($parts[1]) {
                        case 'term':
                            $matches['action'] = 'term';
                            if (!empty($parts[2])) {
                                $matches['slug'] = $this->decode($parts[2]);
                            }
                            break;
                        
                        case 'list':
                            $matches['action'] = 'list';
                            break;
                    }
                    break;
            }
        }

        /* echo '<pre>';
        print_r($matches);
        echo '</pre>';

        echo '<pre>';
        print_r($parts);
        echo '</pre>'; */

        return $matches;
    }

    /**
     * assemble(): Defined by Route interface.
     *
     * @see    Route::assemble()
     * @param  array $params
     * @param  array $options
     * @return string
     */
    public function assemble(
        array $params = array(),
        array $options = array()
    ) {
        $mergedParams = array_merge($this->defaults, $params);
        if (!$mergedParams) {
            return $this->prefix;
        }

        if (!empty($mergedParams['module'])) {
            $url['module'] = $mergedParams['module'];
        }
        if (!empty($mergedParams['controller']) && $mergedParams['controller'] != 'index') {
            $url['controller'] = $mergedParams['controller'];
        }
        if (!empty($mergedParams['action']) && $mergedParams['action'] != 'index') {
            $url['action'] = $mergedParams['action'];
        }
        if (!empty($mergedParams['status'])) {
            $url['status'] = $mergedParams['status'];
        }
        if (!empty($mergedParams['slug'])) {
            $url['slug'] = urlencode($mergedParams['slug']);
        }
        if (!empty($mergedParams['id'])) {
            $url['id'] = $mergedParams['id'];
        }
        if (!empty($mergedParams['order'])) {
            $url['order'] = 'order' . $this->paramDelimiter . $mergedParams['order'];
        }

        // Make url
        $url = implode($this->paramDelimiter, $url);

        if (empty($url)) {
            return $this->prefix;
        }
        return $this->paramDelimiter . $url;
    }
}