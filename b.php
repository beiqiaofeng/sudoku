<?php
$charsvar=$_POST['chars'];
//去掉的值的数目
$numsvar=$_POST['nums'];
$cncarray=array();
if(strlen($charsvar)>1){
	$cncarray_tmp=explode("-",$charsvar);	
	for($tt=1;$tt<=9;$tt++){
		$cncarray[$tt]=$cncarray_tmp[$tt-1];
	}
	$cncarray[0]="";
}else{
	$cncarray=array("0"=>"","1"=>"许","2"=>"武","3"=>"彬","4"=>"北","5"=>"乔","6"=>"峰","7"=>"慕","8"=>"荣","9"=>"福");
}
$sitxarray=array("1"=>"A","2"=>"B","3"=>"C","4"=>"D","5"=>"E","6"=>"F","7"=>"G","8"=>"H","9"=>"I");
//9个大格子数组
$ninesitarray=array();
//存放某个大格子可用的位置
$oldsavecanuse_array=array();
//存放所有9个大格子可用的位置
$oldsavecanuse_array_log=array();
//81个位置数组
$ret = getinit();
$data=$ret[0];
$ninesitarray=$ret[1];
$tmpanswerresult=array();
foreach($ninesitarray as $tmparrayvar9){
	foreach($tmparrayvar9 as $itemsvar){
		$tmpanswerresult[]=$itemsvar;
	}
}
shuffle($tmpanswerresult);
//删除的位置的坐标数组
$killarrayval=array();
for($k=1;$k<=$numsvar;$k++){
	$killarrayval[]['sit']=array_pop($tmpanswerresult);
}
//print_r($killarrayval);
//print_r($ninesitarray);
//循环1到9数字，进行设置值
$k=1;
$isdebug=false;
while(true){
	if($isdebug){
		echo("=========================================== begin $k ==================\n");
	}
	$oldsavecanuse_array=array();
	set_2($k);
	//检测本轮循环是否完
	if(!checkisfinish($k)){
		if($isdebug){
			echo("$k 轮循环没有完成，中途退出\n");
		}
		//print_r($oldsavecanuse_array_log[$k-1]);
		//初始化数据继续循环
		initme();
		$k=1;
		continue;
	}
	$k++;
	if($k>9){
		break;
	}
}
if($isdebug){
	print2();
}
//print_r($killarrayval);
foreach($killarrayval as $keys=>$vals){
	$sitvar=$vals['sit'];
	$killarrayval[$keys]['val']=$data[$sitvar[0]][$sitvar[1]]['val'];
	$data[$sitvar[0]][$sitvar[1]]['val']=0;
}
//print_r($killarrayval);

//print_r($data);
//functions zone---------------------------------------------------------
function initme(){
	global $data;
	global $oldsavecanuse_array;
	global $ninesitarray;
	$oldsavecanuse_array=array();
	$ret = getinit();
	$data=$ret[0];
	$ninesitarray=$ret[1];
}
function checkisfinish($vals){
	global $data;
	for($z=1;$z<=9;$z++){
		$isfinish=false;
		//循环检测9个大格子
		for($k=1;$k<=9;$k++){
			for($k2=1;$k2<=9;$k2++){
				if($data[$k][$k2]['sit']==$z && $data[$k][$k2]['val']==$vals){
					$isfinish=true;
				}
			}	
		}
		if(!$isfinish){
			return false;
		}
	}
	return true;
}


function shuzuchazhi($tmparray,$sitarray,$zone=0){
	$ret=array();
	foreach($tmparray as $items){
		if(($items[0]==$sitarray[0]) && ($items[1]==$sitarray[1])){
			//echo("区域 $zone 去除".$sitarray[0].":".$sitarray[1]."\n");
		}else{
			$ret[]=$items;
		}
	}
	return $ret;
}

