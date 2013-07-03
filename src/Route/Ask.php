<?php
/**
 * Index route implementation
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
 * @author          Taiwen Jiang <taiwenjiang@tsinghua.org.cn>
 * @author          Hossein Azizabadi <azizabadi@faragostaresh.com>
 * @since           3.0
 * @package         Module\Ask
 * @subpackage      Route
 * @version         $Id$
 */

namespace Module\Ask\Route;

use Pi\Mvc\Router\Http\Standard;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\Stdlib\RequestInterface as Request;

/**
 * sample url
 *
 */
class Ask extends Standard
{
    protected $prefix = '/ask';

    /**
     * Default values.
     *
     * @var array
     */
    protected $defaults = array(
        'module' => 'ask',
        'controller' => 'index',
        'action' => 'index',
    );

    /**
     * match(): defined by Route interface.
     *
     * @see    Route::match()
     * @param  Request $request
     * @return RouteMatch
     */
    public function match(Request $request, $pathOffset = null)
    {
        $result = $this->canonizePath($request, $pathOffset);
        if (null === $result) {
            return null;
        }
        list($path, $pathLength) = $result;
        if (empty($path)) {
            return null;
        }

        // Get path
        $controller = explode($this->paramDelimiter, $path, 2);

        // Set controller
        if (isset($controller[0]) && in_array($controller[0], array('answer', 'index', 'list', 'profile', 'question', 'submit', 'tag', 'order'))) {
            $matches['controller'] = urldecode($controller[0]);
        } elseif (isset($controller[0]) && $controller[0] == 'page') {
            $matches['page'] = intval($controller[1]);
            $matches['controller'] = 'index';
        }

        // Make Match
        if (isset($matches['controller'])) {
            switch ($matches['controller']) {
                case 'answer':
                    if (!empty($controller[1])) {
                        $answerPath = explode($this->paramDelimiter, $controller[1], 2);
                        if (isset($answerPath[0]) && $answerPath[0] == 'question') {
                            $matches['question'] = intval($answerPath[1]);
                        }
                    }
                    break;

                case 'list':
                    if (!empty($controller[1])) {
                        $listPath = explode($this->paramDelimiter, $controller[1], 4);
                        if (isset($listPath[0]) && $listPath[0] == 'order' && isset($listPath[2]) && $listPath[2] == 'page') {
                            $matches['order'] = urldecode($listPath[1]);
                            $matches['page'] = intval($listPath[3]);
                        } elseif (isset($listPath[0]) && $listPath[0] == 'order') {
                            $matches['order'] = urldecode($listPath[1]);
                        } elseif (isset($listPath[0]) && $listPath[0] == 'page') {
                            $matches['page'] = intval($listPath[1]);
                        }
                    }
                    break;

                case 'question':
                    if (!empty($controller[1])) {
                        $questionPath = explode($this->paramDelimiter, $controller[1], 2);
                        $matches['alias'] = urldecode($questionPath[0]);
                    }
                    break;

                case 'submit':
                    if (!empty($controller[1])) {
                        $submitPath = explode($this->paramDelimiter, $controller[1], 2);
                        if (in_array('search', $submitPath)) {
                            $matches['action'] = 'search';
                        }
                    }
                    break;

                case 'tag':
                    if (!empty($controller[1])) {
                        $tagPath = explode($this->paramDelimiter, $controller[1], 5);
                        $matches['alias'] = urldecode($tagPath[0]);
                        if (isset($tagPath[1]) && $tagPath[1] == 'order' && isset($listPath[1]) && $listPath[1] == 'page') {
                            $matches['order'] = urldecode($tagPath[2]);
                            $matches['page'] = intval($tagPath[4]);
                        } elseif (isset($tagPath[1]) && $tagPath[1] == 'order') {
                            $matches['order'] = urldecode($tagPath[2]);
                        } elseif (isset($tagPath[1]) && $tagPath[1] == 'page') {
                            $matches['page'] = intval($tagPath[2]);
                        }
                    }
                    break;

                case 'order':
                    if (!empty($controller[1])) {
                        $orderPath = explode($this->paramDelimiter, $controller[1], 3);
                        $matches['order'] = urldecode($orderPath[0]);
                        $matches['controller'] = 'index';
                    }
                    break;
                    
                case 'profile':
                    if (!empty($controller[1])) {
                        $profilePath = explode($this->paramDelimiter, $controller[1], 2);
                        $matches['alias'] = urldecode($profilePath[0]);
                    }
                    break;    
            }
        }
        return new RouteMatch(array_merge($this->defaults, $matches), $pathLength);
    }

    /**
     * assemble(): Defined by Route interface.
     *
     * @see    Route::assemble()
     * @param  array $params
     * @param  array $options
     * @return mixed
     */
    public function assemble(array $params = array(), array $options = array())
    {
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
        if (!empty($mergedParams['alias'])) {
            $url['alias'] = $mergedParams['alias'];
        }
        if (!empty($mergedParams['order'])) {
            $url['order'] = 'order' . $this->paramDelimiter . $mergedParams['order'];
        }
        if (!empty($mergedParams['id'])) {
            $url['id'] = $mergedParams['id'];
        }
        if (!empty($mergedParams['question'])) {
            $url['question'] = 'question' . $this->paramDelimiter . $mergedParams['question'];
        }
        if (!empty($mergedParams['page'])) {
            $url['page'] = 'page' . $this->paramDelimiter . $mergedParams['page'];
        }

        $url = implode($this->paramDelimiter, $url);

        if (empty($url)) {
            return $this->prefix;
        }
        return $this->paramDelimiter . $url;
    }
}