<?php
/**
 * @package		Clicker
 * @subpackage	com_tests
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

error_reporting( E_ALL & ~E_NOTICE & ~E_DEPRECATED );

/* * /
myPrint($this->test);
myPrint($this->questions);
myPrint($this->student_answers);
die();
/* */

require_once 'Spreadsheet/Excel/Writer.php';

// Create a workbook
$workbook = new Spreadsheet_Excel_Writer();

// Sending HTTP headers
$workbook->send(
	preg_replace( '[^a-zA-Z0-9\'\.-_]', '_', $this->test->title .'_'. $this->test->sub_title )
	. '_'. date( 'm-d-Y', strtotime( $this->test->administration_date ) ) .'.xls' );

// Create a worksheet
$worksheet =& $workbook->addWorksheet( 'User answers' );
$worksheet->setLandscape();

// Write the questions
// Don't write questions in the first two lines
$worksheet->write( 0, 0, '' );
$worksheet->write( 0, 1, '' );
$column = 2;
$question_map = array();
foreach ( $this->questions as $question ) {
	// Map question
	$question_map[$question->id] = $column;

	$worksheet->write( 0, $column, $question->title );
	$worksheet->write( 1, $column, $question->type );

	$valid = '';
	foreach ( $question->options as $option ) {
		if ( $option->valid ) {
			$valid .= $option->title . ', ';
		}
	}

	if ( $valid ) {
		$valid = substr( $valid, 0, -2 );
	}

	$worksheet->write( 2, $column, $valid );

	$column++;
}

// Freeze first 3 rows
$worksheet->freezePanes( array( 3, 0 ) );

// Display user answers
$row = 2;
$current_student = 0;
$columns = array();
foreach ( $this->student_answers as $answer ) {
	if ( $current_student != $answer->user_id ) {
		// Write the previous user out before going to the next row
		if ( !empty( $columns ) ) {
			foreach ( $columns as $column => $value ) {
				$worksheet->write( $row, $column, $value );
			}
		}

		// Clean up and start new row
		$row++;
		$current_student = $answer->user_id;

		$columns = array();
		$worksheet->write( $row, 0, $answer->name );
		$worksheet->write( $row, 1, $answer->email );
	}

	if ( isset( $columns[$question_map[$answer->question_id]] ) ) {
		$columns[$question_map[$answer->question_id]] .= ', ' . $answer->answer;
	} else {
		$columns[$question_map[$answer->question_id]] = $answer->answer;
	}
}

// Write the last user out
if ( !empty( $columns ) ) {
	foreach ( $columns as $column => $value ) {
		$worksheet->write( $row, $column, $value );
	}
}

// Let's send the file
$workbook->close();

die();


