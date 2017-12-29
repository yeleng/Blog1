<?php

namespace app\index\model;

use think\Model;

class Student extends Model 
{	
    //命名与表名一致,me_student -> Student.php Student
    //me_student_info StudentInfo.php Student_info 
    public function getNameAttr($val){ //这里是有数组传入时候输出
        dump('------');
        dump($val);
        dump('------');
    }
}