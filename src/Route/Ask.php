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
        'answer', 'index', 'question', 'submit', 'tag'
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
                    if (!empty($parts[1])) {
                        $matches['slug'] = $this->decode($parts[1]);
                    }
                    break;

                case 'tag':
                    switch ($parts[1]) {
                        case 'term':
                            $matches['action'] = 'term';
                            if (!empty($parts[2])) {
                                $matches['slug'] = urldecode($parts[2]);
                            }
                            break;
                        
                        case 'list':
                            $matches['action'] = 'list';
                            break;
                    }

                    break;
            }
        }

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
        if (!empty($mergedParams['slug'])) {
            $url['slug'] = $mergedParams['slug'];
        }
        if (!empty($mergedParams['order'])) {
            $url['order'] = 'order' . $this->paramDelimiter . $mergedParams['order'];
        }
        if (!empty($mergedParams['id'])) {
            $url['id'] = $mergedParams['id'];
        }

        // Make url
        $url = implode($this->paramDelimiter, $url);

        if (empty($url)) {
            return $this->prefix;
        }
        return $this->paramDelimiter . $url;
    }
}