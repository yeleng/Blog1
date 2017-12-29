<?php
namespace app\admin\validate;

use think\Validate;
use think\Db;

class Article extends Validate
{
    protected $db;
    public function get_list(){
    
    $list = Db::name('main_info')->paginate(10);
    return $list;
    
    }
}
