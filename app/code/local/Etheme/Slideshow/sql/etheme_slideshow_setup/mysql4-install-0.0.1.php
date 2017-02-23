<?php


$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$slidersTable = $installer->getTable('etheme_slideshow/slider');
$slidesTable = $installer->getTable('etheme_slideshow/slide');
$installer->startSetup();

$demoData = <<<DEMODATA
INSERT INTO $slidersTable (`id`, `name`, `identifier`, `store_id`, `slides_count`, `params`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'idstore', 'idstore', '0', 4, '{"params":{"slider_type":"fullwidth","grid_width":"1200","grid_height":"480","delay":"9000","touch_enabled":"1","stop_on_hover":"1","shuffle":"0","stop_slider":"0","slider_position":"center","margin_left":"","margin_right":"","margin_top":"","margin_bottom":"20","shadow_type":"0","show_timerline":"1","timerline_position":"bottom","background_color":"#fff","padding":"","show_bg_image":"0","hide_slider_under":"500","hide_defined_layers_under":"700","hide_all_layers_under":"800","navigation_type":"none","navigation_arrows":"solo","navigation_style":"round","navigation_always_on":"1","hide_thumbs":"","leftarrow_align_hor":"left","leftarrow_align_vert":"center","leftarrow_offset_hor":"0","leftarrow_offset_vert":"0","rightarrow_align_hor":"right","rightarrow_align_vert":"center","rightarrow_offset_hor":"","rightarrow_offset_vert":"","thumb_width":"100","thumb_height":"50","thumb_amount":"5"}}', 1, '2013-07-14 23:42:11', '2013-07-29 02:21:14');

