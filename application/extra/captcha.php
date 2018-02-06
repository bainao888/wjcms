<?php
return [
    
 
    // 关闭验证码杂点
    'useNoise'    =>    true, 
    // 验证码字符集合
    'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY', 
    // 验证码字体大小(px)
    'fontSize' => 25, 
    // 是否画混淆曲线
    'useCurve' => true, 
     // 验证码图片高度
    'imageH'   => 0,
    // 验证码图片宽度
    'imageW'   => 0, 
    // 验证码位数
    'length'   => 4, 
    //验证码过期时间（s）
    'expire'   =>1800,
    // 验证成功后是否重置        
    'reset'    => true
];