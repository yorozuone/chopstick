<?php
namespace app\controller;

use \core\auth;
use \core\csrf;
use \core\db;
use \core\fieldset;
use \core\group;
use \core\input;
use \core\response;
use \core\roll;
use \core\url;
use \core\user;
use \core\validation;

use \app\page;

class install extends \app\controller
{
    private $field = null;
    private $validation = null;
    // ********************************************************************************
    // ****
    // **** アクション
    // ****
    // ********************************************************************************
    //
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        //
        if (file_exists(CS_BASE_DIR.'installed') == true)
        {
            die('起動できません');
        }
        touch(CS_BASE_DIR.'installed');
die();
        $this->field = new field();
        $this->validation = new validation();
        $this->validation->field = $this->field;
        //
        $this->field->append('admin_username',  '管理者ユーザー名');
        $this->field->append('admin_password',  '管理者パスワード');
        $this->field->append('admin_email',     '管理者メールアドレス');
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
            echo $this->render('controller/install/edit.twig');
    }
    // --------------------------------------------------------------------------------
    // 更新
    // --------------------------------------------------------------------------------
    public function action_update($params)
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/admin/auth/login'));
        }
        $this->field->post();
        //
        $this->validation->check_rule('admin_username', 'required');
        $this->validation->check_rule('admin_username', 'alnum');
        $this->validation->check_rule('admin_password', 'required');
        $this->validation->check_rule('admin_password', 'password');
        $this->validation->check_rule('admin_email',    'required');
        $this->validation->check_rule('admin_email',    'email');
        //
        if ($this->validation->is_valid)
        {
            // テーブル削除
            $this->drop_table();
            // テーブル作成
            self::create_table();
            // 各種データ作成
            self::create_roll();
            self::create_group();
            self::create_group_roll();
            self::create_user();
            self::create_page();
            //
            echo $this->render('controller/install/result.twig');
        }
        else 
        {
            $this->display();
        }
    }
    // ********************************************************************************
    // ****
    // **** 表示
    // ****
    // ********************************************************************************
    //
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    public function display()
    {
        $vars = array
        (
            'values'            => $this->field->get_values(),
            'error_messages'    => $this->field->get_error_messages(),
        );
        echo $this->render('controller/install/edit.twig', $vars);
    }
    // --------------------------------------------------------------------------------
    // テーブル削除
    // --------------------------------------------------------------------------------
    private function drop_table()
    {
        $con = new db();
        //
        $con->query('DROP TABLE `cs_category`;');
        $con->query('DROP TABLE `cs_page`;');
        $con->query('DROP TABLE `cs_page_category`;');
        $con->query('DROP TABLE `cs_page_tag`;');
        $con->query('DROP TABLE `cs_tag`;');
        $con->query('DROP TABLE `cs_group`;');
        $con->query('DROP TABLE `cs_group_roll`;');
        $con->query('DROP TABLE `cs_roll`;');
        $con->query('DROP TABLE `cs_user`;');
    }
    // --------------------------------------------------------------------------------
    // テーブル作成
    // --------------------------------------------------------------------------------
    private function create_table()
    {
        $con = new db();
        //
        $sql = <<< EOT
CREATE TABLE `cs_category` (
    `category_id` INT(11) NOT NULL AUTO_INCREMENT,
    `parent_category_id` INT(11) NOT NULL,
    `caption` VARCHAR(255) NOT NULL,
    `permanent_name` VARCHAR(255) NOT NULL,
    `order_at` INT(11) NOT NULL,
    `created_at` INT(11) NOT NULL,
    `updated_at` INT(11) NOT NULL,
    PRIMARY KEY (`category_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
EOT;
        $con->query($sql);
        //
        $sql = <<< EOT
CREATE TABLE `cs_page` (
    `page_id` INT(11) NOT NULL AUTO_INCREMENT,
    `parent_page_id` INT(11) NOT NULL DEFAULT '0',
    `publish_type` INT(11) NOT NULL DEFAULT '1',
    `publish_start` DATETIME NULL DEFAULT NULL,
    `publish_end` DATETIME NULL DEFAULT NULL,
    `publish_navi_` INT(11) NOT NULL DEFAULT '1',
    `publish_navi` INT(11) NOT NULL DEFAULT '1',
    `publish_list` INT(11) NOT NULL DEFAULT '1',
    `page_type` INT(11) NOT NULL DEFAULT '1',
    `title` VARCHAR(255) NULL DEFAULT NULL,
    `permanent_name` VARCHAR(512) NULL DEFAULT NULL,
    `external_link` VARCHAR(255) NULL DEFAULT NULL,
    `content2` TEXT NULL,
    `content` TEXT NULL,
    `order_at` INT(11) NULL DEFAULT NULL,
    `created_at` INT(11) NULL DEFAULT NULL,
    `updated_at` INT(11) NULL DEFAULT NULL,
    PRIMARY KEY (`page_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
EOT;
        $con->query($sql);
        //
        $sql = <<< EOT
        CREATE TABLE `cs_page_category` (
            `page_id` INT(11) NOT NULL,
            `category_id` INT(11) NOT NULL,
            `created_at` INT(11) NOT NULL,
            `updated_at` INT(11) NOT NULL,
            PRIMARY KEY (`page_id`, `category_id`)
        )
        COLLATE='utf8_general_ci'
        ENGINE=InnoDB
        ;
EOT;
        $con->query($sql);
        //
        $sql = <<< EOT
        CREATE TABLE `cs_page_tag` (
            `page_id` INT(11) NOT NULL,
            `tag_id` INT(11) NOT NULL,
            `created_at` INT(11) NOT NULL,
            `updated_at` INT(11) NOT NULL,
            PRIMARY KEY (`page_id`, `tag_id`)
        )
        COLLATE='utf8_general_ci'
        ENGINE=InnoDB
        ;
EOT;
        $con->query($sql);
        // ----------------------------------------
        //
        // ----------------------------------------
        $sql = <<< EOT
CREATE TABLE `cs_tag` (
    `tag_id` INT(11) NOT NULL AUTO_INCREMENT,
    `caption` VARCHAR(50) NOT NULL,
    `created_at` INT(11) NOT NULL,
    `updated_at` INT(11) NOT NULL,
    PRIMARY KEY (`tag_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
EOT;
        // ----------------------------------------
        //
        // ----------------------------------------
        $con->query($sql);
        $sql = <<< EOT
CREATE TABLE `cs_group` (
    `group_key` VARCHAR(255) NOT NULL,
    `caption` VARCHAR(255) NULL DEFAULT NULL,
    `description` VARCHAR(255) NULL DEFAULT NULL,
    `created_at` INT(11) NULL DEFAULT NULL,
    `updated_at` INT(11) NULL DEFAULT NULL,
    PRIMARY KEY (`group_key`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
EOT;
        $con->query($sql);
        // ----------------------------------------
        //
        // ----------------------------------------
        $sql = <<< EOT
CREATE TABLE `cs_group_roll` (
    `group_key` VARCHAR(255) NOT NULL,
    `roll_key` VARCHAR(255) NOT NULL,
    `created_at` INT(11) NOT NULL,
    `updated_at` INT(11) NOT NULL,
    PRIMARY KEY (`group_key`, `roll_key`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
EOT;
        $con->query($sql);
        // ----------------------------------------
        //
        // ----------------------------------------
        $sql = <<< EOT
CREATE TABLE `cs_roll` (
    `roll_key` VARCHAR(255) NOT NULL,
    `caption` VARCHAR(255) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `created_at` INT(11) NOT NULL,
    `updated_at` INT(11) NOT NULL,
    PRIMARY KEY (`roll_key`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
EOT;
        $con->query($sql);
        // ----------------------------------------
        //
        // ----------------------------------------
        $sql = <<< EOT
CREATE TABLE `cs_user` (
    `user_id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password_hash` VARCHAR(64) NOT NULL,
    `email` VARCHAR(128) NOT NULL,
    `group_key` VARCHAR(255) NOT NULL,
    `created_at` INT(11) NOT NULL,
    `updated_at` INT(11) NOT NULL,
    PRIMARY KEY (`user_id`),
    UNIQUE INDEX `username` (`username`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
EOT;
        $con->query($sql);
    }
    // --------------------------------------------------------------------------------
    // ロール作成
    // --------------------------------------------------------------------------------
    private function create_roll()
    {
        $field = new field();
        //
        $field->append('roll_key');
        $field->append('caption');
        $field->append('description');
        //
        $roll = array
        (
            array
            (
                'roll_key' => 'full',
                'caption' => 'フルコントロール',
                'description' => 'フルコントロールできる権限',
            ),
            array
            (
                'roll_key' => 'page_create',
                'caption' => 'ページ作成',
                'description' => 'ページを作成できる権限',
            ),
            array
            (
                'roll_key' => 'page_update',
                'caption' => 'ページ更新',
                'description' => 'ページを更新できる権限',
            ),
            array
            (
                'roll_key' => 'page_delete',
                'caption' => 'ページ削除',
                'description' => 'ページを削除できる権限',
            ),
            array
            (
                'roll_key' => 'page_publish',
                'caption' => 'ページ公開',
                'description' => 'ページを公開できる権限',
            ),
        );
        foreach($roll as $v)
        {
            $field->set_values($v);
            roll::create($field);
        }
    }
    // --------------------------------------------------------------------------------
    // グループ作成
    // --------------------------------------------------------------------------------
    private function create_group()
    {
        $field = new field();
        //
        $field->append('group_key');
        $field->append('caption');
        $field->append('description');
        //
        $group = array
        (
            array
            (
                'group_key' => 'admin',
                'caption' => '管理者',
                'description' => 'すべての操作が許される権限',
            ),
            array
            (
                'group_key' => 'editor',
                'caption' => '編集者',
                'description' => 'ページの作成、編集、削除、公開が可能な権限',
            ),
            array
            (
                'group_key' => 'writer',
                'caption' => 'ライター',
                'description' => 'ページの作成、編集が可能な権限',
            )
        );
        foreach($group as $v)
        {
            $field->set_values($v);
            group::create($field);
        }
    }
    // --------------------------------------------------------------------------------
    // グループ・ロール組み合わせ作成
    // --------------------------------------------------------------------------------
    private function create_group_roll()
    {
        $field = new field();
        //
        $field->append('group_key');
        $field->append('roll_key');
        //
        $group_roll = array
        (
            array
            (
                'group_key' => 'admin',
                'roll_key' => array
                (
                    'full',
                ),
            ),
            array
            (
                'group_key' => 'editor',
                'roll_key' => array
                (
                    'page_create',
                    'page_update',
                    'page_delete',
                    'page_publish',
                ),
            ),
            array
            (
                'group_key' => 'writer',
                'roll_key' => array
                (
                    'page_create',
                    'page_update',
                ),
            ),
        );
        foreach($group_roll as $v1)
        {
            foreach($v1['roll_key'] as $v2)
            {
                $field->set_value('group_key',  $v1['group_key']);
                $field->set_value('roll_key',   $v2);
                group::add_roll($field);
            }            
        }
    }
    // --------------------------------------------------------------------------------
    // ユーザー作成
    // --------------------------------------------------------------------------------
    private function create_user()
    {
        $field = new field();
        //
        $field->append('username',  '', $this->field->get_value('admin_username'));
        $field->append('password',  '', $this->field->get_value('admin_password'));
        $field->append('email',     '', $this->field->get_value('admin_email'));
        $field->append('group_key', '', 'damin');
        //
        user::create($field);
    }
    // --------------------------------------------------------------------------------
    // ページ作成
    // --------------------------------------------------------------------------------
    private function create_page()
    {
        $field = new field();
        //
        $field->append('parent_page_id',    '', 0);
        $field->append('page_type',         '', 1);
        $field->append('title',             '', 'home');
        $field->append('publish_type',      '', '1');
        $field->append('publish_start',     '', '');
        $field->append('publish_end',       '', '');
        $field->append('publish_navi',      '', 1);
        $field->append('publish_list',      '', 1);
        $field->append('permanent_name',    '', 'home');
        $field->append('external_link',     '', '');
        $field->append('category_ids',      'カテゴリ');
        $field->append('tags',              'タグ');
        $field->append('content',           '', 'chopstick cms に、ようこそ');
        $field->append('order_at',          '', 1);
        //
        page::create($field);
    }
}
