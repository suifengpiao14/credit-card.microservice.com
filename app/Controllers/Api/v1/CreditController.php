<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\8\4 0004
 * Time: 15:51.
 */

namespace App\Controllers\Api\v1;

use App\Controllers\Controller;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Container;
use App\Models\Card;

/**
 * Class ExampleController.
 *
 * @SuppressWarnings(PHPMD)
 */
class CreditController extends Controller
{

    /**
     *
     * 指定日期选择卡片
     * @SWG\Get(
     *   path="/api/v1/credit/which-card",
     *   tags={"api"},
     *   summary="指定日期选择卡片",
     *   description="指定日期选择卡片",
     *   consumes={"application/x-www-form-urlencoded"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="user_id",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="用户ID",
     *     default=1
     *   ),
     *   @SWG\Parameter(
     *     name="date",
     *     in="query",
     *     type="string",
     *     description="日期格式Y-m-d，默认当天",
     *   ),
     *   @SWG\Response(response="400", description="bad request ",ref="#/responses/BadRequest"),
     *   @SWG\Response(response="404", description="not found",ref="#/responses/NotFound"),
     *   @SWG\Response(response="200", description="ok",ref="#"),
     * )
     *
     * @param Request $request
     * @param Response $response
     * @return $this|static
     */
    public function whichCard(Request $request, Response $response){
        $userId=$request->getParam('user_id');
        $consumptionDate=$request->getParam('date',date('Y-m-d'));
        $model = new Card();
        /** @var Collection $collection */
        $rows=$model->getByUid($userId)->toArray();
        $arr=Card::addIntervalDay($rows,$consumptionDate);
        $data=Card::sortBy($arr);
        return $response->withJson($data);
    }

    /**
     *
     * 指定日期范围内选择卡片
     * @SWG\Get(
     *   path="/api/v1/credit/which-card-and-date",
     *   tags={"api"},
     *   summary="指定日期范围内选择卡片",
     *   description="指定日期范围内选择卡片",
     *   consumes={"application/x-www-form-urlencoded"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="user_id",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="用户ID",
     *     default=1
     *   ),
     *   @SWG\Parameter(
     *     name="start_date",
     *     in="query",
     *     type="string",
     *     description="开始日期，格式Y-m-d，默认当天",
     *   ),
     *   @SWG\Parameter(
     *     name="end_date",
     *     in="query",
     *     type="string",
     *     description="结束日期，格式Y-m-d，默认当天",
     *   ),
     *   @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="string",
     *     description="获取记录数，默认6条",
     *   ),
     *   @SWG\Response(response="400", description="bad request ",ref="#/responses/BadRequest"),
     *   @SWG\Response(response="404", description="not found",ref="#/responses/NotFound"),
     *   @SWG\Response(response="200", description="ok",ref="#"),
     * )
     *
     * @param Request $request
     * @param Response $response
     * @return $this|static
     */
    public function whichCardAndDate(Request $request, Response $response){
        $userId=$request->getParam('user_id');
        $startDate=$request->getParam('start_date',date('Y-m-d'));
        $endDate=$request->getParam('end_date',date('Y-m-d'));
        $limit=$request->getParam('limit',6);//默认获取还款时间最长的前6条数据
        $model = new Card();
        $rows=$model->getByUid($userId)->toArray();
        $startTimestamp=strtotime($startDate);
        $endTimestamp=strtotime($endDate);
        $data=[];
        do{
            $consumptionDate = date('Y-m-d',$startTimestamp);
            $arr=Card::addIntervalDay($rows,$consumptionDate);
            $data = array_merge($data,$arr);

            $startTimestamp=strtotime('+1 day',$startTimestamp);
        }while($startTimestamp<=$endTimestamp);
        $data = Card::sortBy($data);
        count($data)>$limit and $data=array_slice($data,0,$limit);//截取指定长度数组
        return $response->withJson($data);
    }


}
