<?php
namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

/**
 * 基于类的实现方式
 */
class ConfigComposer {
	/**
	 * 共享配置数据
	 * @date   2017-01-06
	 * @author wcg
	 * @param  View       $view [description]
	 * @return [type]           [description]
	 */
	public function compose(View $view) {
        $agents_level = config('yazan.agents_level'); //获取配置文件代理商配置
		$exp_company = config('yazan.exp_company'); //获取配置文件代快递公司配置
		$order_status = config('yazan.order_status'); //获取配置文件订单状态配置

        $view->with('agents_level', $agents_level);
		$view->with('exp_company', $exp_company);
		$view->with('order_status', $order_status);
	}
}