<?php
/**
 * Created by PhpStorm.
 * User: lin.zhou
 * Date: 2019/1/14 0014
 * Time: 14:15
 */

namespace app\controllers;


use common\cache\PublicCache;
use common\cache\sm\SmPraiseCache;
use common\Cms;
use common\components\Api;
use common\components\HomeController;
use common\models\GiftCode;
use common\models\GiftCodeLog;
use common\models\UserCenter;
use common\models\UserCenterData;
use common\models\YuyueStatModel;

class ActController extends HomeController
{
    CONST STAT_NAME = 'anniversary_total';  // 总共点赞人数后台自增
    CONST GIFT_LOGIN_BEFORE = 470; // 注册时间在 18.2.6 前
    CONST GIFT_LOGIN_INTER = 471; // 注册时间在 18.2.6-18.7.12之间
    CONST GIFT_LOGIN_AFTER = 472; // 注册时间在 18.7-13 后
    CONST GIFT_PASS_INVITE = 473;    // 被成功邀请获取的礼包
    CONST GIFT_INVITE_2 = 474;  // 邀请 2 人获取礼包
    CONST GIFT_INVITE_4 = 475;  // 邀请 4 人获取礼包
    CONST GIFT_INVITE_6 = 476;  // 邀请 6 人获取礼包
    CONST GROUP_GIFT = 'act_cover_group_gift';  // cover 活动获取礼包

    CONST INVITE_6 = 6;
    CONST INVITE_4 = 4;
    CONST INVITE_2 = 2;

    CONST REGISTER_BEFORE = '2018-02-06';   // 获取登录礼包码，游戏注册时间
    CONST REGISTER_AFTER = '2018-07-12 23:59:59';

    CONST LOGIN_BEFOR = '2019-01-16 00:00:00';  // 邀请码有效的游戏最后登录时间，之前
    CONST LOGIN_AFTER = '2019-02-22 23:59:59';  // 邀请码有效的游戏最后登录时间，之后

    CONST SESSION_LOGIN_PHONE = 'zn_cover_login_phone'; // 登录 session
    CONST SESSION_LOGIN_SERVER_ID = 'zn_cover_login_server_id';
    CONST SESSION_LOGIN_ROLE_NAME = 'zn_cover_login_role_name';

    public $userData = [
        'role_name' => '',  // 角色名
        'server_id' => '',  // 区服 id
        'register_time' => '',  // 用户注册时间
        'gift_register' => '',  // 注册获取礼包
        'gift_pass_invite' => '',    // 被邀请成功获取的礼包
        'gift_invite' => [],    // 邀请人数获取的礼包
        'gift_lottery_prize' => [], //抽奖获取的礼包
        'invite_user' => [],    // 邀请用户
        'total_lottery_num' => 0,  // 总共抽奖次数
        'use_lottery_num' => 0,    // 剩余抽奖次数
        'login_at' => 0,    // 登录时间
        'share_at' => 0,    // 分享时间
        'is_incr_num_invite_6' => false, //邀请 6 人 是否增加了 3 次抽奖机会
    ];

    public $phone = '';
    public $serverId = '';
    public $roleName = '';

