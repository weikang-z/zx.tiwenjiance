<?php
	return [];


function dataIfAliveToUpdate($data, $key, $_model, $hidden = [])
{

	$keys = array_column($data, $key);
	$alive = $_model::where($key, 'in', $keys)->field($key)->select();
	$alive_keys = [];
	if ($alive) {
		$alive = collection($alive)->toArray();
		$alive_keys = array_unique(array_column($alive, $key));
	}
	$insert = [];
	foreach ($data as $v) {
		$k = $v[$key];
		if (in_array($k, $alive_keys)) {
			// 更新
			$update_data = [];
			foreach ($v as $jz => $item) {
				if (!in_array($jz, $hidden)) {
					$update_data[$jz] = $item;
				}
			}
			$_model::where($key, $v[$key])->update($update_data);
		} else {
			$insert[] = $v;
		}
	}
	return $insert;
}
