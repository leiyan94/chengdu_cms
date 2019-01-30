<!DOCTYPE html>
<html>

<head>
    <title><?= $this->getSeo('title');?></title>
    <meta charset="utf-8">
    <meta name="keyword" content="<?= $this->getSeo('key');?>">
    <meta name="description" content="<?= $this->getSeo('desc');?>">
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1,maximum-scale=1, minimum-scale=1">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <link rel="SHORTCUT ICON" href="<?= STATIC_DOMAIN ?>2.0/favico.ico">
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "//hm.baidu.com/hm.js?744fc26928ce00373c1f19768d018dce";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
    <link rel="stylesheet" type="text/css" href="<?php echo STATIC_DOMAIN; ?>jlj/m/css/wapReset.css?<?= VERSION?>">
    <link rel="stylesheet" type="text/css" href="<?php echo STATIC_DOMAIN; ?>jlj/m/css/animate.min.css?<?= VERSION?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo STATIC_DOMAIN; ?>jlj/m/css/index.css?<?= VERSION?>">
    <script type="text/javascript" src="<?php echo STATIC_DOMAIN; ?>wap1.0/public/flexible.js?<?= VERSION?>"></script>
    <script type="text/javascript" src="<?php echo STATIC_DOMAIN; ?>jlj/m/js/jquery-1.7.1.min.js?<?= VERSION?>"></script>
</head>

<body>
<header class="cp_header bounceInDown animated">
    <section id="nav_back">
        <a href="javascript:"><img src="<?php echo STATIC_DOMAIN; ?>wap1.0/images/cp_img1_03.png?<?= VERSION?>"></a>
    </section>
    <section id="nav_icon" class="nav_icon" name="0">

        <img class="nav_icon" src="<?php echo STATIC_DOMAIN; ?>wap1.0/images/ab_img2_06.png?<?= VERSION?>">
        <img class="close nav_hide" src="<?php echo STATIC_DOMAIN; ?>wap1.0/images/yx_close1_03.png?<?= VERSION?>">
    </section>
    <ul id="page_nav" class="page_nav">
        <li>
            <a href="<?= \yii\helpers\Url::to(['/m/product'])?>">
                <img src="<?php echo STATIC_DOMAIN; ?>wap1.0/images/n_img1_03.png?<?= VERSION?>">
            </a>
        </li>
        <li>
            <a href="<?= \yii\helpers\Url::to(['/m/news'])?>">
                <img src="<?php echo STATIC_DOMAIN; ?>wap1.0/images/n_img2_03.png?<?= VERSION?>">
            </a>
        </li>
        <li>
            <a href="<?= \yii\helpers\Url::to(['/m/into'])?>">
                <img src="<?php echo STATIC_DOMAIN; ?>wap1.0/images/n_img3_03.png?<?= VERSION?>">
            </a>
        </li>
        <li class="no_margin">
            <a href="<?= \yii\helpers\Url::to(['/m/about'])?>">
                <img src="<?php echo STATIC_DOMAIN; ?>wap1.0/images/n_img4_03.png?<?= VERSION?>">
            </a>
        </li>

    </ul>
    <img class="logo" src="<?php echo STATIC_DOMAIN; ?>wap1.0/images/ab_img1_03.png?<?= VERSION?>">
