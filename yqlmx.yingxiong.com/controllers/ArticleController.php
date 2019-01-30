<?php
/**
 * 文章
 *
 * @author Administrator
 *
 */

namespace app\controllers;


use common\components\PcController;
use common\models\CategoryType;
use common\models\Content;
use Yii;
use common\Cms;
use yii\widgets\LinkPager;


class ArticleController extends PcController
{
    public $fenlei = [75 => 'zuixin', 76 => 'xinwen', 77 => 'gonggao', 78 => 'huodong', 79 => 'gonglue'];

    public function beforeAction($action)
    {
        return parent::beforeAction($action, 1); // TODO: Change the autogenerated stub
    }

    /**新闻中心
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = false;
        $testType = Cms::getGetValue('testType', 0);

        $categoryId = Cms::getGetValue('id', 75);

        $content = new Content();
        $category_ids = self::getChildren($categoryId);
        $list = $content->getContentLists($category_ids, 11);
        if (!empty($list['page'])) {
            $page = LinkPager::widget([
                'pagination' => $list['page'],
                'hideOnSinglePage' => false,
                'firstPageLabel' => '首页',
                'lastPageLabel' => '尾页',
                'options' => ['class' => 'page'],
                'nextPageLabel' => '下一页',
                'prevPageLabel' => '上一页',
                'maxButtonCount' => 7
            ]);
        } else {
            $page = '';
        }

        $data = [
                'data' => $list['data'],
                'page' => $page,
                'type' => $this->fenlei[$categoryId],
            ];
        if ($testType == 1) {
            pr($data, 1);
        }
        return $this->render('index.html', $data);
    }

    //详情页
    public function actionDetail()
    {
        $this->layout = false;
        $id = Cms::getGetValue('id');
        $testType = Cms::getGetValue('testType', 0);
//        $id = 415;

        if (!$id) {
            echo "<script>alert('文章ID不能为空');location.href='/article/index.html'</script>";
            exit;
        }
        $content = $this->getContentDetail($id);
        if (!$content) {
            $content == array();
        } else if (!key_exists('body', $content)) {
            $content = array_merge($content, array('body' => ''));
        }
        $content['created_at_formate'] = date('Y-m-d', $content['created_at']);
//        pr($content, 1);
        if ($testType == 1) {
            pr($content, 1);
        }
        return $this->render('detail.html', $content);
    }

    public function actionTest()
    {
        $type = Cms::getGetValue('testType');
        if (!$type) {
            echo '不存在';exit;
        }
        $_GET['testType'] = 1;
        $action = 'action'.$type;
        $this->$action();
    }
}
