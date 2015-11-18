<?php
	require_once 'global.php';
	require_once 'girls-setting.php';

	// sleep(30);

	$totalRecords = D('girls')->field('count(*)')->get();
	$total = $totalRecords[0]['count(*)'];

	$draw = intval($_REQUEST['draw']);
	$start = intval($_REQUEST['start']);
	$length = intval($_REQUEST['length']);
	$fields = array('id', 'name', 'gender', 'height', 'weight', 'bust', 'waist', 'hip');
	$orderBy = $fields[$_REQUEST['order'][0]['column']].' '.$_REQUEST['order'][0]['dir'];
	$where = array('state' => 1);
	if (false == empty($_REQUEST['search']['value'])) {
		$where['name-lk'] = $_REQUEST['search']['value'];
	}
	$records = D('girls')->order($orderBy)->field('id, name, avatar,gender, height, weight, bust, waist, hip')->limit($start, $length)->get($where);

	$data = array();
	foreach ($records as $value) {
		$row = array();
		foreach($value as $key => $val){
			switch ($key) {
				case 'avatar':
					
					break;
				case 'name':
					$row[] = empty($value['avatar']) ? $val : "<img src='$baseurl{$value['avatar']}' style='width:50px;'> ".$val;
					break;
				case 'gender':
					$row[] = $gender[$val];
					break;
				case 'height':
					$row[] = $val.'cm';
					break;
				case 'weight':
					$row[] = $val.'kg';
					break;
				default:
					$row[] = $val;
					break;
			}
			
		}
		$actionStr = "<a href='albums-list.php?id={$value['id']}' class='btn btn-info btn-xs'>相册</a>";
		$actionStr .= " <a href='girls-edit.php?id={$value['id']}' class='btn btn-primary btn-xs'>编辑</a>";
		$actionStr .= " <a class='btn btn-danger btn-xs' data-msg='确定删除吗？数据删除之后无法撤销' data-url='girls-agent.php?action=delete&id={$value['id']}' data-toggle=\"modal\" data-target=\".bs-modal-alert\">删除</a>";
		$row[] = $actionStr;
		$data[] = $row;
	}
	echo json_encode(array(
			'sEcho'=> ++$draw,
			"iTotalRecords"=> $total,
			"iTotalDisplayRecords"=> $total,
			"aaData"=> $data
		));