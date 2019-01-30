<?php
/**
 * 首页
 *
 * @author Administrator
 *
 */

namespace app\controllers;


use common\Cms;
use common\components\HomeController;
use common\components\PcController;
use common\models\newyear\NewyearItem;
use common\models\newyear\NewyearUser;
use common\models\newyear\NewyearWechat;
use yii\redis\Connection;
use yii\web\Cookie;
use yii\web\Request;
use Yii;

class SiteController extends HomeController
{
    const DOWN_TIME = 3;
    const GRAP_STATUS_START = 1;
    const GRAP_STATUS_RIGHT = 2;
    const GRAP_STATUS_ERROR = 3;

    public $admin_test = ['lin.zhou@yingxiong.com', 'xiang.li@yingxiong.com', 'dan.luo@yingxiong.com'];
    public $admin_proc = ['dan.luo@yingxiong.com',
        'lin.zhou@yingxiong.com',
        'jiajia.xiao@yingxiong.com'
    ];

    public $redis = '';

    public function beforeAction($action, $isNoLayout = 0)
    {
        $this->redis = Yii::$app->redis;
        return parent::beforeAction($action, $isNoLayout); // TODO: Change the autogenerated stub
    }

    public function actionCover()
    {
        return $this->render('cover');
    }

    public function actionIndex()
    {
//        if (!YII_DEBUG) {
//            echo "敬请期待！";
//            exit;
//        }
        $cookies = \Yii::$app->request->cookies;
        $cookies->readOnly = false;
        $code = $cookies->get('code');

if (YII_DEV) {
//    $_GET['code'] = 'Tcy34AXymmPRdBWB9u5g5d-bz03JsjzKYUyks70FgT8';
}


        if (!$code && !Cms::getGetValue('code')) {
            $code_url = sprintf(NewyearWechat::CODE_URL, NewyearWechat::CORP_ID, NewyearWechat::AGENT_ID);
            header('Location:'.$code_url);
            exit;
        } else {
            $code = Cms::getGetValue('code');
            $cookies->add(new Cookie([
                'name' => 'code',
                'value' => $code,
            ]));
        }


        if (!Cms::getSession('UserId')) {
            $res = NewyearWechat::getUserInfo();
        } else {
            $user = NewyearUser::getUser(Cms::getSession('UserId'));
            if (!$user) {
                $res = NewyearWechat::getUserInfo();
            } else {
                $res = ['status' => 0, 'msg' => $user->attributes];
            }
        }

        if ($res['status'] != 0) {
            Cms::setSession('UserId', '');
            return $this->renderPartial('404.html', ['status' => -1, 'msg' => $res['msg']]);
        }
//    pr($res, 1);
        if (!YII_DEV
            && Cms::getSession('UserId') != 'lin.zhou@yingxiong.com'
            && $res['msg']['department_name'] != '自研组') {
            echo "敬请期待！";
            exit;
        }
        return $this->renderPartial('index.html', $res);
    }

    /**
     * 签到
     */
    public function actionAjaxSign()
    {
        $userid = Cms::getSession('UserId');
        $user = NewyearUser::getUser($userid);
        if (!$userid || !$user) {
            $this->ajaxOutPut(['status' => -1, 'msg' => '获取数据失败，请重新进入！']);
        }
        $redis = Yii::$app->redis;
        $key = 'newyear:sign:'.$userid;
        if ($redis->exists($key)) {
            $num = $redis->get($key);
            $this->ajaxOutPut(['status' => 1, 'msg' => '您已经签到！', 'num' => $num]);
        }

        $num = $redis->incr('newyear:sign:num');
        $redis->set($key, $num);
        $user->sign = $num;
        $user->save();
        $this->ajaxOutPut(['status' => 0, 'msg' => '签到成功！', 'num' => $num]);
    }

