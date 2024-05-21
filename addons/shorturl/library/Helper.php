<?php
namespace addons\shorturl\library;

class Helper {

    /**
     * 微信访问
     */
    public static function is_weixin() {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * QQ访问
     */
    public static function is_qq() {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'QQ') !== false) {
            return true;
        } else {
            return false;
        }
    }

    private static function check_sub_strs($substrs, $text) {  
        foreach ($substrs as $substr) {
            if (false !== strpos($text, $substr)) {  
                return true;  
            }
        }
        return false;  
    }

    /**
     * 手机访问
     */
    public static function is_mobile() {  
        $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $useragent_commentsblock = preg_match('|\(.*?\)|', $useragent, $matches) > 0 ? $matches[0] : '';        
        $mobile_os_list = array(
            'Google Wireless Transcoder',
            'Windows CE',
            'WindowsCE',
            'Symbian',
            'Android',
            'armv6l',
            'armv5',
            'Mobile',
            'CentOS',
            'mowser',
            'AvantGo',
            'Opera Mobi',
            'J2ME/MIDP',
            'Smartphone',
            'Go.Web',
            'Palm',
            'iPAQ'
        );
        $mobile_token_list = array(
            'Profile/MIDP',
            'Configuration/CLDC-',
            '160×160',
            '176×220',
            '240×240',
            '240×320',
            '320×240',
            'UP.Browser',
            'UP.Link',
            'SymbianOS',
            'PalmOS',
            'PocketPC',
            'SonyEricsson',
            'Nokia',
            'BlackBerry',
            'Vodafone',
            'BenQ',
            'Novarra-Vision',
            'Iris',
            'NetFront',
            'HTC_',
            'Xda_',
            'SAMSUNG-SGH',
            'Wapaka',
            'DoCoMo',
            'iPhone',
            'iPod'
        );  
     
        $found_mobile = self::check_sub_strs($mobile_os_list, $useragent_commentsblock) ||  
                  self::check_sub_strs($mobile_token_list, $useragent);  
     
        if ($found_mobile) {  
            return true;  
        } else {  
            return false;  
        }  
    }
}