//顺序设置数字1
function set_2($tmpnumvar){
	global $isdebug;
	global $data;
	global $ninesitarray;
	global $oldsavecanuse_array;
	$statnum=$tmpnumvar;
	//9个大格子按顺序进行赋值
	for($k=1;$k<=9;$k++){
		//echo($k."::\n");
		//当前格子空闲的数组
		if($isdebug){
			echo "--------------------------------------------------\n";
			echo "开始设置 $k 大格子的值\n";
		}
		for($ktmp=1;$ktmp<=9;$ktmp++){
			for($ktmp2=1;$ktmp2<=9;$ktmp2++){
				if($data[$ktmp][$ktmp2]['sit']==$k){
					if($data[$ktmp][$ktmp2]['val']==$statnum){
						if($tmpnumvar>1){
							if($isdebug){
								echo("$k 格子有值，继续循环\n");
							}
						}
						continue 3;
					}
				}
			}
		}
		$emptyarray=array();
		foreach($ninesitarray[$k] as $items){
			if($data[$items[0]][$items[1]]['val']==0){
				$emptyarray[]=$items;
			}
		}
		shuffle($emptyarray);
		//循环计算每个坐标，看是否可以放置1
		$ktmpnum=1;
		$canusegezinum=count($emptyarray);
		$oldsavecanuse_array[$k]=$emptyarray;
		$oldsavecanuse_array2[$k]=$emptyarray;	
		foreach($emptyarray as $items){
			//行数据处理
			$iffind=false;
			for($k2=1;$k2<=9;$k2++){
				if($data[$items[0]][$k2]['val']==$statnum){
					$oldsavecanuse_array[$k]=shuzuchazhi($oldsavecanuse_array[$k],$items,$k);
					$iffind=true;
					break;
				}
			}
			//列数据处理
			for($k2=1;$k2<=9;$k2++){
				if($data[$k2][$items[1]]['val']==$statnum){
					$oldsavecanuse_array[$k]=shuzuchazhi($oldsavecanuse_array[$k],$items,$k);
					$iffind=true;
					break;
				}
			}
		}
		//判断是否有可以放的位置
		if(count($oldsavecanuse_array[$k])){
			$getitem=array_pop($oldsavecanuse_array[$k]);
			if($isdebug){
				echo "坐标:".$getitem[0].",".$getitem[1]."\n";
			}
			$data[$getitem[0]][$getitem[1]]['val']=$statnum;
		}else{
			if($isdebug){
				echo("line116,警告,需要回溯,当前格子序号 $k :继续循环\n");
			}
			$bk=$k-1;
			if($isdebug){
				echo("回溯到 $bk 格子 \n");
			}
			if(count($oldsavecanuse_array[$bk])==0){     //8
				realhuishuo($bk,$statnum);
				$bk--;
				if($bk==0){
					if($isdebug){
						echo("回溯停止，已经到第一个大格了，bk值 $bk \n");
					}
					break;
				}
				if($isdebug){
					echo("继续回溯-loop2， $bk \n");
				}
				if(count($oldsavecanuse_array[$bk])==0){     //7
					realhuishuo($bk,$statnum);
					$bk--;
					if($bk==0){
						if($isdebug){
							echo("回溯停止，已经到第一个大格了，bk值 $bk \n");
						}
						break;
					}
					if($isdebug){
						echo("继续回溯-loop3， $bk \n");
					}
					if(count($oldsavecanuse_array[$bk])==0){    //6
						realhuishuo($bk,$statnum);
						$bk--;
						if($bk==0){
							if($isdebug){
								echo("回溯停止，已经到第一个大格了，bk值 $bk \n");
							}
							break;
						}	
						if($isdebug){						
							echo("继续回溯-loop4， $bk \n");
						}
						if(count($oldsavecanuse_array[$bk])==0){       //5
							realhuishuo($bk,$statnum);
							$bk--;
							if($bk==0){
								if($isdebug){
									echo("回溯停止，已经到第一个大格了，bk值 $bk \n");
								}
								break;
							}
							if($isdebug){
								echo("继续回溯-loop5， $bk \n");
							}
							if(count($oldsavecanuse_array[$bk])==0){       //4
								realhuishuo($bk,$statnum);
								$bk--;
								if($bk==0){
									if($isdebug){
										echo("回溯停止，已经到第一个大格了，bk值 $bk \n");
									}
									break;
								}
								if($isdebug){
									echo("继续回溯--loop6 $bk \n");
								}
								if(count($oldsavecanuse_array[$bk])==0){       //3
									realhuishuo($bk,$statnum);
									$bk--;
									if($bk==0){
										if($isdebug){
											echo("回溯停止，已经到第一个大格了，bk值 $bk \n");
										}
										break;
									}
									if($isdebug){
										echo("继续回溯-loop7 $bk \n");
									}
									if(count($oldsavecanuse_array[$bk])==0){       //2
										realhuishuo($bk,$statnum);
										$bk--;
										if($bk==0){
											if($isdebug){
												echo("回溯停止，已经到第一个大格了，bk值 $bk \n");
											}
											break;
										}
										if($isdebug){
											echo("继续回溯-loop8 $bk \n");
										}
										if(count($oldsavecanuse_array[$bk])==0){       //2
											realhuishuo($bk,$statnum);
											$bk--;
											if($bk==0){
												if($isdebug){
													echo("回溯停止，已经到第一个大格了，bk值 $bk \n");
												}
												break;
											}
											if($isdebug){
												echo("继续回溯-loop8 $bk \n");
											}
										}else{
											realhuishuo($bk,$statnum);
											$getitem=array_pop($oldsavecanuse_array[$bk]);
											if($isdebug){
												echo("设置坐标 $getitem[0],$getitem[1] 为$statnum \n");
											}
											$data[$getitem[0]][$getitem[1]]['val']=$statnum;
											set_2($statnum);
										}
									}else{
										realhuishuo($bk,$statnum);
										$getitem=array_pop($oldsavecanuse_array[$bk]);
										if($isdebug){
											echo("设置坐标 $getitem[0],$getitem[1] 为$statnum \n");
										}
										$data[$getitem[0]][$getitem[1]]['val']=$statnum;
										set_2($statnum);
									}
								}else{
									realhuishuo($bk,$statnum);
									$getitem=array_pop($oldsavecanuse_array[$bk]);
									if($isdebug){
										echo("设置坐标 $getitem[0],$getitem[1] 为$statnum \n");
									}
									$data[$getitem[0]][$getitem[1]]['val']=$statnum;
									set_2($statnum);
								}
							}else{
								realhuishuo($bk,$statnum);
								$getitem=array_pop($oldsavecanuse_array[$bk]);
								if($isdebug){
									echo("设置坐标 $getitem[0],$getitem[1] 为$statnum \n");
								}
								$data[$getitem[0]][$getitem[1]]['val']=$statnum;
								set_2($statnum);
							}
						}else{
							realhuishuo($bk,$statnum);
							$getitem=array_pop($oldsavecanuse_array[$bk]);
							if($isdebug){
								echo("设置坐标 $getitem[0],$getitem[1] 为$statnum \n");
							}
							$data[$getitem[0]][$getitem[1]]['val']=$statnum;
							set_2($statnum);
						}
					}else{
						realhuishuo($bk,$statnum);
						$getitem=array_pop($oldsavecanuse_array[$bk]);
						if($isdebug){
							echo("设置坐标 $getitem[0],$getitem[1] 为$statnum \n");
						}
						$data[$getitem[0]][$getitem[1]]['val']=$statnum;
						set_2($statnum);
					}
				}else{
					realhuishuo($bk,$statnum);
					$getitem=array_pop($oldsavecanuse_array[$bk]);
					if($isdebug){
						echo("设置坐标 $getitem[0],$getitem[1] 为$statnum \n");
					}
					$data[$getitem[0]][$getitem[1]]['val']=$statnum;
					set_2($statnum);
				}
			}else{
				realhuishuo($bk,$statnum);
				$getitem=array_pop($oldsavecanuse_array[$bk]);
				if($isdebug){
					echo("设置坐标 $getitem[0],$getitem[1] 为$statnum \n");
				}
				$data[$getitem[0]][$getitem[1]]['val']=$statnum;
				set_2($statnum);
			}
			break;
		}
		
		
	}
}

