<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Generate_excel {

function __construct()
{	
	date_default_timezone_set("Asia/Kolkata");	
}

function write($headers,$data,$title_name,$type)
{
 	$ci=& get_instance();
		$ci->load->library("excel"); 
 
	$objPHPExcel = new PHPExcel(); // Create new PHPExcel object	
	// create style
	$default_border = array(
	   	// 'style' => PHPExcel_Style_Border::BORDER_THIN,
	    'color' => array('rgb'=>'000000'),
	);
	$style_header = array(
		'borders' => array('outline' =>
                        array('style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('argb' => '000000'),   ),),
		'fill' => array(
     	    'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb'=>'fff2cc'),
		),
		'font' => array(
			//'color' => array('rgb'=>'151B8D'),
			//'bold' => true,
			'size' => 12,
		)
	);	
	$style_header2 = array(
		'borders' => array('outline' =>
                        array('style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('argb' => '000000'),   ),),
		'fill' => array(
     	    'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb'=>'deebf7'),
		),
		'font' => array(
			//'color' => array('rgb'=>'151B8D'),
			//'bold' => true,
			'size' => 12,
		)
	);
	$style_header3 = array(
		'borders' => array('outline' =>
                        array('style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('argb' => '000000'),   ),),
		'fill' => array(
     	    'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb'=>'ffff00'),
		),
		'font' => array(
			//'color' => array('rgb'=>'151B8D'),
			//'bold' => true,
			'size' => 12,
		)
	);
	$style_content = array(
		'borders' => array(
			'bottom' => $default_border,
			'left' => $default_border,
			'top' => $default_border,
			'right' => $default_border,
		),
		'fill' => array(
     	//   'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb'=>'eeeeee'),
		),
		'font' => array(
 		//'color' => array('rgb'=>'151B8D'),
     	//   'bold' => true,
			'size' => 10,
		)
	);
	$style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        )
    );    

	$alphabets = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	$last_alphabet = '';


	if($type == "course orders")
	{
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'S.NO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray( $style_header );		
		$i = 1;		
		foreach($headers as $vh)
		{
			// Create Header
			$i++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[$i -1].'1', $vh);
			$objPHPExcel->getActiveSheet()->getColumnDimension($alphabets[$i -1])->setWidth(25);
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
			$objPHPExcel->getDefaultStyle()->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle($alphabets[$i -1].'1')->applyFromArray( $style_header );
			//$last_alphabet = $alphabets[$index];
			
		}
		$excel_row = 2;
		$i = 1;
		foreach($headers as $vh)
		{
			// Create Header
			$i++;	
			$j=1;		
			foreach($data as $tp)
			{
				$j++;
				$sno = $j-1;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[0].$j, $j - 1);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[1].$j, $tp['name']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[2].$j, $tp['mobile']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[3].$j, $tp['email_id']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[4].$j, $tp['course_title']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[5].$j, $tp['batch_name']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[6].$j, $tp['batch_date']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[7].$j, $tp['duration_range']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[8].$j, $tp['duration']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[9].$j, $tp['timing']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[10].$j, $tp['amount']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[11].$j, $tp['created_on']);
				//$center_index = $center_index + 1;
			    //$x++;
			}
		}
		//Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Course Orders');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

	}

	if($type == "rotp orders")
	{
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'S.NO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray( $style_header );		
		$i = 1;		
		foreach($headers as $vh)
		{
			// Create Header
			$i++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[$i -1].'1', $vh);
			$objPHPExcel->getActiveSheet()->getColumnDimension($alphabets[$i -1])->setWidth(25);
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
			$objPHPExcel->getDefaultStyle()->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle($alphabets[$i -1].'1')->applyFromArray( $style_header );
			//$last_alphabet = $alphabets[$index];
			
		}
		$excel_row = 2;
		$i = 1;
		foreach($headers as $vh)
		{
			// Create Header
			$i++;	
			$j=1;		
			foreach($data as $tp)
			{
				$j++;
				$sno = $j-1;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[0].$j, $j - 1);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[1].$j, $tp['name']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[2].$j, $tp['mobile']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[3].$j, $tp['email_id']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[4].$j, $tp['course_title']);				
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[5].$j, $tp['amount_paid']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[6].$j, $tp['created_on']);
				//$center_index = $center_index + 1;
			    //$x++;
			}
		}
		//Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('ROTP Orders');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

	}

	if($type == "Paid in Installments")
	{
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'S.NO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray( $style_header );		
		$i = 1;		
		foreach($headers as $vh)
		{
			// Create Header
			$i++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[$i -1].'1', $vh);
			$objPHPExcel->getActiveSheet()->getColumnDimension($alphabets[$i -1])->setWidth(25);
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
			$objPHPExcel->getDefaultStyle()->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle($alphabets[$i -1].'1')->applyFromArray( $style_header );
			//$last_alphabet = $alphabets[$index];
			
		}
		$excel_row = 2;
		$i = 1;
		foreach($headers as $vh)
		{
			// Create Header
			$i++;	
			$j=1;		
			foreach($data as $tp)
			{
				$j++;
				$sno = $j-1;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[0].$j, $j - 1);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[1].$j, $tp['name']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[2].$j, $tp['mobile']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[3].$j, $tp['email_id']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[4].$j, $tp['course_title']);				
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[5].$j, $tp['actual_price']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[6].$j, $tp['discount_price']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[7].$j, $tp['installment']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[8].$j, $tp['per_installment']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[9].$j, $tp['payment_status']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabets[10].$j, $tp['created_on']);
				//$center_index = $center_index + 1;
			    //$x++;
			}
		}
		//Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Paid in Installments');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

	}


	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$title_name.'.xls"'); // file name of excel
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0	 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	//ob_end_clean();
	$objWriter->save('php://output');
 }
	
}