    /**
     * 投票页面
     * @return string
     */
    public function actionVote()
    {
        $userid = Cms::getSession('UserId');
        $key = 'newyear:vote:'.$userid;
        $redis = Yii::$app->redis;
        $node = $redis->smembers($key); //获取用户投票的节目ID

        return $this->renderPartial('vote.html', ['node' => $node]);
    }

    /**
     * 投票
     */
    public function actionAjaxVote()
    {
        $userid = Cms::getSession('UserId');
        $user = NewyearUser::getUser($userid);
        if (!$userid || !$user) {
            $this->ajaxOutPut(['status' => -1, 'msg' => '获取数据失败，请重新进入！']);
        }

        $nodes = Cms::getPostValue('nodes');
        if (!$nodes || empty($nodes)) {
            $this->ajaxOutPut(['status' => -1, 'msg' => '请选择节目！']);
        }
        $nodes = array_unique($nodes);
        if (count($nodes) != 3) {
            $this->ajaxOutPut(['status' => -1, 'msg' => '请选择3个节目！']);
        }

        $redis = Yii::$app->redis;
        $key = 'newyear:vote:'.$userid;
        if ($redis->exists($key)) {
            $this->ajaxOutPut(['status' => -1, 'msg' => '您已经投票，请勿重复投票！']);
        }

        foreach ($nodes as $v) {
            if (!is_numeric($v)) {
                $this->ajaxOutPut(['status' => -1, 'msg' => '参数错误！']);
            }
        }
        $key_item = 'newyear:vote:item';
        foreach ($nodes as $v) {
            $key_node = 'newyear:vote:node:'.$v;
            $redis->sadd($key, $v);
            $num = $redis->incr($key_node);

            $redis->zincrby($key_item, 1, $v);
        }

//        $user->vote = json_encode($nodes);  //用户投票
//        $user->save();

        $this->ajaxOutPut(['status' => 0, 'msg' => '投票成功']);

    }

    /**
     * 抢答页面
     */
    public function actionGrab()
    {
        $is_admin = 0;
        $userid = Cms::getSession('UserId');

        if (!$userid) {
            return $this->renderPartial('404.html');
        }
        if (YII_DEV) {
            if (in_array($userid, $this->admin_test)) {
                $is_admin = 1;
            }
        } else {
            if (in_array($userid, $this->admin_proc)) {
                $is_admin = 1;
            }
        }

        $count = $this->redis->get('newyear:grab:count');
        $key = 'newyear:grab:count:'.$count.':status';   //第几次抢是否已经完成
        if (!$this->redis->exists($key) || $this->redis->get($key) == 1) {
            $select_answer = 1;
        } else {
            $select_answer = 0;
        }

        return $this->renderPartial('grab.html', ['is_admin' => $is_admin, 'select_answer' => $select_answer]);
    }


    /**
     * 获取倒计时时间,如果开始，由前端进行倒计时
     */
    public function actionAjaxGetTime()
    {
        $redis = Yii::$app->redis;
        $key = 'newyear:grab:down_datetime';
        $time = $redis->get($key);
        $diff = $time-time();
        if ($diff <= 0) {
            $count = $redis->get('newyear:grab:count'); //第几次开抢

            if (!$count) {
                $this->ajaxOutPut(['status' => -1, 'msg' => '还未开始，敬请期待！']);
            }
            $key = 'newyear:grab:count:'.$count.':status';   //第几次抢是否已经完成
            if ($redis->exists($key)) {

                $user = $this->redis->get('newyear:grab:count:'.$count.':user');
                $user_model = NewyearUser::getUser($user);
                $this->ajaxOutPut(['status' => 1, 'msg' => '抢答已经结束', 'user' => $user, 'user_name' => $user_model['name'], 'department_name' => $user_model['department_name']]);
            }
        }
        $this->ajaxOutPut(['status' => 0, 'msg' => '', 'time' => $diff]);
    }


