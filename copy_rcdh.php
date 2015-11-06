# -
地方議會資料分類小程式
<pre>
<?php
		//header("Content-Type: text/html;charset=utf-8"); 

		ini_set('memory_limit', '-1'); //加大記憶體空間 
		
		set_time_limit(0);  
		
		ini_set("upload_max_filesize","400M"); 
		
		ini_set("post_max_size","500M"); 
		
		function inputUI($t){
			if($t!="" && preg_match('/\D{1}\:\/(.*)\//',$t,$v)){
				fwrite(STDOUT, "Hello, Your copy destination location is $t !\n");
			}
			else{
				fwrite(STDERR, "You not input path or wrong path!\n");
				fwrite(STDOUT, "Enter your location again: (eg.M:/passer_test5/)\n");
				$p = trim(fgets(STDIN));
				inputUI($p);
			}
		}
		
		//透過 標準輸出 印出要詢問的內容
		fwrite(STDOUT, "Enter your copy destination location: (eg.M:/passer_test5/)\n");
		// 抓取 標準輸入 的 內容
		$HDD_destloc = trim(fgets(STDIN));
		// 將剛剛輸入的內容, 送到 標準輸出
		inputUI($HDD_destloc);
		
		//透過 標準輸出 印出要詢問的內容
		fwrite(STDOUT, "Enter your copy source location: (eg.H:/012/)\n");
		// 抓取 標準輸入 的 內容
		$Path = trim(fgets(STDIN));
		// 將剛剛輸入的內容, 送到 標準輸出
		inputUI($Path);
		
		//mkdir('/raid/FTPFolder/passer/', 0700);
		//$HDD_destloc = 'M:/passer_test5/';
		//$HDD_destloc = '/raid/USBHDD/usb30/2/passer/';	
		//$NAS_destloc = '/raid/FTPFolder/passer/';
		
		//$Path ='H:/012/';
		//$dir1 ='/raid/USBHDD/usb28/1/012基隆市議會影像/012基隆市議事錄/'; //第一層016
		$dir1 = mb_convert_encoding($dir1,'big5', 'utf8');
		
		function dir_path($path) { 
			$path = str_replace('\\', '/', $path); 
			if (substr($path, -1) != '/') $path = $path . '/'; 
			return $path; 
		}
		

		function dir_list($path, $exts = '', $list = array()) { 
			$path = dir_path($path); 
			$files = glob($path . '*'); 
			foreach($files as $v) { 
			
				if (!$exts || preg_match("/\.($exts)/i", $v)) { 
				$list[] = $v; 
					if (is_dir($v)) { 
					$list = dir_list($v, $exts, $list); 
					} 
				} 
			} 
			return $list; 
		} 
		
		$r = dir_list($Path); 
		//printf("<p>輸出數據為：</p><pre>%s</pre>\n", var_export($r , true)); 
		
		foreach($r as $value){
			echo "/////////////////////////////////////////////////////////////////"."</br>";
			echo "value:".$value."</br>";
			$array = explode("/",$value);
			
			echo "</br>"."$array:"."</br>";
			var_dump($array);
			
			$file = $array[(count($array))-1];
			echo "</br>"."file:".$file."</br>";
			
			preg_match('/\w{4}\-\d{2}\-\d{2}(.*)\.\D{3}/',$file,$cfile);
			echo "</br>"."cfile"."</br>";
			var_dump($cfile);			
		
			if($cfile!=NULL){  //符合格式三串數值
				$filenum = substr($file,0,4); //抓第一個數值;
				echo "<br>"."filenum"."<br>";
				echo $filenum."</br>";
			
				$file_array = explode("-",$file);
				echo "<br>"."file_array"."<br>";
				var_dump($file_array);
			
				$filename=$file_array[0].'-'.$file_array[1].'-'.$file_array[2];
				echo "<br>"."filename"."<br>";
				echo $filename."</br>";
				
				$type = substr($file,-3,4);
				echo "<br>"."type"."<br>";
				echo $type."</br>";
				
				if(!is_file($HDD_destloc.$filenum.'/'."JPEG-72".'/'.$file) && !is_file($HDD_destloc.$filenum.'/'."JPEG-300".'/'.$file) && !is_file($HDD_destloc.$filenum.'/'."PDF-150".'/'.$file) && !is_file($HDD_destloc.$filenum.'/'."PNG-300".'/'.$file) && !is_file($HDD_destloc.$filenum.'/'."TIFF-300".'/'.$file) && !is_file($HDD_destloc.$filenum.'/'."ERROR_FILE".'/'.$file)){
				
				mkdir($HDD_destloc.$filenum, 0700);				
		
				//複製檔案及印出 log.txt
				if($type == "pdf"){
					if($array[2]=="PDF-150"){
						mkdir($HDD_destloc.$filenum.'/'."PDF-150", 0700);
						
						$logpath = $HDD_destloc.$filenum.'/'."PDF-150".'/'.'log.txt';
						echo "</br>"."logpath:".$logpath."</br>";
						$fp3 = fopen($logpath, 'a+');
						fseek($fp3, 0);
						
						$c = copy($value , $HDD_destloc.$filenum.'/'."PDF-150".'/'.$file);
						if($c==true){fwrite($fp3,"$file copied to HDD successfully!\n");}
						fclose($fp3);
					}
					else{
						mkdir($HDD_destloc.$filenum.'/'."PDF-150", 0700);
						
						$logpath = $HDD_destloc.$filenum.'/'."PDF-150".'/'.'log.txt';
						echo "</br>"."logpath:".$logpath."</br>";
						$fp3 = fopen($logpath, 'a+');
						fseek($fp3, 0);
						
						$c = copy($value , $HDD_destloc.$filenum.'/'."PDF-150".'/'.$file);
						if($c==true){fwrite($fp3,"$file is not in correct Folder PDF-150 but in $value and copied to HDD\n");}
						fclose($fp3);
					}
				}
				else if($type == "tif"){
					if($array[2]=="TIFF-300"){
						mkdir($HDD_destloc.$filenum.'/'."TIFF-300", 0700);
						mkdir($HDD_destloc.$filenum.'/'."TIFF-300".'/'.$filename, 0700);
						
						$logpath = $HDD_destloc.$filenum.'/'."TIFF-300".'/'.'log.txt';
						echo "</br>"."logpath:".$logpath."</br>";
						$fp5 = fopen($logpath, 'a+');
						fseek($fp5, 0);
						
						$c = copy($value , $HDD_destloc.$filenum.'/'."TIFF-300".'/'.$filename.'/'.$file);
						if($c==true){fwrite($fp5,"$file copied to HDD successfully!\n");}
						fclose($fp5);
					}
					else{
						mkdir($HDD_destloc.$filenum.'/'."TIFF-300", 0700);
						mkdir($HDD_destloc.$filenum.'/'."TIFF-300".'/'.$filename, 0700);
						
						$logpath = $HDD_destloc.$filenum.'/'."TIFF-300".'/'.'log.txt';
						echo "</br>"."logpath:".$logpath."</br>";
						$fp5 = fopen($logpath, 'a+');
						fseek($fp5, 0);
						
						$c = copy($value , $HDD_destloc.$filenum.'/'."TIFF-300".'/'.$filename.'/'.$file);
						if($c==true){fwrite($fp5,"$file is not in correct Folder TIFF-300 but in $value and copied to HDD\n");}	
						fclose($fp5);
					}
				}
				else if($type == "png"){
					if($array[2]=="PNG-300"){
						mkdir($HDD_destloc.$filenum.'/'."PNG-300", 0700);
						mkdir($HDD_destloc.$filenum.'/'."PNG-300".'/'.$filename, 0700);
						
						$logpath = $HDD_destloc.$filenum.'/'."PNG-300".'/'.'log.txt';
						echo "</br>"."logpath:".$logpath."</br>";
						$fp4 = fopen($logpath, 'a+');
						fseek($fp4, 0);
						
						$c = copy($value , $HDD_destloc.$filenum.'/'."PNG-300".'/'.$filename.'/'.$file);
						if($c==true){fwrite($fp4,"$file copied to HDD successfully!\n");}
						fclose($fp4);
					}
					else{
						mkdir($HDD_destloc.$filenum.'/'."PNG-300", 0700);
						mkdir($HDD_destloc.$filenum.'/'."PNG-300".'/'.$filename, 0700);
						
						$logpath = $HDD_destloc.$filenum.'/'."PNG-300".'/'.'log.txt';
						echo "</br>"."logpath:".$logpath."</br>";
						$fp4 = fopen($logpath, 'a+');
						fseek($fp4, 0);
						
						$c = copy($value , $HDD_destloc.$filenum.'/'."PNG-300".'/'.$filename.'/'.$file);
						if($c==true){fwrite($fp4,"$file is not in correct Folder PNG-300 but in $value and copied to HDD\n");}
						fclose($fp4);
					}
				}
				else if($type == "jpg"){	
					if($array[2] == "JPEG-72"){
						mkdir($HDD_destloc.$filenum.'/'."JPEG-72", 0700);
						mkdir($HDD_destloc.$filenum.'/'."JPEG-72".'/'.$filename, 0700);
						
						$logpath = $HDD_destloc.$filenum.'/'."JPEG-72".'/'.'log.txt';
						echo "</br>"."logpath:".$logpath."</br>";
						$fp1 = fopen($logpath, 'a+');
						fseek($fp1, 0);
						
						$c = copy($value , $HDD_destloc.$filenum.'/'."JPEG-72".'/'.$filename.'/'.$file);
						if($c==true){fwrite($fp1,"$file copied to HDD successfully!\n");}
						fclose($fp1);
					}
					elseif($array[2] == "JPEG-300"){
						mkdir($HDD_destloc.$filenum.'/'."JPEG-300", 0700);
						mkdir($HDD_destloc.$filenum.'/'."JPEG-300".'/'.$filename, 0700);
						
						$logpath = $HDD_destloc.$filenum.'/'."JPEG-300".'/'.'log.txt';
						echo "</br>"."logpath:".$logpath."</br>";
						$fp2 = fopen($logpath, 'a+');
						fseek($fp2, 0);
						
						$c = copy($value , $HDD_destloc.$filenum.'/'."JPEG-300".'/'.$filename.'/'.$file);
						if($c==true){fwrite($fp2,"$file copied to HDD successfully!\n");}
						fclose($fp2);
					}
					else{
						mkdir($HDD_destloc.$filenum.'/'."ERROR_FILE", 0700);
						
						$logpath = $HDD_destloc.$filenum.'/'."ERROR_FILE".'/'.'log.txt';
						echo "</br>"."logpath:".$logpath."</br>";
						$fp6 = fopen($logpath, 'a+');
						fseek($fp6, 0);
						
						$c = copy($value , $HDD_destloc."ERROR_FILE".'/'.$file);
						if($c==true){fwrite($fp6,"$file is not in correct File name but in $value and copied to HDD\n");}
						fclose($fp6);
					}
				}
				else{continue;}			
			}
			else{continue;}
			}
			else{ //error_log.txt
				if(!is_file($HDD_destloc.$filenum.'/'."ERROR_FILE".'/'.$file)){
					
					mkdir($HDD_destloc, 0700);
					mkdir($HDD_destloc."ERROR_FILE", 0700);
				
					$filename6 = $HDD_destloc."ERROR_FILE".'/'.'error_log.txt';
					$fp6 = fopen($filename6, 'a+');
					fseek($fp6, 0);
				
					$c = copy($value , $HDD_destloc."ERROR_FILE".'/'.$file);
					if($c==true){fwrite($fp6,"$file is not in correct Folder but in $value and copied to HDD\n");}
					fclose($fp6);
				}
			}
		}	
?>
</pre>