    public $giftIdsPrize = [
        477 => ['id' => 477, 'prize' => '兽皮5、珍珠奶茶10', 'v' => 3000000, 'num' => 0],
        478 => ['id' => 478, 'prize' => '星星法杖', 'v' => 150, 'num' => 300],
        479 => ['id' => 479, 'prize' => '金鱼昊昊', 'v' => 100, 'num' => 200],
        480 => ['id' => 480, 'prize' => '雪狼王', 'v' => 10, 'num' => 8],
        481 => ['id' => 481, 'prize' => '周年限量法杖', 'v' => 100, 'num' => 200],
        482 => ['id' => 482, 'prize' => '50元话费', 'v' => 50, 'num' => 100],
        483 => ['id' => 483, 'prize' => '10元话费', 'v' => 600, 'num' => 1200],
        484 => ['id' => 484, 'prize' => '灯笼龟', 'v' => 250, 'num' => 500],
        485 => ['id' => 485, 'prize' => '鎏金龙', 'v' => 10, 'num' => 5],
        486 => ['id' => 486, 'prize' => '限量蛋糕烟花', 'v' => 5000, 'num' => 10000],
        487 => ['id' => 487, 'prize' => '限量蛋糕炸弹', 'v' => 500, 'num' => 1000],
        488 => ['id' => 488, 'prize' => 'IPad 2018年新款9.7英寸32G WLAN版 金色', 'v' => 1, 'num' => 2],
        489 => ['id' => 489, 'prize' => 'SWITCH游戏机s欧版 红蓝主机', 'v' => 1, 'num' => 2],
        0 => ['id' => 0, 'prize' => '谢谢参与', 'v' => 63229, 'num' => 0],
    ];

    // 邀请 获得的礼包
    public $giftIdsInvite = [
        474 => ['id' => 474, 'prize' => '邀请 2 人获取礼包'],
        475 => ['id' => 475, 'prize' => '邀请 4 人获取礼包'],
        476 => ['id' => 476, 'prize' => '邀请 6 人获取礼包'],
    ];

    public $giftIdsHf = [482, 483]; // 是话费的礼包 id
    public $giftIdsSt = [488, 489]; // 是实体的礼包 id

