<?php
	/* Note: This thumbnail creation script requires the GD PHP Extension.  
		If GD is not installed correctly PHP does not render this page correctly
		and SWFUpload will get "stuck" never calling uploadSuccess or uploadError
	 */

	// Get the session Id passed from SWFUpload. We have to do this to work-around the Flash Player Cookie Bug
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}

	session_start();
	ini_set("html_errors", "0");

	// Check the upload
	if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
		echo "ERROR:invalid upload";
		exit(0);
	}

	// Get the image and create a thumbnail
	$img = imagecreatefromjpeg($_FILES["Filedata"]["tmp_name"]);
	if (!$img) {
		echo "ERROR:could not create image handle ". $_FILES["Filedata"]["tmp_name"];
		exit(0);
	}

	$width = imageSX($img);
	$height = imageSY($img);

	if (!$width || !$height) {
		echo "ERROR:Invalid width or height";
		exit(0);
	}

	// Build the thumbnail
	
	$target_width = 150;
	$target_height = 100;
	$target_ratio = $target_width / $target_height;

	$img_ratio = $width / $height;

	if ($target_ratio > $img_ratio) {
		$new_height = $target_height;
		$new_width = $img_ratio * $target_height;
	} else {
		$new_height = $target_width / $img_ratio;
		$new_width = $target_width;
	}

	if ($new_height > $target_height) {
		$new_height = $target_height;
	}
	if ($new_width > $target_width) {
		$new_height = $target_width;
	}

	$new_img = ImageCreateTrueColor(100, 100);
	if (!@imagefilledrectangle($new_img, 0, 0, $target_width-1, $target_height-1, 0)) {	// Fill the image black
		echo "ERROR:Could not fill new image";
		exit(0);
	}

	if (!@imagecopyresampled($new_img, $img, ($target_width-$new_width)/2, ($target_height-$new_height)/2, 0, 0, $new_width, $new_height, $width, $height)) {
		echo "ERROR:Could not resize image";
		exit(0);
	}

	if (!isset($_SESSION["file_info"])) {
		$_SESSION["file_info"] = array();
	}

	// Use a output buffering to load the image into a variable
	ob_start();
	imagejpeg($new_img);
	$imagevariable = ob_get_contents();
	ob_end_clean();

	$file_id = md5($_FILES["Filedata"]["tmp_name"] + rand()*100000);
	
	$_SESSION["file_info"][$file_id] = $imagevariable;

	$myname_full = "/upload/temp/bg".$file_id.".jpg";				
	if (create_thumbnail($_FILES["Filedata"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'].$myname_full, $thumb_width=800, $thumb_height=800, $do_cut=false)) $_SESSION["file_need"][] = $file_id;
	$myname_full_sm = "/upload/temp/".$file_id.".jpg";				
	create_thumbnail($_FILES["Filedata"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'].$myname_full_sm, $thumb_width=150, $thumb_height=150, $do_cut=true);

	echo "FILEID:" . $file_id;	// Return the file id to the script
	
# Функция по созданию "тумбочек"
function create_thumbnail($orig_fname, $thum_fname, $thumb_width=100, $thumb_height=100, $do_cut=false)
{
    $rgb = 0xFFFFFF;
    $quality = 80;
    $size = @getimagesize($orig_fname);
    $src_x = $src_y = 0;

    if( $size === false) return false;

    $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
    $icfunc = "imagecreatefrom" . $format;
    if (!function_exists($icfunc)) return false;

    $orig_img = $icfunc($orig_fname);
    if (($size[0] <= $thumb_width) && ($size[1] <= $thumb_height))
    {
        // use original size
        $width  = $size['0'];
        $height = $size['1'];
    }
    else
    {
        $width  = $thumb_width;
        $height = $thumb_height;

        // calculate fit ratio
        $ratio_width  = $size['0'] / $thumb_width;
        $ratio_height = $size['1'] / $thumb_height;

        if ($ratio_width < $ratio_height)
        {
            if ($do_cut)
            {
                $src_y = ($size['1'] - $thumb_height * $ratio_width) / 2;
                $size['1'] = $thumb_height * $ratio_width;
            }
            else
            {
                $width  = $size['0'] / $ratio_height;
                $height = $thumb_height;
            }
        } else {
            if ($do_cut)
            {
                $src_x = ($size['0'] - $thumb_width * $ratio_height) / 2;
                $size['0'] = $thumb_width * $ratio_height;
            }
            else
            {
                $width  = $thumb_width;
                $height = $size['1'] / $ratio_width;
            }
        }
    }

    $thum_img = imagecreatetruecolor($width, $height);
    imagefill($thum_img, 0, 0, $rgb);
    imagecopyresampled($thum_img, $orig_img, 0, 0, $src_x, $src_y, $width, $height, $size[0], $size[1]);

    imagejpeg($thum_img, $thum_fname, $quality);
    flush();
    imagedestroy($orig_img);
    imagedestroy($thum_img);
    return true;
}	
?>