<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/4 0004
 * Time: 20:22
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
class Card extends BaseModel
{
    protected $table='card';
    /**
     * @param $userId
     * @return Collection
     */
    public  function getByUid($userId){
        $this->getTable();
        $collection=static::where(['user_id'=>$userId])->get();
        return $collection;
    }

    public static function getIntervalDay(){

    }




    /**
     * @param array $rows 信用卡二维数组
     * @param string|true $consumptionDate 消费日
     * @param bool|true $sort 是否排序
     * @return mixed
     */
    public static function addIntervalDay($rows,$consumptionDate){
        list($year,$month,$day) = explode('-',$consumptionDate);
        $month=intval($month);
        $time=strtotime($consumptionDate);
        $intervalDayArr=[];
        $output=[];
        foreach($rows as $key=>$row){
            $repaymentMonth=$month;
            // 每次消费在紧邻的下一个账单日出账，出账后紧邻下一个还款日还款，
            $billingDate=$row['billing_date'];
            $repaymentDate=$row['repayment_date'];
            $repaymentDate<$billingDate and $repaymentMonth=($repaymentMonth+1)%12;//如果账单日再月尾，还款日在月初，则还款日在下个月月初
            $day>$billingDate and $repaymentMonth=($repaymentMonth+1)%12;//如果消费日（当前日期）大于当月出账日，则出账日在下个月,还款月在下个月出账单后紧邻的一个还款日
            $repaymentTimestamp=strtotime("{$year}-{$repaymentMonth}-{$repaymentDate}");
            $intervalDay = floor(($repaymentTimestamp - $time)/3600/24);
            $intervalDayArr[]=$intervalDay;
            $row['interval_day']=$intervalDay;//增加还款间隔时间
            $row['consumption_date']=$consumptionDate;//增加消费日
            $output[$key]=$row;
        }
        return $output;
    }

    /**
     * 根据指定字段排序
     * @param $data
     * @param string $column
     * @return mixed
     */
    public static function sortBy($data,$column='interval_day'){
        $values=[];
        foreach($data as $row){
            $values[]=$row[$column];
        }
        array_multisort($values,SORT_DESC,SORT_NUMERIC,$data);
        return $data;
    }


}