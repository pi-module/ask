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
namespace Module\Ask\Controller\Admin;

use Pi;
use Pi\Filter;
use Pi\Mvc\Controller\ActionController;
use Module\Ask\Form\ProjectForm;
use Module\Ask\Form\ProjectFilter;

class ProjectController extends ActionController
{
    public function indexAction()
    {
        $projects = array();
        // Set info
        $order = array('time_create DESC', 'id DESC');
        // Set select
        $select = $this->getModel('project')->select()->order($order);
        $rowset = $this->getModel('project')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $projects[$row->id] = Pi::api('project', 'ask')->canonizeProject($row);
        }
        // Set view
        $this->view()->setTemplate('project-index');
        $this->view()->assign('projects', $projects);
    }

    public function updateAction()
    {
        // Get id
        $id = $this->params('id');
        // Set form
        $form = new ProjectForm('project');
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            // Set slug
            $slug = ($data['slug']) ? $data['slug'] : $data['title'];
            $filter = new Filter\Slug;
            $data['slug'] = $filter($slug);
            // Form filter
            $form->setInputFilter(new ProjectFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Set seo_title
                $title = ($values['seo_title']) ? $values['seo_title'] : $values['title'];
                $filter = new Filter\HeadTitle;
                $values['seo_title'] = $filter($title);
                // Set seo_keywords
                $keywords = ($values['seo_keywords']) ? $values['seo_keywords'] : $values['title'];
                $filter = new Filter\HeadKeywords;
                $filter->setOptions(array(
                    'force_replace_space' => (bool)$this->config('force_replace_space'),
                ));
                $values['seo_keywords'] = $filter($keywords);
                // Set seo_description
                $description = ($values['seo_description']) ? $values['seo_description'] : $values['title'];
                $filter = new Filter\HeadDescription;
                $values['seo_description'] = $filter($description);
                // Set if new
                if (empty($values['id'])) {
                    // Set time
                    $values['time_create'] = time();
                }
                // Set time_update
                $values['time_update'] = time();
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('project')->find($values['id']);
                } else {
                    $row = $this->getModel('project')->createRow();
                }
                $row->assign($values);
                $row->save();
                // jump
                $message = __('Project data saved successfully.');
                $this->jump(array('action' => 'index'), $message);
            }
        } else {
            if ($id) {
                $project = $this->getModel('project')->find($id)->toArray();
                $form->setData($project);
            }
        }
        // Set view
        $this->view()->setTemplate('project-update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add project'));
    }
}