    /**
     * 管理员点击开始抢答，出现倒计时
     */
    public function actionAjaxStartGrab()
    {
        $userid = Cms::getSession('UserId');

        $this->_checkPower();

        $redis = Yii::$app->redis;

        $key = 'newyear:grab:down_datetime';
        if ($redis->get($key) > time()) {
            $this->ajaxOutPut(['status' => -1, 'msg' => '倒计时还没有完成，请勿重复操作！']);
        }

        $redis->set($key, time()+self::DOWN_TIME);

        $key = 'newyear:grab:count'; //抢的次数
        $redis->incr($key);

        $this->ajaxOutPut(['status' => 0]);
    }

    /**
     * 抢
     */
    public function actionAjaxGrab()
    {
        $userid = Cms::getSession('UserId');
        $redis = Yii::$app->redis;

        $key = 'newyear:grab:down_datetime';
        $down_time = $redis->get($key); //倒计时时间
        if ($down_time > time()) {
            $this->ajaxOutPut(['status' => -1, 'msg' => '倒计时还没有完成，请稍等！']);
        }

        $key = 'newyear:grab:count'; //第几次开抢
        $count = $redis->get($key);
        if (!$count) {
            $this->ajaxOutPut(['status' => -1, 'msg' => '还未开始，敬请期待！']);
        }

        $key = 'newyear:grab:count:'.$count.':status';   //第几次抢
        if ($redis->exists($key)) {
            $user = $this->redis->get('newyear:grab:count:'.$count.':user');
            $user_model = NewyearUser::getUser($user);
            $this->ajaxOutPut(['status' => 1, 'msg' => '很遗憾，您没有抢中！', 'user' => $user, 'user_name' => $user_model['name'], 'department_name' => $user_model['department_name']]);
        }
        $res = $redis->incr($key);
        if ($res > 1) {
            $user = $this->redis->get('newyear:grab:count:'.$count.':user');
            $user_model = NewyearUser::getUser($user);
            $this->ajaxOutPut(['status' => 1, 'msg' => '很遗憾，您没有抢中！', 'user' => $user, 'user_name' => $user_model['name'], 'department_name' => $user_model['department_name']]);
        } else {
            $key = 'newyear:grab:count:'.$count.':user';  //保存第几次抢中的用户
            $redis->set($key, $userid);

            $this->ajaxOutPut(['status' => 0, 'msg' => '恭喜您，已经抢中！']);
        }

    }

    /**
     * 答对
     */
    public function actionAjaxRight()
    {
        $this->_checkPower();
        $res = $this->_checkGrapRes();
        if ($res['status'] != 0) {
            $this->ajaxOutPut($res);
        }
        $count = $res['msg'];

        $key = 'newyear:grab:count:'.$count.':status';
        $this->redis->set($key, self::GRAP_STATUS_RIGHT);

        $key = 'newyear:grab:count:'.$count.':user';  //获取抢中的用户
        $user = $this->redis->get($key);

        $key = 'newyear:grab:user'; //用户答对题目数+1
        $score = $this->redis->zscore($key, $user);
        if ($score == 'nil') {
            $score = 1;
        } else {
            $score = $score+1;
        }
        $this->redis->zadd($key, $score, $user);    //更新用户已经答对的数量

        $this->ajaxOutPut(['status' => 0]);
    }

    /**
     * 答错
     */
    public function actionAjaxError()
    {
        $this->_checkPower();
        $res = $this->_checkGrapRes();
        if ($res['status'] != 0) {
            $this->ajaxOutPut($res);
        }
        $count = $res['msg'];

        $key = 'newyear:grab:count:'.$count.':status';
        $this->redis->set($key, self::GRAP_STATUS_ERROR);
        $this->ajaxOutPut(['status' => 0]);
    }


