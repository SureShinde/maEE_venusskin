<?php
$installer = $this;

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('banner')};
CREATE TABLE {$this->getTable('banner')} (
  `id` int(11) UNSIGNED NOT NULL auto_increment,
  `blocks` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `alt` varchar(255) NOT NULL default '',
  `image` varchar(100) NOT NULL,
  `imptotal` int(11) UNSIGNED NOT NULL default '0',
  `impmade` int(11) UNSIGNED NOT NULL default '0',
  `clicks` int(11) UNSIGNED NOT NULL default '0',
  `url` varchar(200) NOT NULL default '',
  `order` int(11) UNSIGNED NOT NULL default '0',
  `startdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `enddate` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` BOOLEAN NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=innodb DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('bannerblock')};
CREATE TABLE {$this->getTable('bannerblock')} (
  `id` int(11) UNSIGNED NOT NULL auto_increment,
  `alias` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL default '',
  `display_method` ENUM('NORMAL', 'SLIDER') NOT NULL,
  `sort_method` ENUM('RANDOM', 'ORDER') NOT NULL,
  `target_method` ENUM('PARENT', 'NEW') NOT NULL,
  `num_banners` int(11) UNSIGNED NOT NULL default '1',
  `width` smallint(6) UNSIGNED NOT NULL default '300',
  `height` smallint(6) UNSIGNED NOT NULL default '200',
  `speed` smallint(6) UNSIGNED NOT NULL default '1000',
  `delay_time` int(11) UNSIGNED NOT NULL default '6000',
  `status` BOOLEAN NOT NULL default '1',
  PRIMARY KEY  (`id`),
  UNIQUE(`alias`)
) ENGINE=innodb DEFAULT CHARSET=utf8;
");

$installer->endSetup(); 