<?php
/**
 * kitnote
 * ============================================================================
 * 版权所有 2015-2027 株洲清拓科技有限公司，并保留所有权利。
 * 网站地址: http://www.mall.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用TP5助手函数可实现单字母函数M D U等,也可db::name方式,可双向兼容
 * ============================================================================
 * Author: czk
 * Date: 2019-06-20
 */

namespace app\mobile\controller;

use think\Model;
use think\Page;
use think\db;
use Shenghuo\request\ShSdkUserApi;

/**
 * 分类逻辑定义
 * Class CatsLogic
 * @package Home\Logic
 */
class Examples extends Model{

	public $crmConfig;

	public function __construct(){
        // 在crm系统生成的配置信息，不能为空
        $this->crmConfig['app_id'] = '';
        $this->crmConfig['app_secret'] = '';
        $this->crmConfig['private_key'] = '';
        $this->crmConfig['public_key'] = '';
	}
	/**
     * 同步注册到管理系统
     */
    public function crmRegister($user,$password,$tkMobile){
        // $config = tpCache('alone');
        $data['mobile']   = $user['mobile'];
        $data['password'] = $password;
        $data['payword']  = 123456;
        $data['nickname'] = isset($user['nickname'])?$user['nickname']:"";
        $data['name']   = isset($user['name'])?$user['name']:"";
        $data['wechat'] = isset($user['wechat'])?$user['wechat']:"";
        $data['qq']  = isset($user['qq'])?$user['qq']:"";
        $data['sex'] = isset($user['sex'])?$user['sex']:0;
        $data['referrer_mobile'] = $tkMobile;

        $client = 'h5';    //注册类型 Andorid，IOS，h5
        $data['reg_type'] = $client;
    	$crmModel = new ShSdkUserApi($this->crmConfig);
        $res = $crmModel->sdkUserRegister($data);
        return $res;
    }

    /**
     * crm用户登录
     */
    public function crmLogin($mobile,$password){
    	$data['mobile']   = $mobile;
    	$data['password'] = $password;
    	$crmModel = new ShSdkUserApi($this->crmConfig);
        $res = $crmModel->sdkUserLogin($data);
        return $res;
    }

    /**
     * 同步修改crm用户信息
     */
    public function crmEditUser($data,$interFaceType){
    	$data['token'] = session('crm_token')?:"";     //登录凭证，必填
    	$crmModel = new ShSdkUserApi($this->crmConfig);
        $res = $crmModel->skdCommonInterface($data,$interFaceType);
        return $res;
    }

}