<?php

namespace App\Helpers;

class StringHelper
{
    public static function removeVietnameseAccents($str) {
        $chars = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'd'=>'đ',
            'D'=>'Đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ'
        );
        
        foreach ($chars as $key => $value) {
            $str = preg_replace("/($value)/i", $key, $str);
        }
        
        return $str;
    }

    public static function createSearchableText($str) {
        // Chuyển về chữ thường và bỏ dấu
        $str = mb_strtolower(self::removeVietnameseAccents($str));
        
        // Loại bỏ các ký tự đặc biệt
        $str = preg_replace('/[^a-z0-9\s]/', '', $str);
        
        // Chuẩn hóa khoảng trắng
        $str = preg_replace('/\s+/', ' ', trim($str));
        
        return $str;
    }
} 