    public function beforeAction($action)
    {
        $startTime = strtotime('2019-01-23');
        $endTime = strtotime('2019-02-11');

        if (time() < $startTime) {
            // todo 暂时注释
//            $this->echoJson(10);
        }
        if (time() > $endTime) {
            $this->echoJson(11);
        }
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    /**
     * 获取总共点赞数量
     */
//    public function actionGetPraiseNum()
//    {
//        $yuyue = YuyueStatModel::getYuyue($this->website_id, self::STAT_NAME);
//        $cache = new SmPraiseCache($this->website_id);
//        $count = $yuyue['count'] + $cache->getPraise();
//        $this->echoJson(0, '', $count);
//    }

    /**
     * 获取总共点赞数量
     * @return mixed
     */
    private function _getPraiseNum()
    {
        $yuyue = YuyueStatModel::getYuyue($this->website_id, self::STAT_NAME);
        $cache = new SmPraiseCache($this->website_id);
        $count = $yuyue['count'] + $cache->getPraise();
        return $count;
    }

    /**
     * 点赞
     */
    public function actionPraise()
    {
        $ip = Cms::getClientIp();
        $cache = new SmPraiseCache($this->website_id);
        $res = $cache->praise($ip);
        if (!$res) {
            $this->echoJson(12);
        }
        $cache->incrPraise();
        $this->echoJson(0);
    }

    private function _checkPraise()
    {
        $ip = Cms::getClientIp();
        $cache = new SmPraiseCache($this->website_id);
        $res = $cache->checkPraise($ip);
        if ($res) {
            return true;
        }
        return false;
    }

    /**
     * 获取用户信息&登录状态
     */
    public function actionGetUserInfo()
    {
        $res = [];
        $isPraise = $this->_checkPraise();
        $res['is_praise'] = $isPraise;
        $res['total_praise_num'] = $this->_getPraiseNum();  // 总共点赞数
        $res['all_gift_log'] = $this->_getAllGiftLog(); // 所有中奖记录
        $res['all_residue_num'] = $this->_getResidueNum();  // 获取所有礼包剩余数

        list($user, $userData) = $this->_checkLogin($res);

        // 今天第一次登录，则抽奖机会 +1
        if (!isset($userData['login_at']) || $userData['login_at'] < strtotime(date('Y-m-d'))) {
            $userData['login_at'] = time();
            $userData['total_lottery_num'] = isset($userData['total_lottery_num']) ? $userData['total_lottery_num'] : 0;
            $userData['total_lottery_num'] = $userData['total_lottery_num'] + 1;
            UserCenterData::setData($this->website_id, $user['id'], $userData);
        }

        $userData = array_merge($this->userData, $userData);
        $userData['residue_lottery_num'] = $this->_getResidueLotteryNum($userData);
        $isPraise = $this->_checkPraise();
        $data = [
            'user' => $user,
            'user_data' => $userData,
            'is_praise' => $isPraise,
        ];
        $data = array_merge($res, $data);
        $this->echoJson(0, '', $data);
    }

    /**
     *
     */
    public function actionIndex(){
//        $isPraise = $this->_checkPraise();
//        $data['is_praise'] = 1; //
        $data['total_praise_num'] = $this->_getPraiseNum();  // 总共点赞数
        $data['all_gift_log'] = $this->_getAllGiftLog(); // 所有中奖记录

//        $data['all_gift_log'] = [
//            [
//                'phone' => '158****9010',
//                'name' => '限量蛋糕烟花',
//            ],
//        ];

        $data['all_residue_num'] = $this->_getResidueNum();  // 获取所有礼包剩余数
//        pr($data, 1);
        return $this->renderPartial('@app/views/site/cover.html',['data'=>$data]);
    }

    public function actionIndexWap()
    {
        $data['total_praise_num'] = $this->_getPraiseNum();  // 总共点赞数
        $data['all_gift_log'] = $this->_getAllGiftLog(); // 所有中奖记录
//        $data['all_gift_log'] = [
//            [
//                'phone' => '158****9010',
//                'name' => '限量蛋糕烟花',
//            ],
//        ];
        $data['all_residue_num'] = $this->_getResidueNum();  // 获取所有礼包剩余数
//        pr($data, 1);
        return $this->renderPartial('@app/views/wap/site/cover.html',['data'=>$data]);
    }

    /**
     * 登录
     */
    public function actionLogin()
    {
        $roleName = Cms::getPostValue('role_name');
        $serverId = Cms::getPostValue('server_id');
        $phone = Cms::getPostValue('phone');
        $inviteCode = Cms::getPostValue('invite_code');
        if (!$roleName) {
            $this->echoJson(13);
        }
        if (!$serverId) {
            $this->echoJson(14);
        }
        if (!$phone || !Cms::checkPhone($phone)) {
            $this->echoJson(15);
        }

        $check = Cms::checkVerify(0);
        if ($check['status'] != 0) {
            $this->echoJson(17);
        }

        $user = UserCenter::getUserInfo($this->website_id, $phone);
        $isNewUser = false;
        if ($user) {
            // 已经绑定了该手机号
            $userData = UserCenterData::getUserData($this->website_id, $user['id']);

//            $userData['invite_user'][] = '15802859999';
//            $userData['invite_user'][] = '15802859929';
//            $userData['invite_user'][] = '15802859939';
//            $userData['invite_user'][] = '15802859949';
//            $userData['invite_user'][] = '15802859959';
//            $userData['invite_user'][] = '15802859969';
//            $userData['invite_user'][] = '15802859979';
//            $userData['invite_user'][] = '15802859989';
//            UserCenterData::setData($this->website_id, $user['id'], $userData);

            if (!isset($userData['role_name'])) {
                $this->echoJson(16);
            }
            if ($userData['role_name'] != $roleName && $userData['server_id'] != $serverId) {
                $this->echoJson(16);
            }

            // 今天第一次登录，则抽奖机会 +1
            if (!isset($userData['login_at']) || $userData['login_at'] < strtotime(date('Y-m-d'))) {
                $userData['login_at'] = time();
                $userData['total_lottery_num'] = isset($userData['total_lottery_num']) ? $userData['total_lottery_num'] : 0;
                $userData['total_lottery_num'] = $userData['total_lottery_num'] + 1;
                UserCenterData::setData($this->website_id, $user['id'], $userData);
            }


        } else {
            $exist = $this->_existBindRoleName($serverId, $roleName);
            if ($exist) {
                $this->echoJson(27);
            }

            // 未绑定该手机号，则验证角色，然后发放礼包
            list($code, $roleInfo) = $this->_getRoleInfo($serverId, $roleName);
            if ($code != 0) {
                $this->echoJson($code);
            }

            $otherInviteCode = 0;
            $giftInviteCode = 0;
            // 如果有邀请码
            if ($inviteCode
                && ($roleInfo['lastlogin_time'] < strtotime(self::LOGIN_BEFOR) || $roleInfo['lastlogin_time'] > strtotime(self::LOGIN_AFTER))
            ) {
                $invitePhone = UserCenter::getInviteCodeUser($this->website_id, $inviteCode);
                if ($invitePhone) {
                    $inviteUser = UserCenter::getUserInfo($this->website_id, $invitePhone);
                    $inviteUserData = UserCenterData::getUserData($this->website_id, $inviteUser['id']);
                    $inviteUserData['invite_user'][] = $phone;

                    // 如果邀请人数大于 6 人， 则，抽奖次数 +3
                    if (!isset($inviteUserData['is_incr_num_invite_6']) && count($inviteUserData['invite_user']) >= 6) {
                        if (count($inviteUserData['invite_user']) >= 9) {   // 修复之前 bug
                        } else {
                            $inviteUserData['total_lottery_num'] = isset($inviteUserData['total_lottery_num']) ? $inviteUserData['total_lottery_num'] : 0;
                            $inviteUserData['total_lottery_num'] = $inviteUserData['total_lottery_num'] + 3;
                        }
                        $inviteUserData['is_incr_num_invite_6'] = 1;
                    }

                    UserCenterData::setData($this->website_id, $inviteUser['id'], $inviteUserData);
                    $otherInviteCode = $inviteCode;

                    // 邀请码有效，则会获取一个被邀请礼包
                    list($code, $giftCodeLogId) = GiftCode::getGiftCodeByPhone($this->website_id, self::GIFT_PASS_INVITE, $phone, true);
                    $giftInviteCode = $code;
                }
            }

            $params = [
                'me_invite_code' => $this->_getMeInviteCode(),
                'other_invite_code' => $otherInviteCode
            ];
            $user = UserCenter::addUser($this->website_id, $phone, '', '', $params);
            UserCenter::addInviteCodeUser($this->website_id, $params['me_invite_code'], $phone);

            $userData = [
                'role_name' => $roleName,
                'server_id' => $serverId,
                'register_time' => isset($roleInfo['register_time']) ? $roleInfo['register_time'] : 0,  // 用户注册时间
                'gift_pass_invite' => $giftInviteCode,
            ];

            $userData['login_at'] = time();
            $userData['total_lottery_num'] = isset($userData['total_lottery_num']) ? $userData['total_lottery_num'] : 0;
            $userData['total_lottery_num'] = $userData['total_lottery_num'] + 1;

            UserCenterData::addData($this->website_id, $user['id'], $userData);
            $isNewUser = true;

            $this->_addBindRoleName($serverId, $roleName);
        }

        Cms::setSession(self::SESSION_LOGIN_PHONE, $phone);
        Cms::setSession(self::SESSION_LOGIN_SERVER_ID, $serverId);
        Cms::setSession(self::SESSION_LOGIN_ROLE_NAME, $roleName);
        $userData['is_new_user'] = $isNewUser;
        $userData = array_merge($this->userData, $userData);
        $userData['residue_lottery_num'] = $this->_getResidueLotteryNum($userData);
        $this->echoJson(0, '', ['user' => $user, 'user_data' => $userData]);
    }

    /**
     * 注销
     */
    public function actionLogout()
    {
        Cms::setSession(self::SESSION_LOGIN_PHONE, '');
        Cms::setSession(self::SESSION_LOGIN_ROLE_NAME, '');
        Cms::setSession(self::SESSION_LOGIN_SERVER_ID, '');
        $this->echoJson(0);
    }

    /**
     * 请求接口获取获取信息
     * @param $serverId
     * @param $roleName
     * @return array
     */
    private function _getRoleInfo($serverId, $roleName)
    {
        $res = Api::userdata($this->website_id, Api::$gameids['sm'], '', $serverId, $roleName);
        if ($res['code'] != 0) {
            return [19, $res['msg']];
        }

        return [0, $res['data']];
    }

    /**
     * 配置邀请码，需求是只能纯数字
     * @return string
     */
    private function _getMeInviteCode()
    {
        $sub = substr((string)time(), -3);
        $meInviteCode = rand(1, 9).$sub.rand(10000, 99999);
        return $meInviteCode;
    }

    /**
     * 获取注册礼包
     */
    public function actionGetRegisterGift()
    {
        list($user, $userData) = $this->_checkLogin();
        if (isset($userData['gift_register']) && $userData['gift_register']) {
            $data = [
                'code' => $userData['gift_register'],
                'register_type' => isset($userData['register_type']) ? $userData['register_type'] : 1,
            ];
            $this->echoJson(0, '', $data);
        }

        if (!$userData['register_time']) {
            $this->echoJson(22);
        } elseif ($userData['register_time'] < strtotime(self::REGISTER_BEFORE)) {
            $giftId = self::GIFT_LOGIN_BEFORE;
            $registerType = 1;
        } elseif ($userData['register_time'] >= strtotime(self::REGISTER_BEFORE) && $userData['register_time'] <= strtotime(self::REGISTER_AFTER)) {
            $giftId = self::GIFT_LOGIN_INTER;
            $registerType = 2;
        } elseif ($userData['register_time'] > strtotime(self::REGISTER_AFTER)) {
            $giftId = self::GIFT_LOGIN_AFTER;
            $registerType = 3;
        } else {
            $this->echoJson(22);
        }

        list($code, $giftCodeLogId) = GiftCode::getGiftCodeByPhone($this->website_id, $giftId, $user['phone'], true);
        if ($code) {
            $userData['gift_register'] = $code;
            $userData['register_type'] = $registerType;
            UserCenterData::setData($this->website_id, $user['id'], $userData);
            $data = [
                'code' => $code,
                'register_type' => $registerType,
            ];
            $this->echoJson(0, '', $data);
        } else {
            $this->echoJson(18);
        }
    }

    /**
     * 检查登录
     */
    public function _checkLogin($res = [])
    {

        $this->phone = Cms::getSession(self::SESSION_LOGIN_PHONE);
        $this->roleName = Cms::getSession(self::SESSION_LOGIN_ROLE_NAME);
        $this->serverId = Cms::getSession(self::SESSION_LOGIN_SERVER_ID);
        if (!$this->phone || !$this->roleName || !$this->serverId) {
            $this->echoJson(2, '', $res);
        }
        $user = UserCenter::getUserInfo($this->website_id, $this->phone);
        $userData = UserCenterData::getUserData($this->website_id, $user['id']);
        if (!$user) {
            $this->echoJson(2, '', $res);
        }
        return [$user, $userData];
    }

    /**
     * 获取邀请礼包码
     */
    public function actionGetInviteCode()
    {
        list($user, $userData) = $this->_checkLogin();
        $inviteCount = isset($userData['invite_user']) ? count($userData['invite_user']) : 0;
        $isUpdate = false;

        if ($inviteCount >= self::INVITE_6 && !isset($userData['gift_invite'][self::INVITE_6])) {
            list($code, $giftCodeLogId) = GiftCode::getGiftCodeByPhone($this->website_id, self::GIFT_INVITE_6, $user['phone'], true);
            $userData['gift_invite'][self::INVITE_6] = [
                'gift_id' => self::GIFT_INVITE_6,
                'prize' => $this->giftIdsInvite[self::GIFT_INVITE_6]['prize'],
                'code' => $code,
                'time' => date('Y-m-d H:i:s'),
            ];
            $isUpdate = true;
        }

        if ($inviteCount >= self::INVITE_4 && !isset($userData['gift_invite'][self::INVITE_4])) {
            list($code, $giftCodeLogId) = GiftCode::getGiftCodeByPhone($this->website_id, self::GIFT_INVITE_4, $user['phone'], true);
            $userData['gift_invite'][self::INVITE_4] = [
                'gift_id' => self::GIFT_INVITE_4,
                'prize' => $this->giftIdsInvite[self::GIFT_INVITE_4]['prize'],
                'code' => $code,
                'time' => date('Y-m-d H:i:s'),
            ];
            $isUpdate = true;
        }

        if ($inviteCount >= self::INVITE_2 && !isset($userData['gift_invite'][self::INVITE_2])) {
            list($code, $giftCodeLogId) = GiftCode::getGiftCodeByPhone($this->website_id, self::GIFT_INVITE_2, $user['phone'], true);
            $userData['gift_invite'][self::INVITE_2] = [
                'gift_id' => self::GIFT_INVITE_2,
                'prize' => $this->giftIdsInvite[self::GIFT_INVITE_2]['prize'],
                'code' => $code,
                'time' => date('Y-m-d H:i:s'),
            ];
            $isUpdate = true;
        }

        if ($isUpdate) {
            UserCenterData::setData($this->website_id, $user['id'], $userData);
        }
        $giftInvite =  isset($userData['gift_invite']) ? $userData['gift_invite'] : [];
        $this->echoJson(0, '', $giftInvite);
    }

    /**
     * 分享增加抽奖次数
     */
    public function actionShare()
    {
        list($user, $userData) = $this->_checkLogin();
        if (isset($userData['share_at']) && $userData['share_at'] > strtotime(date('Y-m-d'))) {
            $this->echoJson(20);
        }

        $userData['share_at'] = time();
        $userData['total_lottery_num'] = isset($userData['total_lottery_num']) ? $userData['total_lottery_num'] : 0;
        $userData['total_lottery_num'] = $userData['total_lottery_num'] + 1;
        UserCenterData::setData($this->website_id, $user['id'], $userData);
        $residueLotteryNum = $this->_getResidueLotteryNum($userData);
        $this->echoJson(0, '', ['residule_lottery_num' => $residueLotteryNum]);
    }

    /**
     * 获取剩余抽奖次数
     * @param $userData
     * @return int
     */
    private function _getResidueLotteryNum($userData)
    {
        $residueLotteryNum = $userData['total_lottery_num'] - (!isset($userData['use_lottery_num']) ? 0 : $userData['use_lottery_num']);
        $residueLotteryNum = $residueLotteryNum < 0 ? 0 : $residueLotteryNum;
        return $residueLotteryNum;
    }

    /**
     * 老虎机抽奖
     */
    public function actionLottery()
    {
        list($user, $userData) = $this->_checkLogin();
        $residueLotteryNum = $this->_getResidueLotteryNum($userData);
        if ($residueLotteryNum <= 0) {
            $this->echoJson(21);
        }
        $giftId = Cms::getPrizeId($this->giftIdsPrize);

        // 每天发总数不超过礼包总数的1/20
        $num = ceil($this->giftIdsPrize[$giftId]['num'] / 20);
        $countDay = GiftCode::getCountDay($this->website_id, $giftId);

        // 如果随机获取的礼包超过了每天设置的上限，则自动转为 477
        if ($countDay >= $num) {
            $giftId = 477;
        }

        // 实体礼包 在 2019-02-11 之前总共只能中 1 个，在当天可以中 1 个
        if (in_array($giftId, $this->giftIdsSt)) {
            $count = GiftCodeLog::getGiftCountLogCount($this->website_id, $giftId);
            if ($count >= 2) {
                $giftId = 477;
            } elseif ($count > 0 && date('Y-m-d') != '2019-02-11') {
                $giftId = 477;
            }
        }

        if ((date('Y-m-d') != '2019-02-11') && $giftId == 480) {
            $giftId = 477;
        }

        if ((date('Y-m-d') != '2019-02-11') && $giftId == 485) {
            $giftId = 477;
        }

        if (isset($userData['gift_lottery_prize']) && key_exists($giftId, $userData['gift_lottery_prize'])) {
            $giftId = 0;
            $userData['use_lottery_num'] = isset($userData['use_lottery_num']) ? $userData['use_lottery_num'] : 0;
            $userData['use_lottery_num'] = $userData['use_lottery_num'] + 1;
            UserCenterData::setData($this->website_id, $user['id'], $userData);

            $prize = $giftId ? $this->giftIdsPrize[$giftId]['prize'] : '';
            $data = [
                'gift_id' => $giftId,
                'code' => '',
                'prize' => $prize,
                'time' => date('Y-m-d H:i:s'),
                'residue_lottery_num' => $this->_getResidueLotteryNum($userData),
                'gift_lottery_prize' => $userData['gift_lottery_prize'],
            ];
            $this->echoJson(0, '', $data);
        }

        if (in_array($giftId, $this->giftIdsHf) || in_array($giftId, $this->giftIdsSt)) {
            list($code, $giftCodeLogId) = GiftCode::getGiftEntityByPhone($this->website_id, $giftId, $user['phone'], true, $this->giftIdsPrize[$giftId]['num'], self::GROUP_GIFT);
        } else{
            list($code, $giftCodeLogId) = GiftCode::getGiftCodeByPhone($this->website_id, $giftId, $user['phone'], true, 0, self::GROUP_GIFT);
        }

        if ($code) {
            $userData['gift_lottery_prize'][$giftId] = [
                'gift_id' => $giftId,
                'prize' => $this->giftIdsPrize[$giftId]['prize'],
                'code' => $code,
                'time' => date('Y-m-d H:i:s'),
            ];
        } else {
            $giftId = 0;
        }

        $userData['use_lottery_num'] = isset($userData['use_lottery_num']) ? $userData['use_lottery_num'] : 0;
        $userData['use_lottery_num'] = $userData['use_lottery_num'] + 1;
        UserCenterData::setData($this->website_id, $user['id'], $userData);
        GiftCode::incrCountDay($this->website_id, $giftId);

        $prize = $giftId ? $this->giftIdsPrize[$giftId]['prize'] : '';
        $data = [
            'gift_id' => $giftId,
            'code' => $code,
            'prize' => $prize,
            'time' => date('Y-m-d H:i:s'),
            'residue_lottery_num' => $this->_getResidueLotteryNum($userData),
            'gift_lottery_prize' => $userData['gift_lottery_prize'],
        ];
        $this->echoJson(0, '', $data);
    }

    /**
     * 获取礼包剩余数量
     */
//    public function actionGetResidueNum()
//    {
//        $data = [];
//        foreach ($this->giftIdsPrize as $k => $v) {
//            if ($k == 0 || $k == 477) {
//                continue;
//            }
//
//            $count = GiftCodeLog::getGiftCountLogCount($this->website_id, $k);
//            $num = $v['num'] - $count;
//            $num = $num <= 0 ? 0 : $num;
//            $data[$k] = $num;
//        }
//
//        $this->echoJson(0, '', $data);
//    }

    /**
     * 获取礼包剩余数量
     */
    private function _getResidueNum()
    {
        $data = [];
        foreach ($this->giftIdsPrize as $k => $v) {
            if ($k == 0 || $k == 477) {
                continue;
            }

            $count = GiftCodeLog::getGiftCountLogCount($this->website_id, $k);
            $num = $v['num'] - $count;
            $num = $num <= 0 ? 0 : $num;
            $data[$k] = $num;
        }
        return $data;
    }

    /**
     * 保存收货地址
     */
    public function actionSaveAddress()
    {
        list($user, $userData) = $this->_checkLogin();
        $deliveryName = Cms::getPostValue('delivery_name');
        $deliveryPostcode = Cms::getPostValue('delivery_postcode');
        $deliveryPhone = Cms::getPostValue('delivery_phone');
        $deliveryAddress = Cms::getPostValue('delivery_address');
        if (!$deliveryName || mb_strlen($deliveryName) > 10) {
            $this->echoJson(23);
        }
        if (!$deliveryPhone || !Cms::checkPhone($deliveryPhone)) {
            $this->echoJson(24);
        }
        if (!$deliveryPostcode || !is_numeric($deliveryPostcode) || strlen($deliveryPostcode) > 6) {
            $this->echoJson(25);
        }
        if (!$deliveryAddress || mb_strlen($deliveryAddress) > 50) {
            $this->echoJson(26);
        }

        $userData['delivery_name'] = $deliveryName;
        $userData['delivery_postcode'] = $deliveryPostcode;
        $userData['delivery_phone'] = $deliveryPhone;
        $userData['delivery_address'] = $deliveryAddress;
        UserCenterData::setData($this->website_id, $user['id'], $userData);
        $this->echoJson(0);
    }

//    public function actionGetAllGiftLog()
//    {
//        $data = GiftCode::getGiftCodeLogGroup($this->website_id, self::GROUP_GIFT);
//        $res = [];
//        if ($data && !empty($data)) {
//            foreach ($data as $v) {
//                if (!isset($this->giftIdsPrize[$v['gift_id']]['prize'])) {
//                    continue;
//                }
//                $v['phone'] = substr_replace($v['phone'], '****', 3, 4);
//                $v['name'] = $this->giftIdsPrize[$v['gift_id']]['prize'];
//                $res[] = $v;
//            }
//        }
//        $this->echoJson(0, '', $res);
//    }

    /**
     * 获取所有中奖礼包
     * @return array
     */
    private function _getAllGiftLog()
    {
        $data = GiftCode::getGiftCodeLogGroup($this->website_id, self::GROUP_GIFT);
        $res = [];
        if ($data && !empty($data)) {
            $k = 0;
            foreach ($data as $v) {
                if (!isset($this->giftIdsPrize[$v['gift_id']]['prize'])) {
                    continue;
                }
                if ($k >= 50) {
                    continue;
                }
                unset($v['code']);
                $v['phone'] = substr_replace($v['phone'], '****', 3, 4);
                $v['name'] = $this->giftIdsPrize[$v['gift_id']]['prize'];
                $res[] = $v;
                $k++;
            }
        }
        return $res;
    }

    /**
     * 判断角色绑定池里是否有该角色
     * @param $serverId
     * @param $roleName
     * @return mixed
     */
    private function _existBindRoleName($serverId, $roleName)
    {
        $cache = new PublicCache($this->website_id);
        $res = $cache->existSmNewYearRole($serverId, $roleName);
        return $res;
    }

    /**
     * 角色加入到已经绑定池里
     * @param $serverId
     * @param $roleName
     */
    private function _addBindRoleName($serverId, $roleName)
    {
        $cache = new PublicCache($this->website_id);
        $cache->addSmNewyearRole($serverId, $roleName);
    }

    public function actionAddTestUser()
    {
        exit;
        for ($i = 10; $i <= 59; $i++) {
            $phone = "15802859059";
            $params = [
                'me_invite_code' => $this->_getMeInviteCode(),
                'other_invite_code' => 0
            ];
            $user = UserCenter::addUser($this->website_id, $phone, '', '', $params);
            UserCenter::addInviteCodeUser($this->website_id, $params['me_invite_code'], $phone);

            $userData = [
                'role_name' => 'test',
                'server_id' => '22',
                'register_time' => 0,  // 用户注册时间
                'gift_pass_invite' => '',
            ];

            $userData['login_at'] = time();
            $userData['total_lottery_num'] = isset($userData['total_lottery_num']) ? $userData['total_lottery_num'] : 0;
            $userData['total_lottery_num'] = $userData['total_lottery_num'] + 1;
            UserCenterData::addData($this->website_id, $user['id'], $userData);
            echo $params['me_invite_code']."<br/>";
        }

    }
}