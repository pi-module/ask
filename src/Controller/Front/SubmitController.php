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
namespace Module\Ask\Controller\Front;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Pi\Filter;
use Module\Ask\Form\AskForm;
use Module\Ask\Form\AskFilter;
use Zend\Json\Json;

class SubmitController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Check ask
        if (!$config['question_ask']) {
            $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
            $this->jump($url, __('Ask question not active'), 'error');
        }
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Set form
        $form = new AskForm('Ask');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new AskFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Tag
                if (!empty($values['tag'])) {
                    $tag = explode('|', $values['tag']);
                    $values['tag'] = json::encode($tag);
                }
                // Set time
                $values['time_create'] = time();
                $values['time_update'] = time();
                // Set slug
                $filter = new Filter\Slug;
                $values['slug'] = $filter($values['title'] . ' ' . _date($values['time_create']));
                // Set seo_title
                $filter = new Filter\HeadTitle;
                $values['seo_title'] = $filter($values['title']);
                // Set seo_keywords
                $filter = new Filter\HeadKeywords;
                $filter->setOptions(array(
                    'force_replace_space' => true,
                ));
                $values['seo_keywords'] = $filter($values['title']);
                // Set seo_description
                $filter = new Filter\HeadDescription;
                $values['seo_description'] = $filter($values['title']);
                // Set info
                $values['uid'] = Pi::user()->getId();
                $values['status'] = $this->config('auto_approval');
                $values['type'] = 'Q';
                // Save values
                $row = $this->getModel('question')->createRow();
                $row->assign($values);
                $row->save();
                // Tag
                if (isset($tag) && is_array($tag) && Pi::service('module')->isActive('tag')) {
                    Pi::service('tag')->add($this->params('module'), $row->id, '', $tag);
                }
                // Check it save or not
                if ($this->config('auto_approval')) {
                    $message = __('Your ask new question successfully, Other users can view and answer it');                 
                } else {
                    $message = __('Your ask new question successfully, But it need review and publish by website admin');                  
                }
                $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
                $this->jump($url, $message);
            }
        }
        // Set header and title
        $title = __('Ask a new Question');
        // Set seo_keywords
        $filter = new Filter\HeadKeywords;
        $filter->setOptions(array(
            'force_replace_space' => true
        ));
        $seoKeywords = $filter($title);
        // Set view
        $this->view()->headTitle($title);
        $this->view()->headDescription($title, 'set');
        $this->view()->headKeywords($seoKeywords, 'set');
        $this->view()->setTemplate('submit_index');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', $title);
    }

    public function searchAction()
    {
        /* if ($this->request->isPost()) {
            $params = $this->params()->fromPost();
            if (isset($params['key']) && !empty($params['key'])) {
                // Get info
                $order = array('point DESC', 'id DESC');
                $columns = array('id', 'title', 'slug');
                $where = array('status' => 1, 'type' => 'Q', 'title LIKE ?' => $params['key'] . '%');
                // Set select
                $select = $this->getModel('question')->select()->columns($columns)->where($where)->order($order)->limit(10);
                $rowset = $this->getModel('question')->selectWith($select);
                foreach ($rowset as $row) {
                    $question[$row->id] = $row->toArray();
                    $result[] = $question[$row->id]['title'];
                }
                // return result
                echo Json::encode($result);
            }
        } */
    }
}