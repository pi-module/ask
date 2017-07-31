CREATE TABLE `{question}` (
  `id`                INT(10) UNSIGNED     NOT NULL AUTO_INCREMENT,
  `type`              ENUM ('Q', 'A')      NOT NULL DEFAULT 'Q',
  `question_id`       INT(10) UNSIGNED     NOT NULL DEFAULT '0',
  `project_id`        INT(10) UNSIGNED     NOT NULL DEFAULT '0',
  `answer`            SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
  `uid`               INT(10) UNSIGNED     NOT NULL DEFAULT '0',
  `point`             INT(10)              NOT NULL DEFAULT '0',
  `count`             INT(10) UNSIGNED     NOT NULL DEFAULT '0',
  `favorite`          INT(10) UNSIGNED     NOT NULL DEFAULT '0',
  `hits`              INT(10) UNSIGNED     NOT NULL DEFAULT '0',
  `status`            TINYINT(1) UNSIGNED  NOT NULL DEFAULT '0',
  `time_create`       INT(10) UNSIGNED     NOT NULL DEFAULT '0',
  `time_update`       INT(10) UNSIGNED     NOT NULL DEFAULT '0',
  `title`             VARCHAR(255)         NOT NULL DEFAULT '',
  `slug`              VARCHAR(255)         NOT NULL DEFAULT '',
  `text_description`  TEXT,
  `tag`               VARCHAR(255)         NOT NULL DEFAULT '',
  `seo_title`         VARCHAR(255)         NOT NULL DEFAULT '',
  `seo_keywords`      VARCHAR(255)         NOT NULL DEFAULT '',
  `seo_description`   VARCHAR(255)         NOT NULL DEFAULT '',
  `main_image`        INT(10) UNSIGNED     NOT NULL DEFAULT '0',
  `additional_images` TEXT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `question_id` (`question_id`),
  KEY `title` (`title`),
  KEY `status` (`status`),
  KEY `time_create` (`time_create`),
  KEY `hits` (`hits`),
  KEY `point` (`point`),
  KEY `uid` (`uid`),
  KEY `question_list` (`status`, `type`),
  KEY `answer_list` (`status`, `question_id`, `type`),
  KEY `submit_list` (`status`, `type`, `title`),
  KEY `author_list` (`status`, `type`, `uid`),
  KEY `order_id` (`time_create`, `id`),
  KEY `order_point` (`point`, `id`),
  KEY `order_hits` (`hits`, `id`),
  KEY `order_answer` (`answer`, `id`)
);

CREATE TABLE `{project}` (
  `id`               INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `title`            VARCHAR(255)        NOT NULL DEFAULT '',
  `slug`             VARCHAR(255)        NOT NULL DEFAULT '',
  `text_description` TEXT,
  `time_create`      INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `time_update`      INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `status`           TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `seo_title`        VARCHAR(255)        NOT NULL DEFAULT '',
  `seo_keywords`     VARCHAR(255)        NOT NULL DEFAULT '',
  `seo_description`  VARCHAR(255)        NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `status` (`status`),
  KEY `time_create` (`time_create`)
);