    /**
     * 验证抢答的结果
     * @return array
     */
    private function _checkGrapRes()
    {
        $key = 'newyear:grab:count';
        $count = $this->redis->get($key); //第几轮开抢
        if (!$count) {
            return ['status' => -1, 'msg' => '抢答暂未开始，敬请期待！'];
        }

        $key = 'newyear:grab:count:'.$count.':status';

        if (!$this->redis->exists($key)) {
            return ['status' => -1, 'msg' => '还未有人抢答，请稍后再操作！'];
        }

        if ($this->redis->get($key) != self::GRAP_STATUS_START) {
            return ['status' => -1, 'msg' => '结果已出，请勿重复点击！'];
        }
        return ['status' => 0, 'msg' => $count];
    }

    /**
     * 检查管理员权限
     */
    private function _checkPower()
    {
        $userid = Cms::getSession('UserId');
        if (YII_DEV) {
            if (!in_array($userid, $this->admin_test)) {
                $this->ajaxOutPut(['status' => -1, 'msg' => '您没权进行此项操作！']);
            }
        } else {
            if (!in_array($userid, $this->admin_proc)) {
                $this->ajaxOutPut(['status' => -1, 'msg' => '您没权进行此项操作！']);
            }
        }
    }

    /**
     * 测试清空投票
     */
    public function actionEmptyVote()
    {
        $userid = Cms::getSession('UserId');
        if (YII_DEV) {
            if ($userid == 'xiang.li@yingxiong.com' || $userid == 'lin.zhou@yingxiong.com' || 'dan.luo@yingxiong.com') {
                $this->redis->del('newyear:vote:'.$userid);
            }
        }
    }

    public function action404()
    {
        return $this->renderPartial('404.html');
    }

    public function actionError()
    {
        return $this->renderPartial('404.html');
    }

    /**
     * 获取节目的投票数
     */
    public function actionGetVote()
    {
        $pass = md5(Cms::getGetValue('pass'));
        if ($pass != 'b927a08272f0030a8f9fd23366f81f7d') {
            echo '密码错误';exit;
        }

        $data = [];
        for ($i = 1; $i <= 20; $i++) {
            $key = "newyear:vote:node:".$i;
            if (!$this->redis->exists($key)) {
                break;
            }
            $data[$i] = $this->redis->get("newyear:vote:node:".$i);
        }
        arsort($data);
        pr($data);
    }

    /**
     * 获取用户签到名次
     */
    public function actionGetSign()
    {
        $pass = md5(Cms::getGetValue('pass'));
        $userid = Cms::getGetValue('email');
        if (!$userid) {
            echo '请输入用户邮箱';exit;
        }
        if ($pass != 'b927a08272f0030a8f9fd23366f81f7d') {
            echo '密码错误';exit;
        }
        $key = 'newyear:sign:'.$userid;
        if (!$this->redis->exists($key)) {
            echo "该用户不存在";exit;
        }
        echo $this->redis->get($key);
    }

    /**
     * 获取所有用户抢对的次数
     */
    public function actionGetGrabRank()
    {
        $pass = md5(Cms::getGetValue('pass'));
        if ($pass != 'b927a08272f0030a8f9fd23366f81f7d') {
            echo '密码错误';exit;
        }
        $res = $this->redis->zrevrange('newyear:grab:user', 0, -1, 'WITHSCORES');
        $data = [];
        foreach ($res as $k => $v) {
            if ($k % 2 == 0) {
                $user = NewyearUser::getUser($v);
                $name = $user ? $user['name'] : $v;
                $data[] = $name."->".$res[$k+1];
            }
        }
        pr($data,1);
    }

    public function actionAbTest()
    {
        $this->redis->incr('newyear:ab:test');
    }

    public function actionTest()
    {

        return $this->renderPartial('grab.html', ['is_admin' => 1]);

        if (YII_DEV) {
            Cms::setSession('UserId', 'lin.zhou@yingxiong.com');
        }
        echo 333;exit;
        $_POST['nodes'] = [1, 6, 7];
        $this->actionAjaxVote();
    }
}
