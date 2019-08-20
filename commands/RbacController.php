<?php

namespace app\commands;

use app\rbac\UserRule;
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a post';
        $auth->add($createPost);

        $readPost = $auth->createPermission('readPost');
        $readPost->description = 'Read post';
        $auth->add($readPost);

        $managePost = $auth->createPermission('managePost');
        $managePost->description = 'Manage post';
        $auth->add($managePost);

        $manageCategory = $auth->createPermission('manageCategories');
        $manageCategory->description = 'Manage category';
        $auth->add($manageCategory);

        $manageUser = $auth->createPermission('manageUser');
        $manageUser->description = 'Manage user';
        $auth->add($manageUser);

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $readPost);
        $auth->addChild($user, $createPost);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $managePost);
        $auth->addChild($admin, $manageUser);
        $auth->addChild($admin, $user);


        $auth->assign($user, 2);
        $auth->assign($admin, 1);

        $rule = new UserRule();
        $auth->add($rule);

        $manageOwnPost = $auth->createPermission('manageOwnPost');
        $manageOwnPost->description = 'manage own Post';
        $manageOwnPost->ruleName = $rule->name;
        $auth->add($manageOwnPost);

        $auth->addChild($manageOwnPost, $managePost);

        $auth->addChild($user, $manageOwnPost);
    }
}