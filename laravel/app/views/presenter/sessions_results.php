<?php

$excel = new PHPExcel();

// Set properties
$excel->getProperties()->setCreator('')
	->setLastModifiedBy('')
	->setTitle(preg_replace('[^a-zA-Z0-9\'\.-_]', '_', $test->title .' - '. ($session->title ? $session->title : $test->sub_title)))
	->setSubject('')
	->setDescription('')
	;

// Set sheet and get
$excel->setActiveSheetIndex(0);
$sheet = $excel->getActiveSheet();
$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->setTitle('Results');

for ( $i = 0; $i < 40; $i++ )
{
	$sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
}

// Write the questions
$column = 0;
// Don't write questions in the first two lines if test is anonymous
if ( !$test->anon )
{
	$sheet->setCellValueByColumnAndRow(0, 1, '');
	$sheet->setCellValueByColumnAndRow(1, 1, 'Question title:')->getStyleByColumnAndRow(1, 1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$sheet->getStyleByColumnAndRow(1, 1)->getFont()->setBold(true);
	$column = 2;
}

$question_map = array();
$sheet->setCellValueByColumnAndRow(1, 2, 'Question type:')->getStyleByColumnAndRow(1, 2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$sheet->getStyleByColumnAndRow(1, 2)->getFont()->setBold(true);
$sheet->setCellValueByColumnAndRow(1, 3, 'Correct answers:')->getStyleByColumnAndRow(1, 3)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$sheet->getStyleByColumnAndRow(1, 3)->getFont()->setBold(true);
foreach ( $test->questions as $question )
{
	// Map question
	$question_map[$question->id] = $column;

	$sheet->setCellValueByColumnAndRow($column, 1, $question->title);
	$sheet->setCellValueByColumnAndRow($column, 2, $question->tqt_type);

	$valid = '';
	foreach ( $question->options as $option )
	{
		if ( $option->valid )
		{
			$valid .= $option->title . ', ';
		}
	}

	if ( $valid )
	{
		$valid = substr($valid, 0, -2);
	}

	$sheet->setCellValueByColumnAndRow($column, 3, $valid);

	$column++;
}

// Freeze first 2 columns and 3 rows
$sheet->freezePaneByColumnAndRow(2, 4);

// Display user answers
$row = 3;
$current_student = 0;
$columns = array();
foreach ( $student_answers as $answer )
{
	$user_identifier = $test->anon ? $answer->anon_user_id : $answer->user_id;
	if ( $current_student != $user_identifier )
	{
		// Write the previous user out before going to the next row
		if ( !empty($columns) )
		{
			foreach ( $columns as $column => $value )
			{
				$sheet->setCellValueByColumnAndRow($column, $row, $value);
			}
		}

		// Clean up and start new row
		$row++;
		$current_student = $user_identifier;

		$columns = array();
		if ( !$test->anon )
		{
			$sheet->setCellValueByColumnAndRow(0, $row, $answer->name);
			$sheet->setCellValueByColumnAndRow(1, $row, $answer->email);
		}
	}

	if ( isset($columns[$question_map[$answer->question_id]]) )
	{
		$columns[$question_map[$answer->question_id]] .= ', ' . $answer->answer;
	}
	else
	{
		$columns[$question_map[$answer->question_id]] = $answer->answer;
	}
}

// Write the last user out
if ( !empty($columns) )
{
	foreach ( $columns as $column => $value )
	{
		$sheet->setCellValueByColumnAndRow($column, $row, $value);
	}
}

// Send file
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' .preg_replace('[^a-zA-Z0-9\'\.-_]', '_', $test->title .'_'. ($session->title ? $session->title : $test->sub_title)) . '_'. date( 'm-d-Y', strtotime( $test->created_at ) ). '.xls"');
header('Cache-Control: max-age=0');  
$writer = new PHPExcel_Writer_Excel2007($excel);
$writer->save('php://output');

die();
