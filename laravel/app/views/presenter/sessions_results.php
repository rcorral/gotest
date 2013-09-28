<?php
error_reporting( E_ALL & ~E_NOTICE & ~E_DEPRECATED );

require_once 'Spreadsheet/Excel/Writer.php';

// Create a workbook
$workbook = new Spreadsheet_Excel_Writer();

// Sending HTTP headers
$workbook->send(
	preg_replace( '[^a-zA-Z0-9\'\.-_]', '_', $test->title .'_'. $test->sub_title )
	. '_'. date( 'm-d-Y', strtotime( $test->created_at ) ) .'.xls'
);

// Create a worksheet
$worksheet =& $workbook->addWorksheet('User answers');
$worksheet->setLandscape();

// Write the questions
$column = 0;
// Don't write questions in the first two lines if test is anonymous
if ( !$test->anon )
{
	$worksheet->write(0, 0, '');
	$worksheet->write(0, 1, '');
	$column = 2;
}

$question_map = array();
foreach ( $test->questions as $question )
{
	// Map question
	$question_map[$question->id] = $column;

	$worksheet->write(0, $column, $question->title);
	$worksheet->write(1, $column, $question->type);

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

	$worksheet->write(2, $column, $valid);

	$column++;
}

// Freeze first 3 rows
$worksheet->freezePanes(array(3, 0));

// Display user answers
$row = 2;
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
				$worksheet->write($row, $column, $value);
			}
		}

		// Clean up and start new row
		$row++;
		$current_student = $user_identifier;

		$columns = array();
		if ( !$test->anon )
		{
			$worksheet->write($row, 0, $answer->name);
			$worksheet->write($row, 1, $answer->email);
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
		$worksheet->write($row, $column, $value);
	}
}

// Let's send the file
$workbook->close();

die();
