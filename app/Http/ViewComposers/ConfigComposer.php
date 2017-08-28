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

		$view->with('agents_level', $agents_level);
	}
}