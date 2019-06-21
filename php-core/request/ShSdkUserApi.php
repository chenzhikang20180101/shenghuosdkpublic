<?php
namespace Shenghuo\request;

/**
 * author:czk
 * time:2019-06-18
 */
class ShSdkUserApi extends ShSdkBase
{
	
	public function __construct($config)
	{
		parent::__construct($config);
	}

	/**
	 * 注册
	 */
	public function sdkUserRegister($data){
		$data['app_id'] = $this->appId;
		// 密码加密
		$encryPsd = $this->encryption($data['password']);
		if ($encryPsd['code']==FAIL) {
			return $encryPsd;
		}
		$data['password'] = $encryPsd['data'];
		// 密码加密
		$encryPyd = $this->encryption($data['payword']);
		if ($encryPyd['code']==FAIL) {
			return $encryPyd;
		}
		$data['payword'] = $encryPyd['data'];
		// 生成签名串
		$data['sign'] = $this->MakeSign($data);
		// 请求会员管理系统接口
		$url = API_HOST.SDK_REGISTER;
		$res = sdk_https_request($url, 'POST', $data);
		$res = json_decode($res,true);
		return $res;
	}

	/**
	 * 用户登录
	 */
	public function sdkUserLogin($data){
		$data['app_id'] = $this->appId;
		// 密码加密
		$encryPsd = $this->encryption($data['password']);
		if ($encryPsd['code']==FAIL) {
			return $encryPsd;
		}
		$data['password'] = $encryPsd['data'];
		// 生成签名串
		$data['sign'] = $this->MakeSign($data);
		// 请求会员管理系统接口
		$url = API_HOST.SDK_LOGIN;
		$res = sdk_https_request($url, 'POST', $data);
		$res = json_decode($res,true);
		return $res;

	}

	/**
	 * 通用接口
	 */
	public function skdCommonInterface($data,$interFaceType){
		$data['app_id'] = $this->appId;
		// 生成签名串
		$data['sign']   = $this->MakeSign($data);
		// 请求会员管理系统接口
		$url = API_HOST.$interFaceType;
		$res = sdk_https_request($url, 'POST', $data);
		$res = json_decode($res,true);
		return $res;
	}

	

	public function test(){
		$data['code'] = 0;
		$data['data'] = '123123';
		// return resultArray($data);
		// var_dump($config);
		var_dump($this->appId);
		var_dump($this->appKey);
		echo "string123123";
	}
}