INSERT INTO $slidesTable (`id`, `slider_id`, `name`, `position`, `params`, `layers`, `is_active`, `created_at`, `updated_at`) VALUES
    (1, 1, 'slide1', 1, '{"position":"1","transition":"slidehorizontal","delay":"8000","slot_amount":"7","rotation":"","full_width_cent":"","transition_duration":"300","bg":"etheme\\\/slideshow\\\/slider_1\\\/slide_1_bg.jpg","enable_link":"0"}', '{"layers":{"3":{"is_delete":"","previous_type":"text","id":"3","layer_id":"","name":"flexible idstore","type":"text","appearance_time":"500","order":"1","xoffset":"48","yoffset":"133","speed":"300","animation":"lft","easing":"easeOutExpo","style":"main-caption","text":"flexible \\\u00a0 idstore"},"2":{"is_delete":"","previous_type":"text","id":"2","layer_id":"","name":" IDStore has","type":"text","appearance_time":"900","order":"2","xoffset":"15","yoffset":"233","speed":"300","animation":"sfl","easing":"easeOutExpo","style":"main-text","text":" IDStore has the  unique and modern design of the layout, that is<br>the best start point for professional site creation."},"1":{"is_delete":"","previous_type":"text","id":"1","layer_id":"","name":"Purchase Now","type":"text","appearance_time":"1300","order":"3","xoffset":"247","yoffset":"319","speed":"300","animation":"lfb","easing":"easeOutExpo","style":"noshadow","text":"<a href=\\\"http:\\\/\\\/themeforest.net\\\/item\\\/idstore-responsive-multipurpose-ecommerce-theme\\\/4792186?ref=8theme\\\" class=\\\"button active\\\">Purchase Now<\\\/a>"}}}', 1, '2013-07-15 01:22:51', '2013-07-29 00:53:55'),
    (2, 1, 'slide2', 2, '{"position":"2","transition":"slidehorizontal","delay":"8000","slot_amount":"7","rotation":"","full_width_cent":"","transition_duration":"300","bg":"etheme\\\/slideshow\\\/slider_1\\\/slide_2_bg.jpg","enable_link":"0"}', '{"layers":{"11":{"is_delete":"","previous_type":"image","id":"11","layer_id":"","name":"iphone","type":"image","appearance_time":"2300","order":"7","xoffset":"580","yoffset":"315","speed":"450","animation":"lfr","easing":"easeOutExpo","image":"etheme\\\/slideshow\\\/slider_1\\\/slide_2_layer_11.png"},"10":{"is_delete":"","previous_type":"image","id":"10","layer_id":"","name":"ipad","type":"image","appearance_time":"2033","order":"6","xoffset":"628","yoffset":"220","speed":"450","animation":"lfr","easing":"easeOutExpo","image":"etheme\\\/slideshow\\\/slider_1\\\/slide_2_layer_10.png"},"9":{"is_delete":"","previous_type":"image","id":"9","layer_id":"","name":"macbook","type":"image","appearance_time":"1722","order":"5","xoffset":"805","yoffset":"207","speed":"450","animation":"lfr","easing":"easeOutExpo","image":"etheme\\\/slideshow\\\/slider_1\\\/slide_2_layer_9.png"},"8":{"is_delete":"","previous_type":"text","id":"8","layer_id":"","name":"readmore","type":"text","appearance_time":"1422","order":"3","xoffset":"237","yoffset":"320","speed":"450","animation":"lfl","easing":"easeInCirc","style":"","text":"<a href=\\\"http:\\\/\\\/themeforest.net\\\/item\\\/idstore-responsive-multipurpose-ecommerce-theme\\\/4792186?ref=8theme\\\" class=\\\"button active\\\">Read More<\\\/a>"},"7":{"is_delete":"","previous_type":"image","id":"7","layer_id":"","name":"imac","type":"image","appearance_time":"1211","order":"4","xoffset":"679","yoffset":"32","speed":"450","animation":"lfr","easing":"easeOutExpo","image":"etheme\\\/slideshow\\\/slider_1\\\/slide_2_layer_7.png"},"6":{"is_delete":"","previous_type":"text","id":"6","layer_id":"","name":"HIGH-QUALITY THEME","type":"text","appearance_time":"1100","order":"1","xoffset":"0","yoffset":"130","speed":"450","animation":"lfl","easing":"easeOutExpo","style":"main-caption","text":"HIGH-QUALITY  \\\u00a0  THEME"},"1":{"is_delete":"","previous_type":"text","id":"1","layer_id":"","name":"IDStore is","type":"text","appearance_time":"850","order":"2","xoffset":"10","yoffset":"230","speed":"450","animation":"lfl","easing":"easeOutExpo","style":"main-text-white","text":"IDStore is an ideal basis for a prosperous start or successful <br>development of your business."}}}', 1, '2013-07-15 01:38:37', '2013-07-29 02:02:53'),
    (3, 1, 'slide3', 3, '{"position":"3","transition":"slidehorizontal","delay":"8000","slot_amount":"7","rotation":"","full_width_cent":"","transition_duration":"300","bg":"etheme\\\/slideshow\\\/slider_1\\\/slide_3_bg.jpg","enable_link":"0"}', '{"layers":{"4":{"is_delete":"","previous_type":"text","id":"4","layer_id":"","name":"purchase now","type":"text","appearance_time":"2200","order":"3","xoffset":"842","yoffset":"301","speed":"300","animation":"lfb","easing":"easeOutExpo","style":"","text":"<a href=\\\"http:\\\/\\\/themeforest.net\\\/item\\\/idstore-responsive-multipurpose-ecommerce-theme\\\/4792186?ref=8theme\\\" class=\\\"button active\\\">Purchase Now<\\\/a>"},"3":{"is_delete":"","previous_type":"text","id":"3","layer_id":"","name":"IDStore Template ","type":"text","appearance_time":"1800","order":"2","xoffset":"678","yoffset":"212","speed":"450","animation":"lfl","easing":"easeOutExpo","style":"main-text","text":"IDStore Template is perfectly optimized and<br> has the compatibility with all modern browsers."},"2":{"is_delete":"","previous_type":"text","id":"2","layer_id":"","name":"Retina  & Responsive","type":"text","appearance_time":"1400","order":"1","xoffset":"627","yoffset":"127","speed":"450","animation":"lft","easing":"easeOutExpo","style":"main-caption","text":"Retina  & Responsive"},"1":{"is_delete":"","previous_type":"image","id":"1","layer_id":"","name":"hand","type":"image","appearance_time":"1000","order":"4","xoffset":"80","yoffset":"2","speed":"450","animation":"lfl","easing":"easeOutExpo","image":"etheme\\\/slideshow\\\/slider_1\\\/slide_3_layer_1.png"}}}', 1, '2013-07-15 02:45:36', '2013-07-29 02:11:52'),
    (4, 1, 'slide4', 4, '{"position":"4","transition":"slidehorizontal","delay":"9000","slot_amount":"7","rotation":"","full_width_cent":"","transition_duration":"300","bg":"etheme\\\/slideshow\\\/slider_1\\\/slide_4_bg.jpg","enable_link":"0"}', '{"layers":{"5":{"is_delete":"","previous_type":"text","id":"5","layer_id":"","name":"watch button","type":"text","appearance_time":"2000","order":"5","xoffset":"237","yoffset":"320","speed":"300","animation":"sfb","easing":"easeOutExpo","style":"","text":"<a href=\\\"http:\\\/\\\/www.youtube.com\\\/watch?v=jOJ-A7UJHyA\\\" class=\\\"button active\\\">Watch Now<\\\/a>"},"4":{"is_delete":"","previous_type":"text","id":"4","layer_id":"","name":"Our tutorials","type":"text","appearance_time":"1700","order":"4","xoffset":"-12","yoffset":"230","speed":"300","animation":"lfb","easing":"easeOutExpo","style":"main-text","text":"Lorem impsum dolor"},"3":{"is_delete":"","previous_type":"text","id":"3","layer_id":"","name":"VIDEO TUTORIALS ","type":"text","appearance_time":"1400","order":"3","xoffset":"89","yoffset":"135","speed":"300","animation":"lfb","easing":"easeOutExpo","style":"main-caption","text":"VIDEO TUTORIALS "},"2":{"is_delete":"","previous_type":"video","id":"2","layer_id":"","name":"video","type":"video","appearance_time":"601","order":"2","xoffset":"679","yoffset":"51","speed":"300","animation":"fade","easing":"easeOutExpo","url":"NtbU54uZlPg","width":"480","height":"300","args":""},"1":{"is_delete":"","previous_type":"image","id":"1","layer_id":"","name":"video_wrapper","type":"image","appearance_time":"600","order":"1","xoffset":"654","yoffset":"28","speed":"300","animation":"fade","easing":"easeOutExpo","image":"etheme\\\/slideshow\\\/slider_1\\\/slide_4_layer_1.png"}}}', 1, '2013-07-15 04:44:57', '2013-07-29 02:22:52');
DEMODATA;


$installer->run("
    DROP TABLE IF EXISTS $slidersTable;
    CREATE TABLE $slidersTable (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `identifier` VARCHAR(255) NOT NULL DEFAULT '',
    `store_id` VARCHAR(255) NOT NULL DEFAULT '0',
    `slides_count` INT(11) NOT NULL DEFAULT 0,
    `params` text,
    `is_active` BOOL NOT NULL DEFAULT 1,
    `created_at` DATETIME,
    `updated_at` DATETIME
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
    DROP TABLE IF EXISTS $slidesTable;
    CREATE TABLE $slidesTable (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `slider_id` INT(11) UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `position` INT(11) NOT NULL DEFAULT 0,
    `params` text,
    `layers` text,
    `is_active` BOOL NOT NULL DEFAULT 1,
    `created_at` DATETIME,
    `updated_at` DATETIME   
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->run($demoData);

$installer->endSetup();