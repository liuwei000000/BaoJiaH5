<?php 

		header('Access-Control-Allow-Origin:*');//ע�⣡����Ҫ�����ͷ �����Ǹ�û��
    $yanzheng = $_POST['yanzheng'];
    $danwei = $_POST['danwei'];
    $width = floatval($_POST['width']);
    $height = floatval($_POST['height']);
    $bizhong = $_POST['bizhong'];
    $danjia = intval($_POST['danjia']);
    $hanliang = intval($_POST['hanliang']);
    $houdu = intval($_POST['houdu']);
    $secai = intval($_POST['secai']);
    
    $midu = 0;
    $midu_b = array("", "10%","15%","18%", "20%", "BOPA//PE", "Other",  "PET//PA/PE");
    $hl = array(    "", 0.95, 0.96, 0.975, 0.98,  0.99, 1, 1.06);
    
    $c1 = array("", "0ɫ","1ɫ","2ɫ", "3ɫ", "4ɫ" , "5ɫ", "6ɫ", "7ɫ" , "8ɫ");
    $c2 = array("", 0,    0.15,  0.2,  0.3,   0.35,   0.4,   0.45,  0.5  , 0.6);
    $sec = 0;
    
    $HJCB = 802.30; //�ϼƳɱ�
    $ELL = 0.03749531; // ������
    $MYHL = 6; // ��Ԫ����
    $CDL = 2; // ������
    $SBBZJPZ = 29400 ;//�豸��׼����ֵ 
    
		$r = $_POST;
		unset($r["yanzheng"]);
		$r["r"] = 0;
		
		
		if ($width <= 0 || $height <= 0 || $danjia <= 0  || $houdu<= 0) {			
			$r["r"] = 1;
			goto rt;
		}
				
		$r['width'] = $width;
		$r['height'] = $height;
		$r['danjia'] = $danjia;
		
		if ($danwei == 'y') {
			$r['danwei'] =  iconv("GB2312","UTF-8//IGNORE", 'Ӣ��');
			$width *= 25.4;
			$height *= 25.4;
		} else {
			$r['danwei'] =  iconv("GB2312","UTF-8//IGNORE", '����');
		}
		
		if ($hanliang < 1 ||  $hanliang > 7) {
			$r["r"] = 1;
			goto rt;
		}
		
		$r['hanliang'] = iconv("GB2312","UTF-8//IGNORE", $midu_b[$hanliang]);
		$r["midu"] = $midu = $hl[$hanliang];
		
		if ($secai < 1 ||  $secai > 9) {
			$r["r"] = 1;
			goto rt;
		}		
		$r["secai"] = iconv("GB2312","UTF-8//IGNORE", $c1[$secai]);
		$sec = $c2[$secai];		
		
		$r["z"] = $width * $height * $houdu * 2.0 * $midu / 1000000.0;

		
		$r["cydj"] = $sec;
		$t = 0;
		if ($r["bizhong"] == "r") {
			$t = $HJCB / ($CDL *  $SBBZJPZ)*1000;
		} else {
			$t = $HJCB/ ( $CDL *  $SBBZJPZ) * 1000 / $MYHL / 1.13;
		}
		$r["zdcb"] = $r["z"] * $ELL * $r['danjia'] / 1000.0 + $t;


		$r["bmcb"] = $r["z"] * $r["danjia"] /1000/1000;
		if ($r["cydj"] != 0) {
				$r["bmcb"] = ($r["bmcb"] + $width * $height * 2/1000/1000*$r["cydj"]/6)*1.1;
		}
		$r["bmcb"] *= 1000;
		
		$r["zhzdcb"] = $r["zdcb"]/$r["z"]*1000;
		
		$r["zhbj"] = $r["bmcb"] + $r["zdcb"];
		$r["zhdj"] = $r["zhbj"]/$r["z"] * 1000;

		$r["zhbj"] = round($r["zhbj"], 2);
		$r["zhdj"] = round($r["zhdj"], 0);
		$r["zhzdcb"] = round($r["zhzdcb"], 2);
		$r["zdcb"] = round($r["zdcb"], 2);
		$r["z"] = round($r["z"], 2);
		$r["bmcb"] = round($r["bmcb"], 2);
		
		if ($r["bizhong"] == 'r') {
			$r["bizhong"] = iconv("GB2312","UTF-8//IGNORE", "�����");
		} else {
			$r["bizhong"] = iconv("GB2312","UTF-8//IGNORE", "��Ԫ");
		}
rt:
		echo json_encode($r);
?>
