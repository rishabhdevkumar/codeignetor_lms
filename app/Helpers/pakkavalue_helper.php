<?php

use App\Models\EmpmasterModel;
use App\Models\AssignRoleModel; 
use App\Models\NotificationModel;

use App\Models\TrainingRequestModel;

function getrqstValue($training_id, $emp_code) 
{
    $status = null;
    $TrainingRequestModel = new TrainingRequestModel();

    $data = $TrainingRequestModel->where('training_id', $training_id)->where('emp_code', $emp_code)->first();
    if ($data) {
        $status = $data['status'];
    }

    return $status;
}


function getSessionMonthRange($session_year, $sessionStartMonth = 4, $totalMonths = 12) {
    $monthsRange = [];
    $currentDate = date('Y') . '-04-01';
    if (!empty($session_year)) {
        $currentDate = $session_year . '-04-01';
    }
    $currentYear = (int)date('Y', strtotime($currentDate));
    $currentMonth = (int)date('m', strtotime($currentDate));

    $sessionYear = $currentMonth >= $sessionStartMonth ? $currentYear : $currentYear - 1;
    $startDate = strtotime("-0 months", strtotime($currentDate));

    // Generate the range of months with session years
    for ($i = 0; $i < $totalMonths; $i++) {
        $timestamp = strtotime("+$i months", $startDate);
        $year = (int)date('Y', $timestamp);
        $month = date('F', $timestamp);

        if ((int)date('m', $timestamp) >= $sessionStartMonth) {
            $startSessionYear = $year;
            $endSessionYear = $year + 1;
        } else {
            $startSessionYear = $year - 1;
            $endSessionYear = $year;
        }

        $monthsRange[] = "$month-$startSessionYear-$endSessionYear";
    }
    return $monthsRange;
}



