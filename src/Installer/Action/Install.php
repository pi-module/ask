<?php
/**
 * Pi module installer action
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
 * @author          Hossein Azizabadi <azizabadi@faragostaresh.com>
 * @since           3.0
 * @package         Module\Ask
 * @subpackage      Installer
 * @version         $Id$
 */

namespace Module\Ask\Installer\Action;
use Pi;
use Pi\Application\Installer\Action\Install as BasicInstall;
use Zend\EventManager\Event;

class Install extends BasicInstall
{
    protected function attachDefaultListeners()
    {
        $events = $this->events;
        $events->attach('install.pre', array($this, 'preInstall'), 1000);
        $events->attach('install.post', array($this, 'postInstall'), 1);
        parent::attachDefaultListeners();
        return $this;
    }

    public function preInstall(Event $e)
    {
        $result = array(
            'status' => true,
            'message' => sprintf('Called from %s', __METHOD__),
        );
        $e->setParam('result', $result);
    }

    public function postInstall(Event $e)
    {
        // Set model
        $model = Pi::model($module = $e->getParam('module') . '/question');

        // Add question
        $data = array(
            'type' => 'Q',
            'author' => Pi::registry('user')->id,
            'status' => '1',
            'create' => time(),
            'update' => time(),
            'title' => __('This is a sample question'),
            'slug' => __('this-is-a-sample-question'),
            'content' => __('Sample question for test new ask module instaltion. you can remove this question after install'),
        );
        $model->insert($data);

        // Result
        $result = array(
            'status'    => true,
            'message'   => __('Sample question added.'),
        );
        $this->setResult('post-install', $result);
    }
}