</header>
<section class="bounceInUp animated j_content">
    <h1 class="j_title j_title1">支持你最爱的游戏 · 别错过投票机会</h1>
    <div class="j_item j_item1">
        <h1>
            本届“金翎奖”年度游戏评选大赛投票周期: <br />
            2017年10月12日12:00-2017年11月16日12:00
        </h1>
        <h4>
            第一周：10月12日-10月19日 淘汰赛第一轮
        </h4>
        <p>
            10月19日中午12点整 截止所有票数低于200票（不含200票）的游戏失去参赛资格
        </p>
        <h4>
            第二周：10月19日-10月26日 淘汰赛第二轮
        </h4>
        <p>
            10月26日中午12点整截止所有票数低于500票（不含500票）的游戏失去参赛资格
        </p>
        <h4>
            第三周：10月26日-11月2日 自由投票期
        </h4>
        <h4>
            第四周：11月2日-11月9日 自由投票期
        </h4>
        <h4>
            第五周：11月9日-11月16日 中午12点自由投票期结束
        </h4>
        <h2>
            投票规则
        </h2>
        <p>
            每个支付宝账号投票期最多投票数为500票<br/> 并且给同一款游戏/媒体参评的同一奖项投票只可获得一次即得奖
        </p>
        <h2>
            获奖说明
        </h2>
        <p>
            10月12日中午12点开始投票截止到11月16日中午12点  票数领先者获奖<br/> 汉威信恒展览有限公司有最终解释权
        </p>
    </div>
    <h1 class="j_title j_title2">奖 品 展 示</h1>
    <div class="j_item j_item2">
        <ul>
            <li>
                <img src="<?php echo STATIC_DOMAIN;?>jlj/images/j_jp1.png?<?= VERSION?>" />
                <p>手机充值卡 50元</p>
            </li>
            <li>
                <img src="<?php echo STATIC_DOMAIN;?>jlj/images/j_jp2.png?<?= VERSION?>" />
                <p>京东卡 200元</p>
            </li>
            <li>
                <img src="<?php echo STATIC_DOMAIN;?>jlj/images/j_jp3.png?<?= VERSION?>" />
                <p>OPPO R11</p>
            </li>
            <li>
                <img src="<?php echo STATIC_DOMAIN;?>jlj/images/j_jp4.png?<?= VERSION?>" />
                <p>IPAD MINI 4</p>
            </li>
            <li>
                <img src="<?php echo STATIC_DOMAIN;?>jlj/images/j_jp5.png?<?= VERSION?>" />
                <p>PS 4</p>
            </li>
        </ul>
    </div>
    <h1 class="j_title j_title3">参 赛 游 戏</h1>
    <div class="j_item j_item3">
        <ul>
            <li>
                <img class="i3_game_img" src="<?php echo STATIC_DOMAIN;?>jlj/images/j_game1.png?<?= VERSION?>" />
                <p>
                    一起来飞车——玩家最喜爱的移动网络游戏
                </p>
                <img class="i3_game_mark" src="<?php echo STATIC_DOMAIN;?>jlj/images/j_mark1.png?<?= VERSION?>" />
            </li>
            <li>
                <img class="i3_game_img" src="<?php echo STATIC_DOMAIN;?>jlj/images/j_game2.png?<?= VERSION?>" />
                <p>
                    一起来飞车——最佳原创移动游戏
                </p>
                <img class="i3_game_mark" src="<?php echo STATIC_DOMAIN;?>jlj/images/j_mark2.png?<?= VERSION?>" />
            </li>
            <li>
                <img class="i3_game_img" src="<?php echo STATIC_DOMAIN;?>jlj/images/j_game3.png?<?= VERSION?>" />
                <p>
                    一起来飞车——最佳移动电竞游戏
                </p>
                <img class="i3_game_mark" src="<?php echo STATIC_DOMAIN;?>jlj/images/j_mark3.png?<?= VERSION?>" />
            </li>
            <li>
                <img class="i3_game_img" src="<?php echo STATIC_DOMAIN;?>jlj/images/j_game4.png?<?= VERSION?>" />
                <p>
                    战争艺术:赤潮——玩家最期待的移动网络游戏
                </p>
                <img class="i3_game_mark" src="<?php echo STATIC_DOMAIN;?>jlj/images/j_mark4.png?<?= VERSION?>" />
            </li>
            <li>
                <img class="i3_game_img" src="<?php echo STATIC_DOMAIN;?>jlj/images/j_game5.png?<?= VERSION?>" />
                <p>
                    战争艺术:赤潮——最佳原创移动游戏
                </p>
                <img class="i3_game_mark" src="<?php echo STATIC_DOMAIN;?>jlj/images/j_mark5.png?<?= VERSION?>" />
            </li>
            <li>
                <img class="i3_game_img" src="<?php echo STATIC_DOMAIN;?>jlj/images/j_game6.png?<?= VERSION?>" />
                <p>
                    战争艺术:赤潮——最佳移动电竞游戏
                </p>
                <img class="i3_game_mark" src="<?php echo STATIC_DOMAIN;?>jlj/images/j_mark6.png?<?= VERSION?>" />
            </li>
            <li>
                <img class="i3_game_img" src="<?php echo STATIC_DOMAIN;?>jlj/images/j_game7.png?<?= VERSION?>" />
                <p>
                    创造与魔法——玩家最期待的移动单机游戏
                </p>
                <img class="i3_game_mark" src="<?php echo STATIC_DOMAIN;?>jlj/images/j_mark7.png?<?= VERSION?>" />
            </li>
            <li>
                <img class="i3_game_img" src="<?php echo STATIC_DOMAIN;?>jlj/images/j_game8.png?<?= VERSION?>" />
                <p>
                    创造与魔法——最佳原创移动游戏
                </p>
                <img class="i3_game_mark" src="<?php echo STATIC_DOMAIN;?>jlj/images/j_mark8.png?<?= VERSION?>" />
            </li>
        </ul>
    </div>
    <div class="j_item j_item4">
        <p>
            英雄互娱——最具影响力移动游戏发行商
        </p>
        <img src="<?php echo STATIC_DOMAIN;?>jlj/images/j_mark9.png" />
    </div>
</section>
<footer class="i_foot animated bounceInUp" id="i_foot">
    <ul>
        <li>
            <a href="http://kf.yingxiong.com">客服中心</a>
        </li>
        <li>
            <a href="http://bbs.yingxiong.com:">游戏社区</a>
        </li>
        <li>
            <a href="/m/contact">联系我们</a>
        </li>
        <li class="no_border">
            <a href="http://m.i.yingxiong.com/login/index">账号中心</a>
        </li>
    </ul>
    <p>COPYRIGHT©2015 – 2018 . ALL RIGHTS RESERVED</p>
    <p>英雄互娱版权所有</p>
</footer>
</body>
<script type="text/javascript" src="<?php echo STATIC_DOMAIN; ?>wap1.0/public/yx_main1.js?<?= VERSION?>"></script>
<script type="text/javascript">
    $(function() {
        //调用点击顶部菜单按钮
        main1.click_top_nav({
            ele: $(".nav_icon")
        });
    });
    //页面重写main1.click_top_nav方法
    main1.click_top_nav = function(obj) {
        var ele = obj.ele;
        if(ele) {
            ele.on("touchend", function() {
                var name = $(this).attr("name");
                if(name == 0) {
                    $(this).attr({
                        "name": "1"
                    });
                    $(this).children().eq(0).attr({
                        "class": "nav_icon nav_hide"
                    });
                    $(this).children().eq(1).attr({
                        "class": "close"
                    });
                    $(this).parent().children("ul").css("display", "block").attr("class", "animated bounceInRight");
                } else {
                    $(this).attr("name", "0");

                    $(this).children().eq(0).attr({
                        "class": "nav_icon"
                    });
                    $(this).children().eq(1).attr({
                        "class": "close nav_hide"
                    });
                    $(this).parent().children("ul").attr("class", "animated bounceOutRight");
                    setTimeout(function() {
                        $(this).parent().children("ul").css("display", "none");
                    }, 500);
                }
            })
        }
    };

    $("#nav_back").click(function() {
        location.href = "/m";
    });
</script>
</html>