<?php
namespace Shenghuo\request;
/**
 * author:czk
 * time:2019-06-18
 */
require_once './vendor/shenghuo/php-sdk/ShSdkInterFaceName.php';
require_once './vendor/shenghuo/php-sdk/ShSdkConfig.php';
require_once './vendor/shenghuo/php-sdk/ShSdkCommon.php';

class ShSdkBase
{
	public $appId,$appSecret,$privateKey,$publicKey,$sign;
	public function __construct($config){
		if (empty($config)) {
			return sdkReturnArr(FAIL,[],"获取项目app配置失败");
		}
		$this->appId      = $config['app_id'];
		$this->appSecret  = $config['app_secret'];
		$this->privateKey = $config['private_key'];
		$this->publicKey  = $config['public_key'];
	}

	/**
	 * 加密
	 */
	public function encryption($data){
		$key   = openssl_pkey_get_public($this->publicKey);		//获取公钥
		if (!$key) {
			return sdkReturnArr(FAIL,[],"获取公钥失败");
		}
		$jdata = json_encode($data);
		$flag  = openssl_public_encrypt($jdata, $crypted, $key);
		if (!$flag) {
			return sdkReturnArr(FAIL,[],"加密失败");
		}
		$encryData  = base64_encode($crypted);
		return sdkReturnArr(SUCCESS,$encryData,"加密成功");
	}

	/**
	 * 排序
	 */
	public function ToUrlParams()
    {
        $buff = "";
        foreach ($this->values as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
    
        $buff = trim($buff, "&");
        return $buff;
    }
    /**
     * 生成签名
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    public function MakeSign($data)
    {
    	$this->values = $data;
        //签名步骤一：按字典序排序参数
        ksort($this->values);
        $string = $this->ToUrlParams();
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$this->appSecret;
    	// p($string);
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    
}