//具体回溯操作  $kvar 格子序号   $val 设置值
function realhuishuo($kvar,$val){
	global $data;
	global $isdebug;
	for($k=1;$k<=9;$k++){
		for($k2=1;$k2<=9;$k2++){
			/*
			if($data[$k][$k2]['sit']==$kvar){
				if($data[$k][$k2]['val']==$val){
					echo("具体回溯设置坐标 $k,$k2 值为0 \n");
					$data[$k][$k2]['val']=0;
				}
			}
			*/
			//把大于$kvar的格子也给设置为0
			for($hkk=$kvar;$hkk<=9;$hkk++){
				if($data[$k][$k2]['sit']==$hkk){
					if($data[$k][$k2]['val']==$val){
						if($isdebug){
							echo("具体回溯设置格子序号 $hkk ,坐标 $k,$k2 值为0 \n");
						}
						$data[$k][$k2]['val']=0;
					}
				}
			}
		}
	}
	//return $data;
}

function getinit() {
	//行坐标
	$k = 1;
	//列坐标
	$j = 1;
	$tmparray=array();
	while (true) {
		while (true) {
			$data[$k][$j]['val'] = 0;
			if (($k >= 1 && $k <= 3) && ($j >= 1 && $j <= 3)) {
				$data[$k][$j]['sit'] = 1;
				$tmparray['1'][]=array($k,$j);
			}
			if (($k >= 4 && $k <= 6) && ($j >= 1 && $j <= 3)) {
				$data[$k][$j]['sit'] = 4;
				$tmparray['4'][]=array($k,$j);
			}
			if (($k >= 7 && $k <= 9) && ($j >= 1 && $j <= 3)) {
				$data[$k][$j]['sit'] = 7;
				$tmparray['7'][]=array($k,$j);
			}
			if (($k >= 1 && $k <= 3) && ($j >= 4 && $j <= 6)) {
				$data[$k][$j]['sit'] = 2;
				$tmparray['2'][]=array($k,$j);
			}
			if (($k >= 4 && $k <= 6) && ($j >= 4 && $j <= 6)) {
				$data[$k][$j]['sit'] = 5;
				$tmparray['5'][]=array($k,$j);
			}
			if (($k >= 7 && $k <= 9) && ($j >= 4 && $j <= 6)) {
				$data[$k][$j]['sit'] = 8;
				$tmparray['8'][]=array($k,$j);
			}
			if (($k >= 1 && $k <= 3) && ($j >= 7 && $j <= 9)) {
				$data[$k][$j]['sit'] = 3;
				$tmparray['3'][]=array($k,$j);
			}
			if (($k >= 4 && $k <= 6) && ($j >= 7 && $j <= 9)) {
				$data[$k][$j]['sit'] = 6;
				$tmparray['6'][]=array($k,$j);
			}
			if (($k >= 7 && $k <= 9) && ($j >= 7 && $j <= 9)) {
				$data[$k][$j]['sit'] = 9;
				$tmparray['9'][]=array($k,$j);
			}			
			
			$j++;
			if ($j > 9) {
				$j = 1;
				break;
			}
		}
		$k++;
		if ($k > 9) {
			break;
		}
	}
	return array($data,$tmparray);
}

	function print2(){
		global $data;
		foreach($data as $key=>$item){
			echo "          \n";
			if($key==4 || $key==7){
				echo("---------------------------------------------\n");
			}
			foreach($item as $key2=>$item2){
				if($key2==4 || $key2==7){
					echo(" | ");
				}
				echo $data[$key][$key2]['val']."   ";
			}
			echo "\n";
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>计算结果-汉字数独</title>
	<style>
		.container {
			position: relative;
			width: 1000px;
			text-align: center;
			margin: auto;
			/*border: 1px solid red;*/
		}
		ul,li{
			list-style: none;	
			margin: 0;
			padding: 0;		
		}
		.sd{
			width: 56%;
			/*border: 1px solid red;*/
			margin: 0 auto;
		}
		.sdspan{
			float: left;
			display: inline-block;
			margin: 0;
			border: 1px dotted green;
			height:60px;
			width: 60px;
			line-height: 60px;
			text-align: center;
			font-size: 30px;
		}
		.sdli{
			width: 100%;
			/*height: 26px;*/
			float: left;
		}

		.bl{
			border-left-color: green;
			border-left-style: solid;
		}
		.bt{
			border-top-color: green;
			border-top-style: solid;
		}
		.br{
			border-right-color: green;
			border-right-width: 2px;
			border-right-style: solid;
		}
		.bb{
			border-bottom-color: green;
			border-bottom-width: 2px;
			border-bottom-style: solid;
		}
		.blankCell{
			color:blue;
			font-weight: 600;
		}
		.bg_red{
			background: red;
		}
		.clearfix:after{
			content: '';
			display: table;
			clear: both;
		}
		.btn-group{
			margin-top:20px;
			text-align:center;
		}
		.send {
			width: 56%;
			margin: auto;
		}
		.send::after,.sdli::after,.sd::after,.answer::after {
			clear: both;
			display: block;
			content: '';
		}
		.send li {
			float: left;
			width: 62px;
			height: 59px;
			line-height: 59px;

		}
		.position-ul{
			position: absolute; 
			left: 143px;
		}
		.position-ul li {
			
			width: 72px;
			height: 62px;
			line-height: 70px;
		}
		/*#dcf2f0*/
		.bgcolor {
			background: #dcf2f0;
		}
		button {
			margin-top: 20px;
			margin-left: 20px;
			margin-bottom: 20px;
			width: 130px;
			height: 50px;
			border:1px solid green;
			background: green;
			color: #fff;
			font-size: 20px;
			border-radius: 7px;
			cursor: pointer;
			outline: none;

		}
		.answer {
			margin: auto;
			width: 71%;
			border-left: 1px solid green;
			border-right: 1px solid green;
			border-top: 1px solid green;
		}
		.xia {
			border-bottom: 1px solid green;
		}
		.answer li{
			float: left;
			width: 70px;
			height: 70px;
			line-height: 70px;
			border-right:1px solid green;
		}
		.answer li:last-of-type {
			border:0;
		}
	</style>
</head>
<body>
	<div class="container">
		<ul class="send">
			<li class="send-li">A</li>
			<li>B</li>
			<li>C</li>
			<li>D</li>
			<li>E</li>
			<li>F</li>
			<li>G</li>
			<li>H</li>
			<li>I</li>
		</ul>
		<ul class="position-ul">
			<li class="position-ul-li">1</li>
			<li>2</li>
			<li>3</li>
			<li>4</li>
			<li>5</li>
			<li>6</li>
			<li>7</li>
			<li>8</li>
			<li>9</li>
		</ul>
		<ul class="sd clearfix">
			<li class="sdli">
				<span class="sdspan"><?php echo($cncarray[$data[1][1]['val']]);?></span>
				<span class="sdspan" contenteditable="false"><?php echo($cncarray[$data[1][2]['val']]);?></span>
				<span class="sdspan br"><?php echo($cncarray[$data[1][3]['val']]);?></span>
				<span class="sdspan bl bgcolor"><?php echo($cncarray[$data[1][4]['val']]);?></span>
				<span class="sdspan bgcolor"><?php echo($cncarray[$data[1][5]['val']]);?></span>
				<span class="sdspan br bgcolor"><?php echo($cncarray[$data[1][6]['val']]);?></span>
				<span class="sdspan bl"><?php echo($cncarray[$data[1][7]['val']]);?></span>
				<span class="sdspan"><?php echo($cncarray[$data[1][8]['val']]);?></span>
				<span class="sdspan" contenteditable="false"><?php echo($cncarray[$data[1][9]['val']]);?></span>
			</li>
			<li class="sdli">
				<span class="sdspan"><?php echo($cncarray[$data[2][1]['val']]);?></span>
				<span class="sdspan" contenteditable="false"><?php echo($cncarray[$data[2][2]['val']]);?></span>
				<span class="sdspan br" contenteditable="false"><?php echo($cncarray[$data[2][3]['val']]);?></span>
				<span class="sdspan bl bgcolor" contenteditable="false"><?php echo($cncarray[$data[2][4]['val']]);?></span>
				<span class="sdspan bgcolor"><?php echo($cncarray[$data[2][5]['val']]);?></span>
				<span class="sdspan br bgcolor"><?php echo($cncarray[$data[2][6]['val']]);?></span>
				<span class="sdspan bl" contenteditable="false"><?php echo($cncarray[$data[2][7]['val']]);?></span>
				<span class="sdspan"><?php echo($cncarray[$data[2][8]['val']]);?></span>
				<span class="sdspan" contenteditable="false"><?php echo($cncarray[$data[2][9]['val']]);?></span>
			</li>
			<li class="sdli">
				<span class="sdspan bb" contenteditable="false"><?php echo($cncarray[$data[3][1]['val']]);?></span>
				<span class="sdspan bb"><?php echo($cncarray[$data[3][2]['val']]);?></span>
				<span class="sdspan br bb"><?php echo($cncarray[$data[3][3]['val']]);?></span>
				<span class="sdspan bl bb bgcolor"><?php echo($cncarray[$data[3][4]['val']]);?></span>
				<span class="sdspan bb bgcolor"><?php echo($cncarray[$data[3][5]['val']]);?></span>
				<span class="sdspan br bb bgcolor"><?php echo($cncarray[$data[3][6]['val']]);?></span>
				<span class="sdspan bl bb"><?php echo($cncarray[$data[3][7]['val']]);?></span>
				<span class="sdspan bb"><?php echo($cncarray[$data[3][8]['val']]);?></span>
				<span class="sdspan bb" contenteditable="false"><?php echo($cncarray[$data[3][9]['val']]);?></span>
			</li>
			<li class="sdli">
				<span class="sdspan bt bgcolor"><?php echo($cncarray[$data[4][1]['val']]);?></span>
				<span class="sdspan bt bgcolor" contenteditable="false"><?php echo($cncarray[$data[4][2]['val']]);?></span>
				<span class="sdspan br bt  bgcolor" contenteditable="false"><?php echo($cncarray[$data[4][3]['val']]);?></span>
				<span class="sdspan bl bt"><?php echo($cncarray[$data[4][4]['val']]);?></span>
				<span class="sdspan bt" contenteditable="false"><?php echo($cncarray[$data[4][5]['val']]);?></span>
				<span class="sdspan br bt"><?php echo($cncarray[$data[4][6]['val']]);?></span>
				<span class="sdspan bl bt bgcolor"><?php echo($cncarray[$data[4][7]['val']]);?></span>
				<span class="sdspan bt bgcolor"><?php echo($cncarray[$data[4][8]['val']]);?></span>
				<span class="sdspan bt bgcolor" contenteditable="false"><?php echo($cncarray[$data[4][9]['val']]);?></span>
			</li>
			<li class="sdli">
				<span class="sdspan bgcolor"><?php echo($cncarray[$data[5][1]['val']]);?></span>
				<span class="sdspan bgcolor" contenteditable="false"><?php echo($cncarray[$data[5][2]['val']]);?></span>
				<span class="sdspan br bgcolor"><?php echo($cncarray[$data[5][3]['val']]);?></span>
				<span class="sdspan bl"><?php echo($cncarray[$data[5][4]['val']]);?></span>
				<span class="sdspan"><?php echo($cncarray[$data[5][5]['val']]);?></span>
				<span class="sdspan br"><?php echo($cncarray[$data[5][6]['val']]);?></span>
				<span class="sdspan bl bgcolor" contenteditable="false"><?php echo($cncarray[$data[5][7]['val']]);?></span>
				<span class="sdspan bgcolor" contenteditable="false"><?php echo($cncarray[$data[5][8]['val']]);?></span>
				<span class="sdspan bgcolor"><?php echo($cncarray[$data[5][9]['val']]);?></span>
			</li>
			<li class="sdli">
				<span class="sdspan bb bgcolor" contenteditable="false"><?php echo($cncarray[$data[6][1]['val']]);?></span>
				<span class="sdspan bb bgcolor"><?php echo($cncarray[$data[6][2]['val']]);?></span>
				<span class="sdspan br bb bgcolor"><?php echo($cncarray[$data[6][3]['val']]);?></span>
				<span class="sdspan bl bb"><?php echo($cncarray[$data[6][4]['val']]);?></span>
				<span class="sdspan bb" contenteditable="false"><?php echo($cncarray[$data[6][5]['val']]);?></span>
				<span class="sdspan br bb" contenteditable="false"><?php echo($cncarray[$data[6][6]['val']]);?></span>
				<span class="sdspan bl bb bgcolor" contenteditable="false"><?php echo($cncarray[$data[6][7]['val']]);?></span>
				<span class="sdspan bb bgcolor" contenteditable="false"><?php echo($cncarray[$data[6][8]['val']]);?></span>
				<span class="sdspan bb bgcolor"><?php echo($cncarray[$data[6][9]['val']]);?></span>
			</li>
			<li class="sdli">
				<span class="sdspan bt"><?php echo($cncarray[$data[7][1]['val']]);?></span>
				<span class="sdspan bt"><?php echo($cncarray[$data[7][2]['val']]);?></span>
				<span class="sdspan br bt"><?php echo($cncarray[$data[7][3]['val']]);?></span>
				<span class="sdspan bl bt bgcolor" contenteditable="false"><?php echo($cncarray[$data[7][4]['val']]);?></span>
				<span class="sdspan bt bgcolor" contenteditable="false"><?php echo($cncarray[$data[7][5]['val']]);?></span>
				<span class="sdspan br bt bgcolor" contenteditable="false"><?php echo($cncarray[$data[7][6]['val']]);?></span>
				<span class="sdspan bl bt"><?php echo($cncarray[$data[7][7]['val']]);?></span>
				<span class="sdspan bt"><?php echo($cncarray[$data[7][8]['val']]);?></span>
				<span class="sdspan bt"><?php echo($cncarray[$data[7][9]['val']]);?></span>
			</li>
			<li class="sdli">
				<span class="sdspan"><?php echo($cncarray[$data[8][1]['val']]);?></span>
				<span class="sdspan" contenteditable="false"><?php echo($cncarray[$data[8][2]['val']]);?></span>
				<span class="sdspan br"><?php echo($cncarray[$data[8][3]['val']]);?></span>
				<span class="sdspan bl bgcolor"><?php echo($cncarray[$data[8][4]['val']]);?></span>
				<span class="sdspan bgcolor"><?php echo($cncarray[$data[8][5]['val']]);?></span>
				<span class="sdspan br bgcolor" contenteditable="false"><?php echo($cncarray[$data[8][6]['val']]);?></span>
				<span class="sdspan bl" contenteditable="false"><?php echo($cncarray[$data[8][7]['val']]);?></span>
				<span class="sdspan" contenteditable="false"><?php echo($cncarray[$data[8][8]['val']]);?></span>
				<span class="sdspan" contenteditable="false"><?php echo($cncarray[$data[8][9]['val']]);?></span>
			</li>
			<li class="sdli">
				<span class="sdspan"><?php echo($cncarray[$data[9][1]['val']]);?></span>
				<span class="sdspan" contenteditable="false"><?php echo($cncarray[$data[9][2]['val']]);?></span>
				<span class="sdspan br"><?php echo($cncarray[$data[9][3]['val']]);?></span>
				<span class="sdspan bl bgcolor" contenteditable="false"><?php echo($cncarray[$data[9][4]['val']]);?></span>
				<span class="sdspan bgcolor" contenteditable="false"><?php echo($cncarray[$data[9][5]['val']]);?></span>
				<span class="sdspan br bgcolor"><?php echo($cncarray[$data[9][6]['val']]);?></span>
				<span class="sdspan bl" contenteditable="false"><?php echo($cncarray[$data[9][7]['val']]);?></span>
				<span class="sdspan" contenteditable="false"><?php echo($cncarray[$data[9][8]['val']]);?></span>
				<span class="sdspan" contenteditable="false"><?php echo($cncarray[$data[9][9]['val']]);?></span>
			</li>
		</ul>
		<button onclick="location.reload();">刷新</button>
		<button onclick="window.location.replace('index.html');">返回</button>
		<ul class="answer">
			<li>坐标</li>
                        <?php
                        foreach($killarrayval as $items){
                           echo("<li>".$items['sit'][0].",".$sitxarray[$items['sit'][1]]."</li>");
                        }
                        ?>
		</ul>
		<ul class="answer xia">
			<li>答案</li>
                        <?php
                        foreach($killarrayval as $items){
                           echo("<li>".$cncarray[$items['val']]."</li>");
                        }
                        ?>
		</ul>
	</div>
</body>
</html>