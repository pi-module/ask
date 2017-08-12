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

namespace Module\Ask\Api;

use Pi;
use Pi\Application\Api\AbstractApi;

/*
 * Pi::api('notification', 'ask')->askQuestion();
 * Pi::api('notification', 'ask')->answerQuestion();
 * Pi::api('notification', 'ask')->reviewQuestion();
 */

class Notification extends AbstractApi
{
    public function askQuestion($question, $project)
    {
        // Set information
        $information = array(
            'title'        => $question['title'],
            'description'  => $question['text_description'],
            'user'         => $question['user']['name'],
            'time'         => $question['time_create_view'],
            'url'          => $question['questionUrl'],
            'status'       => $question['status'] == 1 ? __('Published'): __('Pending review'),
        );

        // Send notification to admin
        Pi::service('notification')->send(
            array(
                Pi::config('adminmail') => Pi::config('adminname'),
            ),
            'ask_question_admin',
            $information,
            Pi::service('module')->current()
        );

        // Send notification to project manager
        if (!empty($project)) {
            Pi::service('notification')->send(
                array(
                    $project['user']['email'] => $project['user']['name'],
                ),
                'ask_question_manager',
                $information,
                Pi::service('module')->current(),
                $project['user']['id']
            );
        }

        // Send notification to user
        Pi::service('notification')->send(
            array(
                $question['user']['email'] => $question['user']['name'],
            ),
            'ask_question_user',
            $information,
            Pi::service('module')->current(),
            $question['user']['id']
        );
    }

    public function answerQuestion($question, $answer, $project)
    {
        // Set information
        $information = array(
            'title'        => $question['title'],
            'description'  => $answer['text_description'],
            'questioner'   => $question['user']['name'],
            'user'         => $answer['user']['name'],
            'time'         => $answer['time_create_view'],
            'url'          => $question['questionUrl'],
            'status'       => $answer['status'] == 1 ? __('Published'): __('Pending review'),
        );

        // Send notification to admin
        Pi::service('notification')->send(
            array(
                Pi::config('adminmail') => Pi::config('adminname'),
            ),
            'answer_question_admin',
            $information,
            Pi::service('module')->current()
        );

        // Send notification to project manager
        if (!empty($project)) {
            Pi::service('notification')->send(
                array(
                    $project['user']['email'] => $project['user']['name'],
                ),
                'answer_question_manager',
                $information,
                Pi::service('module')->current(),
                $project['user']['id']
            );
        }

        // Send notification to user
        Pi::service('notification')->send(
            array(
                $answer['user']['email'] => $answer['user']['name'],
            ),
            'answer_question_user',
            $information,
            Pi::service('module')->current(),
            $answer['user']['id']
        );

        // Send notification to questioner
        if ($answer['uid'] != $question['uid']) {
            Pi::service('notification')->send(
                array(
                    $question['user']['email'] => $question['user']['name'],
                ),
                'answer_question_questioner',
                $information,
                Pi::service('module')->current(),
                $question['user']['id']
            );
        }
    }

    public function reviewQuestion($status, $project, $question, $mainQuestion)
    {
        // Set information
        $information = array(
            'title'        => !empty($mainQuestion) ? $mainQuestion['title'] : $question['title'],
            'description'  => !empty($mainQuestion) ? $mainQuestion['text_description'] : $question['text_description'],
            'questioner'   => !empty($mainQuestion) ? $mainQuestion['user']['name'] : '',
            'user'         => !empty($mainQuestion) ? $mainQuestion['user']['name'] : $question['user']['name'],
            'time'         => !empty($mainQuestion) ? $mainQuestion['time_create_view'] : $question['time_create_view'],
            'url'          => !empty($mainQuestion) ? $mainQuestion['questionUrl'] : $question['questionUrl'],
            'status'       => $status == 'confirm' ? __('Confirmed'): __('Rejected'),

        );

        // Send notification to admin
        Pi::service('notification')->send(
            array(
                Pi::config('adminmail') => Pi::config('adminname'),
            ),
            'review_question_admin',
            $information,
            Pi::service('module')->current()
        );

        // Send notification to user
        Pi::service('notification')->send(
            array(
                $question['user']['email'] => $question['user']['name'],
            ),
            'review_question_user',
            $information,
            Pi::service('module')->current(),
            $question['user']['id']
        );

        // Send notification to questioner
        if (!empty($mainQuestion)) {
            Pi::service('notification')->send(
                array(
                    $mainQuestion['user']['email'] => $mainQuestion['user']['name'],
                ),
                'review_question_questioner',
                $information,
                Pi::service('module')->current(),
                $mainQuestion['user']['id']
            );
        }
    }
}