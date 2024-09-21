<?php

use App\Models\Admin;
use App\Models\Blog_model;
use App\Models\Listing_model;
use App\Models\Listing_prices_model;
use App\Models\Member_model;
use App\Models\Sitecontent;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Json;
use Illuminate\Support\Str;


// use Image;
use Intervention\Image\Facades\Image;

function geUsertLocation($ipAddress)
{
    if ($ipAddress === '127.0.0.1' || $ipAddress === '::1') {
        $ipAddress = '139.130.4.5';
    }
    $client = new Client();

    // Replace 'your_api_token' with your actual IPInfo API token
    $response = $client->get("http://ipinfo.io/{$ipAddress}/json?token=08fca6a8aebb2c");


    if ($response->getStatusCode() == 200) {
        $data = json_decode($response->getBody(), true);
        if (isset($data['loc'])) {
            list($latitude, $longitude) = explode(',', $data['loc']);
            return [
                'latitude' => $latitude,
                'longitude' => $longitude,
            ];
        }
    }

    return ['error' => 1];
}
function generateThumbnail_with_thumbs_folder($folderName, $imageName, $image_type, $folder_type)
{
    switch ($image_type) {
        case 'square':
            switch ($folder_type) {
                case 'small':
                    $thumbnailWidth = 150;
                    $thumbnailHeight = 150;
                    break;
                case 'medium':
                    $thumbnailWidth = 500;
                    $thumbnailHeight = 500;
                    break;
                case 'large':
                    $thumbnailWidth = 1000;
                    $thumbnailHeight = 1000;
                    break;
                default:
                    $thumbnailWidth = 150;
                    $thumbnailHeight = 150;
                    break;
            }
            break;
        case 'rectangular':
            switch ($folder_type) {
                case 'small':
                    $thumbnailWidth = 300;
                    $thumbnailHeight = 200;
                    break;
                case 'medium':
                    $thumbnailWidth = 600;
                    $thumbnailHeight = 400;
                    break;
                case 'large':
                    $thumbnailWidth = 1200;
                    $thumbnailHeight = 800;
                    break;
                default:
                    $thumbnailWidth = 300;
                    $thumbnailHeight = 200;
                    break;
            }
            break;
        case 'vertical':
            switch ($folder_type) {
                case 'small':
                    $thumbnailWidth = 200;
                    $thumbnailHeight = 300;
                    break;
                case 'medium':
                    $thumbnailWidth = 400;
                    $thumbnailHeight = 600;
                    break;
                case 'large':
                    $thumbnailWidth = 800;
                    $thumbnailHeight = 1200;
                    break;
                default:
                    $thumbnailWidth = 200;
                    $thumbnailHeight = 300;
                    break;
            }
            break;
        case 'avatar':
            switch ($folder_type) {
                case 'small':
                    $thumbnailWidth = 100;
                    $thumbnailHeight = 100;
                    break;
                case 'medium':
                    $thumbnailWidth = 200;
                    $thumbnailHeight = 200;
                    break;
                case 'large':
                    $thumbnailWidth = 300;
                    $thumbnailHeight = 300;
                    break;
                default:
                    $thumbnailWidth = 100;
                    $thumbnailHeight = 100;
                    break;
            }
            break;
        default:
            $thumbnailWidth = 150;
            $thumbnailHeight = 150;
            break;
    }

    $imagePath = storage_path("app/public/{$folderName}/{$imageName}");

    if (!file_exists($imagePath)) {
        return false;
    }

    $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

    if ($extension === 'svg') {
        $thumbnailFolder = storage_path("app/public/{$folderName}/{$folder_type}");
        if (!is_dir($thumbnailFolder)) {
            mkdir($thumbnailFolder, 0755, true);
        }

        $thumbnailPath = "{$thumbnailFolder}/{$imageName}";
        if (copy($imagePath, $thumbnailPath)) {
            return basename($thumbnailPath);
        } else {
            return false;
        }
    }

    list($width, $height) = getimagesize($imagePath);

    $scale = min($thumbnailWidth / $width, $thumbnailHeight / $height);

    $newWidth = floor($width * $scale);
    $newHeight = floor($height * $scale);

    $thumbnail = imagecreatetruecolor($newWidth, $newHeight);

    $sourceImage = null;
    switch ($extension) {
        case 'jpeg':
        case 'jpg':
            $sourceImage = imagecreatefromjpeg($imagePath);
            break;
        case 'png':
            $sourceImage = imagecreatefrompng($imagePath);
            break;
        case 'gif':
            $sourceImage = imagecreatefromgif($imagePath);
            break;
        case 'bmp':
            $sourceImage = imagecreatefrombmp($imagePath);
            break;
        default:
            return false;
    }

    imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    $thumbnailFolder = storage_path("app/public/{$folderName}/{$folder_type}");
    if (!is_dir($thumbnailFolder)) {
        mkdir($thumbnailFolder, 0755, true);
    }

    $thumbnailPath = "{$thumbnailFolder}/{$imageName}";
    $result = false;
    switch ($extension) {
        case 'jpeg':
        case 'jpg':
            $result = imagejpeg($thumbnail, $thumbnailPath);
            break;
        case 'png':
            $result = imagepng($thumbnail, $thumbnailPath);
            break;
        case 'gif':
            $result = imagegif($thumbnail, $thumbnailPath);
            break;
        case 'bmp':
            $result = imagebmp($thumbnail, $thumbnailPath);
            break;
    }

    // Free up memory
    imagedestroy($thumbnail);
    imagedestroy($sourceImage);

    // Return the thumbnail name on success, false on failure
    return $result ? basename($thumbnailPath) : false;
}
function nextOrder($table, $where = [])
{
    $maxOrderNo = DB::table($table)
        ->when(!empty($where), function ($query) use ($where) {
            return $query->where($where);
        })
        ->max('order_no');

    return intval($maxOrderNo) + 1;
}
function formatAmount($amount)
{
    $roundedAmount = round($amount * 10) / 10;
    if (floor($roundedAmount) == $roundedAmount) {
        return (string) intval($roundedAmount);
    } else {
        return number_format($roundedAmount, 1, '.', '');
    }
}
function formatNumber($number)
{
    // Check if the number has a decimal part of .00
    if (strpos($number, '.') !== false && substr($number, strpos($number, '.') + 1) === '00') {
        // If true, return only the whole number part
        return (int)$number;
    } else {
        // Otherwise, return the number as it is
        return $number;
    }
}

function generateThumbnail($folderName, $imageName, $image_type, $folder_type)
{
    $imagePath = storage_path("app/public/{$folderName}/{$imageName}");

    if (!file_exists($imagePath)) {
        return false;
    }

    // Get the file extension
    $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

    // Determine thumbnail dimensions based on image type and folder type
    switch ($image_type) {
        case 'square':
            switch ($folder_type) {
                case 'small':
                    $thumbnailWidth = 150;
                    $thumbnailHeight = 150;
                    break;
                case 'medium':
                    $thumbnailWidth = 500;
                    $thumbnailHeight = 500;
                    break;
                case 'large':
                    $thumbnailWidth = 1000;
                    $thumbnailHeight = 1000;
                    break;
                default:
                    $thumbnailWidth = 150;
                    $thumbnailHeight = 150;
                    break;
            }
            break;
        case 'rectangular':
            switch ($folder_type) {
                case 'small':
                    $thumbnailWidth = 300;
                    $thumbnailHeight = 200;
                    break;
                case 'medium':
                    $thumbnailWidth = 600;
                    $thumbnailHeight = 400;
                    break;
                case 'large':
                    $thumbnailWidth = 1200;
                    $thumbnailHeight = 800;
                    break;
                default:
                    $thumbnailWidth = 300;
                    $thumbnailHeight = 200;
                    break;
            }
            break;
        case 'vertical':
            switch ($folder_type) {
                case 'small':
                    $thumbnailWidth = 200;
                    $thumbnailHeight = 300;
                    break;
                case 'medium':
                    $thumbnailWidth = 400;
                    $thumbnailHeight = 600;
                    break;
                case 'large':
                    $thumbnailWidth = 800;
                    $thumbnailHeight = 1200;
                    break;
                default:
                    $thumbnailWidth = 200;
                    $thumbnailHeight = 300;
                    break;
            }
            break;
        case 'avatar':
            switch ($folder_type) {
                case 'small':
                    $thumbnailWidth = 100;
                    $thumbnailHeight = 100;
                    break;
                case 'medium':
                    $thumbnailWidth = 200;
                    $thumbnailHeight = 200;
                    break;
                case 'large':
                    $thumbnailWidth = 300;
                    $thumbnailHeight = 300;
                    break;
                default:
                    $thumbnailWidth = 100;
                    $thumbnailHeight = 100;
                    break;
            }
            break;
        default:
            $thumbnailWidth = 150;
            $thumbnailHeight = 150;
            break;
    }

    // Load the original image
    $sourceImage = null;
    switch ($extension) {
        case 'jpeg':
        case 'jpg':
            $sourceImage = imagecreatefromjpeg($imagePath);
            break;
        case 'png':
            $sourceImage = imagecreatefrompng($imagePath);
            break;
        case 'gif':
            $sourceImage = imagecreatefromgif($imagePath);
            break;
        case 'bmp':
            $sourceImage = imagecreatefrombmp($imagePath);
            break;
        default:
            return false;
    }

    // Get the original image dimensions
    $width = imagesx($sourceImage);
    $height = imagesy($sourceImage);

    // Calculate the scaling factor
    $scale = min($thumbnailWidth / $width, $thumbnailHeight / $height);

    // Calculate the new dimensions
    $newWidth = floor($width * $scale);
    $newHeight = floor($height * $scale);

    // Create a new blank image with the new dimensions
    $thumbnail = imagecreatetruecolor($newWidth, $newHeight);

    // Copy and resize the original image to the thumbnail
    imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Determine the thumbnail path
    $thumbnailPath = storage_path("app/public/{$folderName}/{$imageName}");

    // Save the thumbnail to the same folder
    $result = false;
    switch ($extension) {
        case 'jpeg':
        case 'jpg':
            $result = imagejpeg($thumbnail, $thumbnailPath);
            break;
        case 'png':
            $result = imagepng($thumbnail, $thumbnailPath);
            break;
        case 'gif':
            $result = imagegif($thumbnail, $thumbnailPath);
            break;
        case 'bmp':
            $result = imagebmp($thumbnail, $thumbnailPath);
            break;
    }

    // Free up memory
    imagedestroy($thumbnail);
    imagedestroy($sourceImage);

    // Delete the original image after thumbnail creation
    // unlink($imagePath);

    // Return the thumbnail name on success, false on failure
    return $result ? basename($thumbnailPath) : false;
}


function breadcrumb($currentPage, $url = '')
{
    if (!empty($url)) {
        $link = '
            <div class="">
                <a href="' . $url . '" class="btn btn-primary">Add New</a>
            </div>
            ';
    } else {
        $link = '
            <ol class="breadcrumb">
                                    <li class="breadcrumb-item d-flex align-items-center">
                                        <a class="text-muted text-decoration-none d-flex" href="' . url("admin/dashboard") . '">
                                            <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">' . $currentPage . '</span>
                                    </li>
                                </ol>
            ';
    }
    return '
            <div class="card card-body py-3">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="d-sm-flex align-items-center justify-space-between">
                            <h4 class="mb-4 mb-md-0 card-title">' . $currentPage . '</h4>
                            <nav aria-label="breadcrumb" class="ms-auto">
                               ' . $link . ' 
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        ';
}
function showMessage()
{
    $output = '';

    if (session('status')) {
        $output .= '<div class="alert bg-danger-subtle text-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center text-danger">
                                <i class="ti ti-info-circle me-2 fs-4"></i>
                                ' . session('status') . '
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
    }

    if (session('success')) {
        $output .= '<div class="alert bg-success-subtle text-success alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center text-success">
                                <i class="ti ti-info-circle me-2 fs-4"></i>
                                ' . session('success') . '
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
    }

    if (session('error')) {
        $output .= '<div class="alert bg-danger-subtle text-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center text-danger">
                                <i class="ti ti-info-circle me-2 fs-4"></i>
                                ' . session('error') . '
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
    }

    if (!empty($errors) && count($errors) > 0) {
        $output .= '<div class="alert bg-danger-subtle text-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center text-danger">
                                <i class="ti ti-info-circle me-2 fs-4"></i>
                                <ul>';
        foreach ($errors->all() as $error) {
            $output .= '<li>' . $error . '</li>';
        }
        $output .= '</ul>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
    }

    return $output;
}
function generatePromoCode($length = 6)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $promoCode = '';

    for ($i = 0; $i < $length; $i++) {
        $promoCode .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $promoCode;
}
function isTargetDateAfterCurrentMonth($targetDateStr)
{
    // Get the current date
    $currentDate = new DateTime();

    // Convert the target date string to a DateTime object
    $targetDate = new DateTime($targetDateStr);

    // Check if the target date is in the current month or any month after the current month
    return ($targetDate->format('Y-m') > $currentDate->format('Y-m'));
}
function isDateOlderOrGreater($targetDate)
{
    // Create DateTime objects for the target date and current date
    $targetDateTime = new DateTime($targetDate);
    $currentDateTime = new DateTime();

    // Add 5 days to the target date
    $targetDateTime->modify('+5 days');

    // Compare the modified target date with the current date
    if ($targetDateTime < $currentDateTime) {
        return true;
    } else {
        return false;
    }
}
function isEditable($leaseStartDate)
{
    $startDateObj = new DateTime($leaseStartDate);
    $currentDate = new DateTime();
    $dateDifference = $startDateObj->diff($currentDate)->days;
    return $dateDifference >= 1;
}
function isEditableInDate($leaseStartDate)
{
    $startDateTimestamp = strtotime($leaseStartDate);
    $currentDateTimestamp = time();

    $startDate = date('Y-m-d', $startDateTimestamp);
    $currentDate = date('Y-m-d', $currentDateTimestamp);

    $dateDifference = floor((strtotime($startDate) - strtotime($currentDate)) / (60 * 60 * 24));

    return $dateDifference >= 1;
}
function hasFiveDaysPassedInCurrentMonth()
{
    // Create DateTime objects for the current date and a reference date 5 days ago
    $currentDateTime = new DateTime();
    $fiveDaysAgo = (new DateTime())->modify('-5 days');

    // Compare the reference date with the current date
    return $fiveDaysAgo < $currentDateTime;
}

function compareProperties($a, $b, $criteria, $order)
{
    foreach ($criteria as $criterion) {
        $comparison = $a->$criterion - $b->$criterion;
        if ($comparison !== 0) {
            return ($order === 'asc') ? $comparison : -$comparison;
        }
    }
    return 0;
}
function isTwoDaysAfter($availableDate, $requestedDate)
{
    // Create DateTime objects from the provided dates
    $availableDateTime = new DateTime($availableDate);
    $requestedDateTime = new DateTime($requestedDate);

    // Calculate the difference between the two dates
    $interval = $availableDateTime->diff($requestedDateTime);

    // Check if the difference is exactly 2 days and there are no other time differences
    return $interval->days;
}
function fiveDaysPassed()
{
    $currentDate = new DateTime();

    // Subtract 5 days
    $currentDate->sub(new DateInterval('P5D'));

    // Current date 5 days ago
    $currentDate5DaysAgo = new DateTime();

    if ($currentDate5DaysAgo > $currentDate) {
        return 1;
    } else {
        return 0;
    }
}
function addDaysToDate($inputDate, $daysToAdd)
{
    // Create a DateTime object from the input date string
    $date = new DateTime($inputDate);

    // Add the specified number of days
    $date->modify('+' . $daysToAdd . ' days');

    // Format the result in the desired format (Y-m-d)
    $resultDate = $date->format('Y-m-d');

    return $resultDate;
}
function addFiveDays($start_date, $site_lease_grace_period)
{
    $initialDate = new DateTime($start_date);

    for ($i = 1; $i < 5; $i++) {
        $initialDate->modify('+1 day');
    }

    return $initialDate->format('Y-m-d');
}
function leaseStartDatePassedGrosPeriod($start_date)
{
    $leaseStartDateTime = new DateTime($start_date);
    $currentDateTime = new DateTime();

    // Calculate the date 5 days from the lease start date
    $paymentDeadline = clone $leaseStartDateTime;
    $paymentDeadline->modify('+5 days');
    if ($currentDateTime <= $paymentDeadline) {
        return 1;
    } else {
        return 0;
    }
}
function checkLeaseCurentMonthType($date)
{
    $currentDate = new DateTime(); // Get the current date
    $givenDate = new DateTime($date); // Your given date

    // Check if the current date is in the same month as the given date
    if ($currentDate->format('Y-m') == $givenDate->format('Y-m')) {
        // If it's the same month, return last month
        return 'last';
    } elseif ($currentDate > $givenDate) {
        return null;
    } else {
        return 'middle';
    }
}
function calculateNextYearCurrentDate($year = 1)
{
    $currentDate = new DateTime();

    // Add one year to the current date
    $currentDate->modify('+' . $year . ' year');

    // Format the result as a string
    $endDate = $currentDate->format('Y-m-d');
    return $endDate;
}
function checkDateInLastthirtyDays($date)
{
    $currentDate = new DateTime(); // Current date and time

    $dateToCheck = new DateTime($date); // Replace with the date you want to check

    $interval = $currentDate->diff($dateToCheck);
    $daysDifference = $interval->days;

    if ($daysDifference <= 30) {
        return true;
    } else {
        return false;
    }
}
function getStartDateofGivenMonth($dateString)
{
    $date = new DateTime($dateString);

    // Set the date to the first day of the month
    $date->modify('first day of this month');

    return new DateTime($date->format('Y-m-d'));
}
function getStartDate($dateString)
{
    $date = new DateTime($dateString);

    // Set the date to the first day of the month
    $date->modify('first day of this month');

    return $date->format('Y-m-d');
}
function getTotalDays($startDate, $last_day_of_first_month)
{
    $interval = $startDate->diff($last_day_of_first_month);

    return $interval->days + 1;
}
function getDatesBtweenTwoDates($start_date, $end_date)
{
    $arr = array();
    $startDate = new DateTime($start_date);
    $endDate = new DateTime($end_date);
    $currentDate = clone $startDate;

    while ($currentDate <= $endDate) {
        $arr[] = $currentDate->format('Y-m-d');
        $currentDate->modify('+1 day');
    }

    return $arr;
}
function isDateBookedAndFindClosest($bookedDates, $checkDate)
{
    $checkDate = (new DateTime($checkDate))->format('Y-m-d');

    if (in_array($checkDate, $bookedDates)) {
        return ['isBooked' => true, 'bookingAfterOneday' => false];
    }

    $closestFutureDate = null;
    foreach ($bookedDates as $bookedDate) {
        $bookedDateObj = new DateTime($bookedDate);
        $checkDateObj = new DateTime($checkDate);
        if ($bookedDateObj > $checkDateObj) {
            if (is_null($closestFutureDate) || $bookedDateObj < new DateTime($closestFutureDate)) {
                $closestFutureDate = $bookedDate;
            }
        }
    }

    if ($closestFutureDate) {
        $checkDateObj = new DateTime($checkDate);
        $closestFutureDateObj = new DateTime($closestFutureDate);
        $interval = $checkDateObj->diff($closestFutureDateObj);
        $daysBetween = $interval->days;
        if ($daysBetween <= 1) {
            return ['isBooked' => false, 'bookingAfterOneday' => true];
        }
    }

    return ['isBooked' => false, 'bookingAfterOneday' => false];
}
function getLastMonthDays($end_date)
{
    $endDate = new DateTime($end_date);
    $total_days_in_month = (int)$endDate->format('t');
    $spentDays = $endDate->format('d');
    return $spentDays / $total_days_in_month;
}
function getLastDayDateOfGivenDate($dateString)
{
    $date = new DateTime($dateString);
    $date->modify('last day of this month');
    return $date->format('m/d/Y');
}
function getFirstDayDateOfGivenDate($dateString)
{
    $date = new DateTime($dateString);
    $date->modify('first day of this month');
    return $date->format('m/d/Y');
}
function getMonthsBetweenTwoDates($start_date, $end_date)
{
    $startDate = new DateTime($start_date);
    $endDate = new DateTime($end_date);

    $interval = $startDate->diff($endDate);

    $months = $interval->y * 12 + $interval->m;
    return $months;
}
function getFirstMonthDaysCount($givenDate)
{
    try {
        // Create a DateTime object with the given date
        $givenDateTime = new DateTime($givenDate);
    } catch (Exception $e) {
        return "Invalid date format";
    }

    // Get the current DateTime object
    $currentDateTime = new DateTime();

    // Calculate the difference between the two DateTime objects
    $interval = $currentDateTime->diff($givenDateTime);

    // Get the total number of days in the given month
    $totalDays = $givenDateTime->format('t');

    // Get the number of days passed
    $days_Passed = $givenDateTime->format('j');
    return array(
        'total' => $totalDays,
        'remaining_days' => intval($totalDays) - intval(intval($days_Passed) - 1)
    );
}
function getLeaseMonths($start_date, $end_date)
{
    $arr = array();
    $arr['firstMonthDays'] = 0;
    $arr['lastMonthDays'] = 0;
    $arr['middle_months'] = 0;
    $startDate = new DateTime($start_date);
    $endDate = new DateTime($end_date);

    $interval = $startDate->diff($endDate);

    $months = $interval->y * 12 + $interval->m;
    $days = $interval->format('%d');

    if ($days > 0) {
        $months++;
    }
    $arr['total_months'] = $months;
    if ($months > 1) {
        $last_day_of_first_month = new DateTime(date('Y-m-t'));
        $startMonthDaysLeft = getTotalDays($startDate, $last_day_of_first_month);

        $firstDateofLastMonth = getStartDateofGivenMonth($end_date);
        $LastMonthDaysPassed = getTotalDays($firstDateofLastMonth, $endDate);

        $daysPassedInStartDate = (int)$startDate->format('d') - 1;


        $arr['total_days_in_first_month'] = (int)$startDate->format('t');
        $arr['start_date_passed_days'] = $daysPassedInStartDate;
        $arr['firstMonthDays'] = $startMonthDaysLeft;
        $arr['lastMonthDays'] = $LastMonthDaysPassed;
        $arr['middle_months'] = $months - 2;
    } else if ($months == 1) {
        $daysPassedInStartDate = (int)$startDate->format('d') - 1;
        $arr['start_date_passed_days'] = $daysPassedInStartDate;
        $startMonthDaysLeft = getTotalDays($startDate, $endDate);
        $arr['firstMonthDays'] = $startMonthDaysLeft;
    }



    return $arr;
}
function getOnlyMonthsFromDate($dateString1, $dateString2)
{
    // $date1 = new DateTime($dateString1);
    // $date2 = new DateTime($dateString2);

    // $interval = $date1->diff($date2);
    // $months = ($interval->y * 12) + $interval->m;
    $start_date = new DateTime($dateString2);
    $end_date = new DateTime($dateString1);

    // Initialize a variable to store the total number of months
    $total_months = 0;

    // Create a copy of the start date to iterate through months
    $current_date = clone $start_date;

    // Iterate through the months between the start and end dates
    while ($current_date <= $end_date) {
        $total_months++;

        // Move to the next month
        $current_date->add(new DateInterval('P1M'));
    }
    return $total_months;
}
function getOnlyDaysFromDate($dateString1, $dateString2)
{
    $date1 = new DateTime($dateString1);
    $date2 = new DateTime($dateString2);

    $interval = $date1->diff($date2);
    $days = $interval->days;
    return $days;
}
function getMonthsDaysFromDate($dateString1, $dateString2)
{
    $date1 = new DateTime($dateString1);
    $date2 = new DateTime($dateString2);

    $interval = $date1->diff($date2);

    $days = $interval->format('%a');
    $months = $interval->format('%m');
    $years = $interval->format('%y');
    $duration = [];

    if ($years > 0) {
        $duration[] = "$years year" . ($years > 1 ? 's' : '');
    }
    if ($months > 0) {
        $duration[] = "$months month" . ($months > 1 ? 's' : '');
    }
    if ($days > 0) {
        $duration[] = "$days day" . ($days > 1 ? 's' : '');
    }

    $result = implode(', ', $duration);
    return $result;
}
function findAndFilterObjects($objectsArray, $valueToFind)
{
    return array_filter($objectsArray, function ($object) use ($valueToFind) {
        return $object->mgt_type == $valueToFind;
    });
}
function isJson($value)
{
    try {
        Json::decode($value);
        return true;
    } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
        return false;
    }
}
function resize_crop_image($path, $image, $type = 'thumb_', $width = 500, $height = 500)
{
    // try {
    ini_set('memory_limit', '1200M');
    if (!empty($image) && @file_exists("." . Storage::url($path . '/' . $image))) {
        // pr($image);
        $imagePath = public_path('storage/' . $path . '/' . $image);
        $thumbnailpath = public_path('storage/' . $path . '/thumbs/' . $type . $image);
        $img = Image::make($imagePath)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->save($thumbnailpath)->destroy();
        return $img;
    }

    // } catch (\Exception $e) {

    //     $msg=$e->getMessage();
    //     return false;
    // }


}

function get_branch_baths($branch_full_bathrooms)
{
    $min = null;
    $max = null;
    if (!empty($branch_full_bathrooms)) {
        $min = $branch_full_bathrooms[0]->all_bathrooms;
        $max = $branch_full_bathrooms[0]->all_bathrooms;
        foreach ($branch_full_bathrooms as $key => $f_bath) {


            if ($f_bath->all_bathrooms < $min) {
                $min = $f_bath->all_bathrooms;
            }
            if ($f_bath->all_bathrooms > $max) {
                $max = $f_bath->all_bathrooms;
            }
        }
    }
    return (array("min" => $min, 'max' => $max));
}
function write_image($url, $path)
{
    $contents = file_get_contents($url);
    // pr($contents);
    $file_name = md5(rand(100, 1000)) . '_' . time() . '_' . rand(1111, 9999) . '.jpg';

    Storage::put($path . $file_name, $contents);
    return $file_name;
}
function format_address($address)
{
    if (!empty($address)) {
        $address_arr = explode(",", $address);

        if (count($address_arr) == 5) {
            return nl2br($address_arr[0] . ", " . $address_arr[1] . "\n" . $address_arr[2] . ", " . $address_arr[3] . ", " . $address_arr[4]);
        } else if (count($address_arr) == 4) {
            return nl2br($address_arr[0] . "\n" . $address_arr[1] . ", " . $address_arr[2] . ", " . $address_arr[3]);
        } else if (count($address_arr) == 3) {
            return nl2br($address_arr[0] . "\n" . $address_arr[1] . ", " . $address_arr[2]);
        } else if (count($address_arr) == 2) {
            if (str_contains($address_arr[1], 'USA')) {
                return nl2br($address_arr[0]);
            } else {
                return nl2br($address_arr[0] . "\n" . $address_arr[1]);
            }
        } else {
            return $address;
        }
    } else {
        return '';
    }
    // return $address;

}
function format_address_single($address)
{
    // if(!empty($address)){
    //     $address_arr=explode( ",", $address);
    //     // $address_arr=array_reverse($address_arr);
    //      if(count($address_arr) == 5){
    //         return nl2br($address_arr[0].", ".$address_arr[1].",".$address_arr[2].", ".$address_arr[3]);
    //     }
    //     else if(count($address_arr) == 4){
    //         return $address_arr[0].", ".$address_arr[1].", ".$address_arr[2];
    //     }
    //     else if(count($address_arr) == 3){
    //         return $address_arr[0].", ".$address_arr[1];
    //     }
    //     else if(count($address_arr) == 2){
    //         return nl2br($address_arr[0]);
    //     }
    //     else{
    //         return $address;
    //     }

    // }
    // else{
    //     return '';
    // }
    return $address;
}
function format_address_one_line($address)
{
    if (!empty($address)) {
        $address_arr = explode(",", $address);
        // $address_arr=array_reverse($address_arr);
        if (count($address_arr) == 5) {
            return nl2br($address_arr[0] . ", " . $address_arr[1] . "," . $address_arr[2] . ", " . $address_arr[3]);
        } else if (count($address_arr) == 4) {
            return $address_arr[0] . ", " . $address_arr[1] . ", " . $address_arr[2];
        } else if (count($address_arr) == 3) {
            return $address_arr[0] . ", " . $address_arr[1];
        } else if (count($address_arr) == 2) {
            return nl2br($address_arr[0]);
        } else {
            return $address;
        }
    } else {
        return '';
    }
}

function isCheckedFeature($features, $type = 'den')
{

    if (!empty($features) && count($features) > 0) {
        foreach ($features as $feature) {

            $feature_id = intval(json_decode($feature));
            // pr(get_amentiy_name($feature_id));
            if (strtolower(get_amentiy_name($feature_id)) == $type) {
                return true;
            }
        }
    } else {
        return false;
    }
}
function encrypt_string($string)
{
    return Crypt::encryptString($string);
}
function decrypt_string($string)
{
    return Crypt::decryptString($string);
}
function doEncode($string, $key = 'preciousprotection')
{
    $hash = '';
    $string = base64_encode($string);
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    $j = 0;
    for ($i = 0; $i < $strLen; $i++) {

        $ordStr = ord(substr($string, $i, 1));
        if ($j == $keyLen) {
            $j = 0;
        }
        $ordKey = ord(substr($key, $j, 1));
        $j++;
        $hash .= strrev(base_convert(dechex($ordStr + $ordKey), 16, 36));
    }
    return ($hash);
}
function doDecode($string, $key = 'preciousprotection')
{
    $hash = '';
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    $j = 0;
    for ($i = 0; $i < $strLen; $i += 2) {
        $ordStr = hexdec(base_convert(strrev(substr($string, $i, 2)), 36, 16));
        if ($j == $keyLen) {
            $j = 0;
        }
        $ordKey = ord(substr($key, $j, 1));
        $j++;
        $hash .= chr($ordStr - $ordKey);
    }
    $hash = base64_decode($hash);
    return ($hash);
}

function get_users_folder_random_image()
{
    $images = glob(public_path('users/*.{jpg,jpeg,png,gif,svg}'), GLOB_BRACE);

    if (!empty($images) && count($images) > 0) {
        $randomImage = $images[array_rand($images)];
        $extension = pathinfo($randomImage, PATHINFO_EXTENSION);
        $encryptedName = Str::random(40) . '.' . $extension;
        $destinationPath = 'members/' . $encryptedName;
        Storage::disk('public')->put($destinationPath, file_get_contents($randomImage));
        return $encryptedName;
    }

    return null;
}

function setInvoiceNo($invoice_id)
{

    $output = NULL;

    for ($i = 0; $i < 6 - strlen($invoice_id); $i++) {

        $output .= '0';
    }

    return "Re_" . $output . $invoice_id;
}
function setLeaseInvoiceNo($invoice_id)
{

    $output = NULL;

    for ($i = 0; $i < 6 - strlen($invoice_id); $i++) {

        $output .= '0';
    }

    return "lease_" . $output . $invoice_id;
}
function writ_post_data($file_name, $post)
{
    Storage::put('public/logs/' . $file_name . date('Y-m-d H:i:s') . '.txt', json_encode($post));
}
function create_notification($data, $type = 'notification')
{
    $data['updated_at'] = date('Y-m-d h:i:s');
    $data['created_at'] = date('Y-m-d h:i:s');
    $data['type'] = $type;
    // pr($data);
    DB::table('notifications')->insert($data);
    $id = DB::table('notifications')->insertGetId($data);
    if (intval($data['sender']) > 0 && $sender_row = DB::table("members")->where('id', $data['sender'])->get()->first()) {
        $data['sender_dp'] = get_site_image_src('members', $sender_row->mem_image);
        $data['sender_name'] = $sender_row->mem_display_name ? $sender_row->mem_display_name : $sender_row->mem_fullname;
        $data['time'] = format_date($data['created_at'], "M d, Y");
        $data['id'] = $id;
        $notify = sendPostRequest(env('NODE_SOCKET') . 'receive-notification/', $data);
        // pr($notify);
    } else if (intval($data['sender']) == 0) {
        $site_settings = getSiteSettings();
        $data['sender_dp'] = get_site_image_src('images', $site_settings->site_logo);
        $data['sender_name'] = $site_settings->site_name;
        $data['time'] = format_date($data['created_at'], "M d, Y");
        $data['id'] = $id;
        $notify = sendPostRequest(env('NODE_SOCKET') . 'receive-notification/', $data);
    }
}

function sendPostRequest($url, $data)
{
    try {
        $response = Http::post($url, $data);

        if ($response->successful()) {
            return $response->json();
        } else {
            return [
                'status' => $response->status(),
                'error' => $response->body()
            ];
        }
    } catch (\Exception $e) {
        return [
            'error' => $e->getMessage()
        ];
    }
}
function create_payment_history($data)
{
    $data['created_at'] = date('Y-m-d h:i:s');
    DB::table('payment_history')->insert($data);
}
function updateRecord($table, $field, $value, $arr)
{
    // pr($arr);
    $id = DB::table($table)->where($field, $value)->update($arr);
    return $id;
}
function save_data($data, $table)
{
    // $data['updated_at']=date('Y-m-d h:i:s');
    // $data['created_at']=date('Y-m-d h:i:s');
    // pr($data);
    $id = DB::table($table)->insert($data);
    return $id;
}
function roundToNearestTenCents($price)
{
    $rounded = round($price * 100);
    return number_format($rounded / 10, 2);
}
function get_notifications($mem_id, $limit = null)
{
    $res = [];
    $res['count'] = 0;
    $res['content'] = [];
    $query = DB::table('notifications')
        ->leftJoin('members as receiver', 'notifications.mem_id', '=', 'receiver.id')
        ->leftJoin('members as sender', function ($join) {
            $join->on('notifications.sender', '=', 'sender.id')
                ->where('notifications.sender', '>', 0);
        })
        ->where('notifications.mem_id', $mem_id)
        ->orderBy('notifications.id', 'desc')
        ->select('notifications.*', 'sender.mem_fullname as sender_name', 'sender.mem_image as sender_image');

    // Execute the query
    $results = $query->get();


    if (!empty($limit)) {
        $query->take($limit);
    }
    $res['query'] = Str::replaceArray('?', $query->getBindings(), $query->toSql());
    $notification = $query->get();
    $site_settings = getSiteSettings();
    if (!$notification->isEmpty()) {
        $res['count'] = $notification->count();
        $res['unread'] = DB::table('notifications')->where(['mem_id' => $mem_id, 'status' => 0])->count();

        foreach ($notification as $notify) {
            $obj = (object)[];
            $obj->id = $notify->id;
            $obj->name = $notify->sender_name;
            if ($notify->sender == 0) {
                $obj->thumb = get_site_image_src('images', $site_settings->site_logo);
            } else {
                $obj->thumb = get_site_image_src('members', !empty($notify->sender_image) ? $notify->sender_image : '');
            }

            $obj->text = $notify->text;
            $obj->time = format_date($notify->created_at, "M d, Y");
            $res['content'][] = $obj;
        }
    }

    return $res;
}

function get_site_image_src($path, $image, $type = '', $user_image = false)
{

    if (!empty($image) && Storage::disk('public')->exists($path . '/' . $type . '/' . $image)) {
        $filepath = Storage::url($path . '/' . $type . "/" . $image);
        // if (!empty($image) && @getimagesize($filepath)) {
        return url($filepath);
    } else if (!empty($image) && Storage::disk('public')->exists($path . '/' . $image)) {
        return url(Storage::url($path . '/' . $image));
    }
    return empty($user_image) ? asset('images/no-image.svg') : asset('images/no-user.svg');
}
function get_site_video($path, $video)
{
    $filepath = Storage::url($path . '/' . $video);
    if (!empty($video) && @file_exists("." . Storage::url($path . '/' . $video))) {
        return $filepath;
    }
    return asset('videos/404.mp4');
}
function removeImage($path)
{
    if (file_exists("." . Storage::url($path))) {
        unlink("." . Storage::url($path));
    }
}
function pr($data)
{
    print_r($data);
    die;
}
function getSelectObject($value, $label = '')
{
    $object = (object)[];
    if (!empty($label)) {
        $object->label = $label;
    } else {
        $object->label = $value;
    }

    $object->value = $value;
    return $object;
}

function upload_error($file)
{
    $error_types = array(
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
        'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
        'The uploaded file was only partially uploaded.',
        'No file was uploaded.',
        6 => 'Missing a temporary folder.',
        'Failed to write file to disk.',
        'A PHP extension stopped the file upload.'
    );
    return $error_types[$file];
}
function getMultiText($section)
{
    return DB::table('multi_text')->where('section', $section)->get();
}
function saveMultiText($vals, $section)
{
    if (count($vals['title']) > 0) {
        for ($i = 0; $i < count($vals['title']); $i++) {
            $arr['section'] = $section;
            $arr['title'] = ($vals['title'][$i] != '') ? $vals['title'][$i] : '';
            $arr['detail'] = ($vals['detail'][$i] != '') ? $vals['detail'][$i] : '';
            $arr['txt1'] = isset($vals['txt1'][$i]) != '' ? $vals['txt1'][$i] : '';
            $arr['txt2'] = isset($vals['txt2'][$i]) != '' ? $vals['txt2'][$i] : '';
            $arr['txt3'] = isset($vals['txt3'][$i]) != '' ? $vals['txt3'][$i] : '';
            $arr['txt4'] = isset($vals['txt4'][$i]) != '' ? $vals['txt4'][$i] : '';
            $arr['txt5'] = isset($vals['txt5'][$i]) != '' ? $vals['txt5'][$i] : '';
            $arr['order_no'] = ($vals['order_no'][$i] != '') ? $vals['order_no'][$i] : '';
            DB::table('multi_text')->insert($arr);
        }
    }
}
function saveData($vals, $table)
{
    $id = DB::table($table)->insert($vals);
    return $id;
}

function delete_record($table, $field, $value)
{
    DB::table($table)->where($field, $value)->delete();
}
function get_countries($where = array("id" => 231))
{
    $options = "";
    $rows = DB::table('countries')->where($where)->get();
    return $rows;
}
function findPropertyAddress($where)
{
    pr($where);
    $options = "";
    $rows = DB::table('properties')->where($where)->get();
    return $rows->first();
}
function convertArrayToSelectArray($array)
{
    $rows = array();
    if (!empty($array)) {
        foreach ($array as $arr) {
            $item = (object)[];
            $item->value = $arr->id;
            $item->label = $arr->name;
            $rows[] = $item;
        }
    }
    return $rows;
}
function get_country_name($id)
{
    if (intval($id) > 0 && $row = DB::table('countries')->where('id', $id)->first()) {
        if (!empty($row)) {
            return $row->name;
        } else {
            return 'N/A';
        }
    } else {
        return 'N/A';
    }
}
function get_mem_name($id)
{
    if (intval($id) > 0 && $row = DB::table('members')->where('id', $id)->first()) {
        if (!empty($row)) {
            return $row->mem_fname . " " . $row->mem_lname;
        } else {
            return 'N/A';
        }
    } else {
        return 'N/A';
    }
}
function get_mem_row($id)
{
    if (intval($id) > 0 && $row = DB::table('members')->where('id', $id)->first()) {
        return $row;
    } else {
        return false;;
    }
}

function get_amentiy_name($id)
{
    if (intval($id) > 0 && $row = DB::table('amenties')->where('id', $id)->first()) {
        if (!empty($row)) {
            return $row->title;
        } else {
            return 'N/A';
        }
    } else {
        return 'N/A';
    }
}
function get_floor_plan_name($id)
{
    if (intval($id) > 0 && $row = DB::table('floor_plans')->where('id', $id)->first()) {
        if (!empty($row)) {
            return $row->floor_plan;
        } else {
            return 'N/A';
        }
    } else {
        return 'N/A';
    }
}
function get_floor_plan($id)
{
    if (intval($id) > 0 && $row = DB::table('floor_plans')->where('id', $id)->first()) {
        return $row;
    } else {
        return false;
    }
}
function format_amount($amount, $size = 2)
{
    $amount = floatval($amount);
    return $amount >= 0 ? "$" . number_format($amount, $size) : "$ (" . number_format(abs($amount), $size) . ')';
}
function format_amount_with_symbols($amount, $size = 2)
{
    $amount = floatval($amount);
    if ($amount >= 10000 && $amount <= 999499) {
        return "$" . round($amount / 1000, 1) . "K";
    } else if ($amount > 999499) {
        return "$" . round($amount / 1000000, 1) . "M";
    } else {
        return $amount >= 0 ? "$" . number_format($amount, $size) : "$ (" . number_format(abs($amount), $size) . ')';
    }
}
function format_number($amount, $size = 2)
{
    $amount = floatval($amount);
    return number_format(abs($amount), $size);
}
function formatDecimalNumber($number, $size = 2)
{
    if (strpos($number, '.') !== false) {
        return number_format($number, $size);
    } else {
        return $number;
    }
}
function get_branch_name($id)
{
    if (intval($id) > 0 && $row = DB::table('branches')->where('id', $id)->first()) {
        if (!empty($row)) {
            return $row->name;
        } else {
            return 'N/A';
        }
    } else {
        return 'N/A';
    }
}
function get_branch_description($id)
{
    if (intval($id) > 0 && $row = DB::table('branches')->where('id', $id)->first()) {
        if (!empty($row)) {
            return $row->description;
        } else {
            return '';
        }
    } else {
        return '';
    }
}
function get_property_name($id)
{
    if (intval($id) > 0 && $row = DB::table('properties')->where('id', $id)->first()) {
        if (!empty($row)) {
            return $row->title;
        } else {
            return 'N/A';
        }
    } else {
        return 'N/A';
    }
}
function get_property($id)
{
    return $row = DB::table('properties')->where('id', $id)->first();
}
function get_property_member($id)
{
    if (intval($id) > 0 && $row = DB::table('properties')->where('id', $id)->first()) {
        if (!empty($row)) {
            return get_mem_name($row->mem_id);
        } else {
            return 'N/A';
        }
    } else {
        return 'N/A';
    }
}
function get_mem_properties($mem_id)
{
    if (intval($mem_id) > 0 && $row = DB::table('properties')->where('mem_id', $mem_id)->get()) {
        if (!empty($row)) {
            return $row->count();
        } else {
            return 'N/A';
        }
    } else {
        return 'N/A';
    }
}
function AddMonths($months, $date = '')
{
    if ($date != '') {
        $total_months = date('Y-m-d', strtotime("+" . $months . " months", strtotime(date('Y-m-d', strtotime($date)))));
    } else {
        $total_months = date('Y-m-d', strtotime("+" . $months . " months", strtotime(date('Y-m-d'))));
    }

    return $total_months;
}
function add_days($months, $date)
{
    $total_days = 0;
    if (!empty($date)) {
        $total_days = intval($months) * 30;
        $final_date = date('Y-m-d', strtotime("+" . $total_days . " days", strtotime(date('Y-m-d', strtotime($date)))));
    } else {
        $final_date = date('Y-m-d', strtotime("+" . $total_days . " days", strtotime(date('Y-m-d'))));
    }
    return $final_date;
}
function convertArrayToStringMessage($errors)
{
    $message = '';
    if (is_array($errors)) {
        foreach ($errors as $err) {
            $message .= $err->message;
        }
    } else {
        $message = $errors;
    }
    return $message;
}
function getPackageID($package)
{
    if ($package == 'N') {
        return 0;
    } else if ($package == 'CC') {
        return 5002;
    } else if ($package == 'CCE') {
        return 5003;
    } else if ($package == 'CCI') {
        return 5007;
    } else if ($package == 'CCEI') {
        return 5004;
    } else {
        return 0;
    }
}

function getOfferPackage($package, $mem_id)
{
    $packages = get_mem_packages_names($mem_id);
    if (!empty($packages)) {
        if ($package == 'CC') {
            if (in_array("CC", $packages) == true || in_array("CCEI", $packages) == true) {
                return false;
            } else {
                return true;
            }
        } else if ($package == 'CCE') {
            if (in_array("CCEI", $packages) == true) {
                return false;
            } else {
                return true;
            }
        } else if ($package == 'CCI') {
            if (in_array("CCEI", $packages) == true) {
                return false;
            } else {
                return true;
            }
        } else if ($package == 'CCEI') {
            // if(in_array("CCE",$packages)==true && in_array("CCI",$packages)==true){
            //     return false;
            // }
            if (in_array("CCEI", $packages) == true) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
}
function getGoogleMapAddress($address)
{
    $key = env('GOOGLE_API_KEY');
    $details_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $address['line1'] . " " . $address['line2'] . ", " . $address['city'] . " " . $address['state'] . " " . $address['zip_code'] . "&key=" . $key;
    $newUrl = str_replace(' ', '%20', $details_url);
    // pr($newUrl);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $newUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    if ($result === false) {
        // throw new Exception('Curl error: ' . curl_error($crl));
        return array(
            'error' => curl_error($ch),
            'status' => 0
        );
    }
    // Close cURL resource
    curl_close($ch);
    $res = json_decode($result);
    if ($res->status == 'OK' || $res->status == 'ok') {
        return array(
            'address' => format_address_one_line($res->results[0]->formatted_address),
            'latitude' => $res->results[0]->geometry->location->lat,
            'longitude' => $res->results[0]->geometry->location->lng,
            'place_id' => $res->results[0]->place_id,
            'status' => 1
        );
    }
    return array(
        'error' => $res->status,
        'status' => 0
    );
}

function getGoogleMapAddressAPI($address)
{
    $key = env('GOOGLE_API_KEY');
    $details_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $address['line1'] . " " . $address['line2'] . ", " . $address['city'] . " " . $address['state'] . " " . $address['zip_code'] . "&key=" . $key;
    $newUrl = str_replace(' ', '%20', $details_url);
    // pr($newUrl);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $newUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    if ($result === false) {
        // throw new Exception('Curl error: ' . curl_error($crl));
        return array(
            'error' => curl_error($ch),
            'status' => 0
        );
    }
    // Close cURL resource
    curl_close($ch);
    $res = json_decode($result);
    if ($res->status == 'OK' || $res->status == 'ok') {
        // pr($res);
        $components = $res->results[0]->address_components;
        // pr($components);
        $street_number = array_values(filter($components, "street_number"))[0]->long_name;
        $route = array_values(filter($components, "route"))[0]->long_name;
        $neighborhood = array_values(filter($components, "neighborhood"))[0]->long_name;
        $locality = array_values(filter($components, "locality"))[0]->long_name;
        $zipcode = array_values(filter($components, "postal_code"))[0]->long_name;
        $citystate = array_values(filter($components, "administrative_area_level_1"))[0]->long_name;
        // pr($street_number." ".$route." ".$neighborhood.", ".$locality.", ".$citystate.", ".$zipcode);
        return array(
            'address' => $res->results[0]->formatted_address,
            'latitude' => $res->results[0]->geometry->location->lat,
            'longitude' => $res->results[0]->geometry->location->lng,
            'place_id' => $res->results[0]->place_id,
            'status' => 1
        );
    }
    return array(
        'error' => $res->status,
        'status' => 0
    );
}
function filter($components, $type)
{
    return array_filter($components, function ($component) use ($type) {
        return array_filter($component->types, function ($data) use ($type) {
            return $data == $type;
        });
    });
}
function curl_request($url, $payload, $token = '', $put = false)
{
    $ch = curl_init($url);

    // Attach encoded JSON string to the POST fields
    if ($put == true || $put == 1) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    }
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    $headers = array(
        'Content-Type:application/json',
        "Authorization: " . $token . "",
    );
    // Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Execute the POST request
    $result = curl_exec($ch);
    if ($result === false) {
        // throw new Exception('Curl error: ' . curl_error($crl));
        return 'Curl error: ' . curl_error($ch);
    }
    // Close cURL resource
    curl_close($ch);
    return json_decode($result);
}
function convertPhoneToNumber($phone)
{
    $phone = str_replace(array('(', ')'), '', $phone);
    $phone = str_replace(' ', '', $phone);
    $phone = str_replace('+', '', $phone);
    $phone = str_replace('-', '', $phone);
    $phone = substr($phone, 1);
    return $phone;
}
function truncate_number($number, $precision = 2)
{
    // // Zero causes issues, and no need to truncate
    // if ( 0 == (int)$number ) {
    //     return $number;
    // }
    // // Are we negative?
    // $negative = $number / abs($number);
    // // Cast the number to a positive to solve rounding
    // $number = abs($number);
    // // Calculate precision number for dividing / multiplying
    // $precision = pow(10, $precision);
    // // Run the math, re-applying the negative value to ensure returns correctly negative / positive
    // return floor( $number * $precision ) / $precision * $negative;
    return $number;
}
function curl_get_request($url, $token = '', $openstreetmap = false)
{
    $ch = curl_init($url);

    // Attach encoded JSON string to the POST fields
    if (!empty($token)):
        $headers = array(
            'Content-Type:application/json',
            "Authorization: " . $token . "",
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    endif;
    if ($openstreetmap):
        $headers = array(
            "Content-Type: application/json",
            "header" => "User-Agent: Nominatim-Test"
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    endif;
    // Set the content type to application/json


    // Return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Execute the POST request
    $result = curl_exec($ch);
    if ($result === false) {
        // throw new Exception('Curl error: ' . curl_error($crl));
        return 'Curl error: ' . curl_error($ch);
    }
    // Close cURL resource
    curl_close($ch);
    return json_decode($result);
}
function createTransUnionToken()
{
    // API URL
    $url = config('app.transunion_api') . 'Tokens';
    $data = [
        'clientId' => env('TRANSUNION_API_CLIENT'),
        'apiKey' => env('TRANSUNION_API_KEY'),
    ];
    $payload = json_encode($data);

    $ch = curl_init($url);

    // Attach encoded JSON string to the POST fields
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    // Set the content type to application/json
    if (!empty($token)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization' => $token));
    } else {
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    }

    // Return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the POST request
    $result = curl_exec($ch);
    if ($result === false) {
        // throw new Exception('Curl error: ' . curl_error($crl));
        return 'Curl error: ' . curl_error($ch);
    }
    // Close cURL resource
    curl_close($ch);
    return json_decode($result);
}

function getCompanyListingPrice($property)
{
    $listings = Listing_model::where(['property' => $property, 'mem_type' => 'company'])->get();
    if (count($listings) >  0) {
        $max_price = 0;
        $min_price = 0;
        foreach ($listings as $key => $listing) {
            $max = Listing_prices_model::where('listing_id', $listing->id)->max('price');
            $min = Listing_prices_model::where('listing_id', $listing->id)->min('price');
            if ($key == 0) {
                $max_price = $max;
                $min_price = $min;
            } else {
                if ($max > $max_price) {
                    $max_price = $max;
                }
                if ($min < $min_price) {
                    $min_price = $min;
                }
            }
        }
        return ['max_price' => $max_price, 'min_price' => $min_price];
    }
    return false;
}
function getDays($future_date)
{
    $now = time(); // or your date as well
    $your_date = strtotime($future_date);
    $datediff = $your_date - $now;

    return round($datediff / (60 * 60 * 24));
}
function getListingDays($future_date)
{
    $now = time(); // or your date as well
    $your_date = strtotime($future_date);
    $datediff = $now - $your_date;

    return round($datediff / (60 * 60 * 24));
}
function get_property_image($id)
{
    if (intval($id) > 0 && $row = DB::table('properties')->where('id', $id)->first()) {
        if (!empty($row)) {
            return $row->imageThumbnail;
        } else {
            return 'N/A';
        }
    } else {
        return 'N/A';
    }
}

function addLog($logFile)
{
    Log::useDailyFiles(storage_path() . '/logs/' . $logFile);
}

function getSiteSettings()
{
    return Admin::where('id', '=', 1)->first();
}
function get_branch_size($id)
{
    if (intval($id) > 0 && $row = DB::table('branches')->where('id', $id)->first()) {
        if (!empty($row)) {
            return $row->lot_size;
        } else {
            return 'N/A';
        }
    } else {
        return 'N/A';
    }
}
function get_listing_floor_plan($id)
{
    if (intval($id) > 0 && $row = DB::table('branches')->where('id', $id)->first()) {
        if (!empty($row)) {
            return get_floor_plan_name($row->floor_plan);
        } else {
            return 'N/A';
        }
    } else {
        return 'N/A';
    }
}
function get_branch_address($id)
{
    if (intval($id) > 0 && $row = DB::table('branches')->where('id', $id)->first()) {
        if (!empty($row)) {
            return $row->address;
        } else {
            return 'N/A';
        }
    } else {
        return 'N/A';
    }
}
function get_amenity_name($id)
{
    if (intval($id) > 0 && $row = DB::table('amenties')->where('id', $id)->first()) {
        if (!empty($row)) {
            return $row->title;
        } else {
            return 'N/A';
        }
    } else {
        return 'N/A';
    }
}
function get_faq_category_name($id)
{
    if (intval($id) > 0 && $row = DB::table('faq_categories')->where('id', $id)->first()) {
        if (!empty($row)) {
            return $row->name;
        } else {
            return 'N/A';
        }
    } else {
        return 'N/A';
    }
}
function get_category_name($id, $table_name = 'categories')
{
    if (intval($id) > 0 && $row = DB::table($table_name)->where('id', $id)->first()) {
        if (!empty($row)) {
            return $row->name;
        } else {
            return 'N/A';
        }
    } else {
        return 'N/A';
    }
}
function convertEmailToUsername($email)
{
    list($usernamePart) = explode('@', $email);

    $uniqueIdentifier = rand(1000, 9999);

    $username = $usernamePart . "_" . $uniqueIdentifier;

    return $username;
}
function calculateDaysBetween($startDate, $endDate)
{
    date_default_timezone_set('Australia/Sydney');
    $start = DateTime::createFromFormat('Y-m-d', $startDate);
    $end = DateTime::createFromFormat('Y-m-d', $endDate);

    // Check if date creation was successful
    if ($start === false || $end === false) {
        throw new Exception("Invalid date format. Please use 'YYYY-MM-DD'.");
    }

    $interval = $start->diff($end);
    // Return the number of days
    return $interval->days + 1;
}
function get_country_states($country_id)
{
    $rows = DB::table('states')->where('country_id', $country_id)->get();
    return $rows;
}
function get_cat_faqs($category)
{
    $options = "";
    $rows = DB::table('faqs')->where(['category' => $category, 'status' => 1])->get();
    return $rows;
}
function table_count($table, $where = array(), $only_count = false)
{
    $count = DB::table($table)->where($where)->count();
    if ($only_count) {
        return $count;
    } else if (!empty($count) && $count > 0) {
        return '<span class="badge badge-light-danger">' . $count . '</span>';
    }
}
function get_site_settings()
{
    return Admin::where('id', '=', 1)->first();
}
function convertArrayMessageToString($array)
{
    $messages = '';
    if (!empty($array)) {
        foreach ($array as $item) {
            $messages .= $item;
        }
    }
    return $messages;
}
function getWebsiteSocialLinks()
{
    $social_links = array();
    $facebook = (object)[];
    $instagram = (object)[];
    $discord = (object)[];
    $twitter = (object)[];
    $email = (object)[];
    //Social Links
    $site_settings = get_site_settings();
    $facebook->id = 1;
    $facebook->link = $site_settings->site_facebook;
    $facebook->image = config('app.react_url') . '/images/social-facebook.svg';
    $social_links[] = $facebook;
    //Instagram
    $instagram->id = 2;
    $instagram->link = $site_settings->site_instagram;
    $instagram->image = config('app.react_url') . '/images/social-instagram.svg';
    $social_links[] = $instagram;
    //Twitter
    $twitter->id = 3;
    $twitter->link = $site_settings->site_twitter;
    $twitter->image = config('app.react_url') . '/images/social-twitter.svg';
    $social_links[] = $twitter;
    //Discord
    $discord->id = 4;
    $discord->link = $site_settings->site_discord;
    $discord->image = config('app.react_url') . '/images/social-discord.svg';
    $social_links[] = $discord;
    //Email
    $email->id = 5;
    $email->link = $site_settings->site_email;
    $email->image = config('app.react_url') . '/images/social-email.svg';
    $social_links[] = $email;
    return $social_links;
}
function get_state_name($id)
{
    if (intval($id) > 0 && $row = DB::table('states')->where('id', $id)->first()) {
        if (!empty($row)) {
            return $row->name;
        } else {
            return 'N/A';
        }
    } else {
        return 'N/A';
    }
}
function get_state_code($id)
{
    if (intval($id) > 0 && $row = DB::table('states')->where('id', $id)->first()) {
        if (!empty($row)) {
            return $row->code;
        } else {
            return 'N/A';
        }
    } else {
        return 'N/A';
    }
}
function getStatus($status)
{
    if ($status == 1) {
        return '<span class="badge bg-success-subtle text-success">Active</span>';
    } else {
        return '<span class="badge bg-danger-subtle text-danger">InActive</span>';
    }
}
function getTenantStatus($status)
{
    if ($status == 1) {
        return '<span class="badge green">Complete</span>';
    } else {
        return '<span class="badge yellow">Incomplete</span>';
    }
}
function getTenantReportStatus($expiry_date)
{
    if (strtotime($expiry_date) >= strtotime(date('Y-m-d'))) {
        return '<span class="badge green">Received</span>';
    } else {
        return '<span class="badge red">Expired</span>';
    }
}
function getLandlordReportExpiryDate($screeningRequestRenterId, $type)
{
    if ($type == 'IdReport') {
        $report = DB::table('offer_tenants')
            ->select('offer_tenant_reports.expiry_date')
            ->join('offer_tenant_reports', 'offer_tenant_reports.tenant_id', '=', 'offer_tenants.id')
            ->where(['offer_tenants.screeningRequestRenterId' => intval($screeningRequestRenterId)])
            ->get()->first();
    } else {
        $report = DB::table('offer_tenants')
            ->select('offer_tenant_reports.expiry_date')
            ->join('offer_tenant_reports', 'offer_tenant_reports.tenant_id', '=', 'offer_tenants.id')
            ->where(['offer_tenants.screeningRequestRenterId' => intval($screeningRequestRenterId), 'offer_tenant_reports.type' => $type])
            ->get()->first();
    }

    if (!empty($report)) {
        return getTenantReportStatus($report->expiry_date);
    } else {
        return 'N/A';
    }
}
function getLandlordReportExpiryDateFlag($screeningRequestRenterId, $type)
{
    if ($type == 'IdReport') {
        $report = DB::table('offer_tenants')
            ->select('offer_tenant_reports.expiry_date')
            ->join('offer_tenant_reports', 'offer_tenant_reports.tenant_id', '=', 'offer_tenants.id')
            ->where(['offer_tenants.screeningRequestRenterId' => $screeningRequestRenterId])
            ->get()->first();
    } else {
        $report = DB::table('offer_tenants')
            ->select('offer_tenant_reports.expiry_date')
            ->join('offer_tenant_reports', 'offer_tenant_reports.tenant_id', '=', 'offer_tenants.id')
            ->where(['offer_tenants.screeningRequestRenterId' => $screeningRequestRenterId, 'offer_tenant_reports.type' => $type])
            ->get()->first();
    }
    if (!empty($report)) {
        return getTenantReportStatusFlag($report->expiry_date);
    } else {
        return 'N/A';
    }
}
function getTenantReportStatusFlag($expiry_date)
{
    if (strtotime($expiry_date) >= strtotime(date('Y-m-d'))) {
        return true;
    } else {
        return false;
    }
}
function getOfferStatus($offer_status, $tenants_unpaid_count)
{
    if ($tenants_unpaid_count > 0) {
        return '<span class="badge yellow">Incomplete</span>';
    } else if ($offer_status == 'accepted') {
        return '<span class="badge green">Accepted</span>';
    } else if ($offer_status == 'rejected') {
        return '<span class="badge red">Rejected</span>';
    } else {
        return '<span class="badge yellow">Pending</span>';
    }
}
function getReadStatus($status)
{
    if ($status == 1) {
        return '<span class="badge bg-success-subtle text-success">Read</span>';
    } else {
        return '<span class="badge bg-danger-subtle text-danger">Unread</span>';
    }
}
function getWithdrawStatus($status)
{
    if ($status == 'cleared') {
        return '<span class="badge bg-success-subtle text-success">Cleared</span>';
    } else {
        return '<span class="badge bg-danger-subtle text-danger">Pending</span>';
    }
}
function getUserIdStatus($status)
{
    if ($status == 'verified') {
        return '<span class="badge bg-success-subtle text-success">Verified</span>';
    } else if ($status == 'unverified') {
        return '<span class="badge bg-danger-subtle text-danger">Unverified</span>';
    } else if ($status == 'requested') {
        return '<span class="badge bg-info-subtle text-info">Requested</span>';
    } else {
        return '<span class="badge bg-warning-subtle text-warning">In Progress</span>';
    }
}

function getRequestsStatus($status)
{
    if ($status == 'in_progress') {
        return '<span class="badge bg-info-subtle text-success">In Progress</span>';
    } else if ($status == 'prescription_in_progress') {
        return '<span class="badge bg-yellow-subtle text-success">Prescription In Progress</span>';
    } else if ($status == 'payed') {
        return '<span class="badge bg-success-subtle text-success">Payed</span>';
    }else if ($status == 'prescription') {
        return '<span class="badge bg-teal-subtle text-success">Prescription</span>';
    }else if ($status == 'prescription') {
        return '<span class="badge bg-ganger-subtle text-danger">Closed</span>';
    } else {
        return '<span class="badge bg-warning-subtle text-warning">New</span>';
    }
}

function has_access($permission_id = 0)
{
    if (is_admin())
        return true;
    if (!in_array($permission_id, session('permissions'))) {
        abort(404, 'Item not found');
        exit;
    }
    return session('PropertyLoginId');
}
function access($permission_id)
{
    if (is_admin()) return true;
    return in_array($permission_id, session('permissions'));
}
function is_admin()
{
    return session('admin_type') == 'admin' ? true : false;
}
function getTicketStatus($status)
{
    if ($status == 'open') {
        return '<span class="badge bg-success-subtle text-success">Open</span>';
    } else if ($status == 'closed') {
        return '<span class="badge bg-danger-subtle text-danger">Closed</span>';
    } else if ($status == 'in_progress') {
        return '<span class="badge bg-info-subtle text-info">In Progress</span>';
    } else {
        return '<span class="badge bg-warning-subtle text-warning">Pending</span>';
    }
}
function getApproveStatus($status)
{
    if ($status == '1') {
        return '<span class="badge badge-success">Approved</span>';
    } else if ($status == '2') {
        return '<span class="badge badge-warning">Denied</span>';
    } else if ($status == '3') {
        return '<span class="badge badge-danger">Cancelled</span>';
    } else {
        return '<span class="badge badge-secondary">Pending</span>';
    }
}
function getFeatured($status)
{
    if ($status == 1) {
        return '<span class="badge bg-success-subtle text-success">Yes</span>';
    } else {
        return '<span class="badge bg-danger-subtle text-danger">No</span>';
    }
}
function getFirstLetters($string)
{
    $words = explode(" ", $string);
    $result = '';

    foreach ($words as $word) {
        $result .= substr($word, 0, 1);
    }

    return $result;
}
function replaceSpaceWith20($input)
{
    // Replace all spaces with "%20"
    return str_replace(' ', '%20', $input);
}
function userAccountType($type)
{
    if (!empty($type)) {
        return '<span class="badge bg-danger-subtle text-danger"><i class="fa fa-google-plus-square"></i> Google</span>';
    } else {
        return '<span class="badge bg-info-subtle text-info"><i class="fa fa-user"></i> Website User</span>';
    }
}
function get_page($key)
{
    $row = Sitecontent::where('ckey', $key)->first();
    return unserialize($row->code);
}
function get_blog_tags()
{
    $keywords = Blog_model::pluck('meta_keywords');
    $tags = '';
    foreach ($keywords as $key => $keyword) {
        $tags .= strtolower($keyword);
    }
    $meta = explode(",", rtrim($tags, ","));
    $blog_tags = [];
    foreach ($meta as $mt) {
        $blog_tags[] = trim($mt);
    }
    return array_unique($blog_tags);
}
function time_ago($time)
{
    $time = str_replace('/', '-', $time);
    $timestamp = (is_numeric($time) && (int)$time == $time) ? $time : strtotime($time);

    $strTime = array(" sec", " min", " hr", " day", " month", " year");
    $length = array("60", "60", "24", "30", "12", "10");

    $currentTime = strtotime(date("Y-m-d H:i:s"));
    if ($currentTime >= $timestamp) {
        $diff = $currentTime - $timestamp;
        for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
            $diff = $diff / $length[$i];
        }
        $diff = round($diff);

        if ($diff == 1 && $strTime[$i] == ' day') {
            return 'yesterday';
        }

        $ago = $diff > 1 ? 's ago' : ' ago';
        return $diff . $strTime[$i] . $ago;
    } else {
        return "in the future";
    }
}

// Test the function
function timeAgo($time)
{
    $time = str_replace('/', '-', $time);
    $timestamp = (is_numeric($time) && (int)$time == $time) ? $time : strtotime($time);

    $strTime = array(" sec", " min", " hr", " day", " month", " year");
    $length = array("60", "60", "24", "30", "12", "10");

    $currentTime = strtotime(date("Y-m-d H:i:s"));
    if ($currentTime >= $timestamp) {
        $diff = $currentTime - $timestamp;
        for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
            $diff = $diff / $length[$i];
        }
        $diff = round($diff);

        if ($diff == 1 && $strTime[$i] == ' day') {
            return 'yesterday';
        }

        $ago = $diff > 1 ? 's ago' : ' ago';
        return $diff . $strTime[$i] . $ago;
    } else {
        return "in the future";
    }
}
function format_date($d, $format = '', $default_show = 'TBD')
{
    $format = empty($format) ? 'm/d/Y' : $format;
    // $d = str_replace('/', '-', $d);
    if ($d == '0000:00:00' || $d == '0000-00-00' || !$d)
        return $default_show;
    $d = (is_numeric($d) && (int)$d == $d) ? $d : strtotime($d);
    return date($format, $d);
}

function format_american_date($d, $format = '', $default_show = 'TBD', $timezone = 'America/New_York')
{
    $format = empty($format) ? 'm/d/Y' : $format;

    if ($d == '0000:00:00' || $d == '0000-00-00' || !$d)
        return $default_show;

    // Convert to timestamp
    $d = (is_numeric($d) && (int)$d == $d) ? $d : strtotime($d);

    // Set timezone
    $dt = new DateTime("@$d");
    $dt->setTimezone(new DateTimeZone($timezone));

    return $dt->format($format);
}
function subtractHoursFromTime($hours = 4)
{
    $timezone = new DateTimeZone("America/New_York");

    // Create a DateTime object with the specified timezone
    $datetime = new DateTime(null, $timezone);

    // Subtract 4 hours
    // $interval = new DateInterval('PT4H');
    // $datetime->sub($interval);

    // Format and print the updated datetime in the specified timezone
    $updatedDatetime = $datetime->format('Y-m-d H:i:s');
    return $updatedDatetime;
}
function convertDateToTimeZone($timestamp, $timezone)
{ /* input: 1518404518,America/Los_Angeles */
    $date = new DateTime(date("d F Y H:i:s", $timestamp));
    $date->setTimezone(new DateTimeZone($timezone));
    $rt = $date->format('Y-m-d'); /* output: Feb 11, 2018 7:01:58 pm */
    return $rt;
}
function toSlugUrl($text)
{

    $text = trim($text);
    $text = str_replace("&quot", '', $text);
    $text = preg_replace('/[^A-Za-z0-9-]+/', '-', $text);
    $text = str_replace("--", '-', $text);
    $text = str_replace("--", '-', $text);
    $text = str_replace("@", '-', $text);
    return strtolower($text);
}
function short_text($str, $length = 150)
{
    $str = strip_tags($str);
    return strlen($str) > $length ? substr($str, 0, $length) . '...' : $str;
}
function countEndingDigits($string)
{
    $tailing_number_digits =  0;
    $i = 0;
    $from_end = -1;
    while ($i < strlen($string)) :
        if (is_numeric(substr($string, $from_end - $i, 1))) :
            $tailing_number_digits++;
        else :
            // End our while if we don't find a number anymore
            break;
        endif;
        $i++;
    endwhile;
    return $tailing_number_digits;
}
function getData($table_name, $where)
{
    if (empty($table_name)) {
        $table_name = 'faqs';
    }
    $rows = DB::table($table_name)->where($where)->get();
    return $rows;
}
function calculatePercentage($number, $percentage)
{
    return ($number * $percentage) / 100;
}
function getSingleData($table_name, $where)
{
    if (empty($table_name)) {
        $table_name = 'faqs';
    }
    $rows = DB::table($table_name)->where($where)->get()->first();
    return $rows;
}

function get_pages()
{
    return $page_arr = array('/' => 'Home', '/about' => 'About Us', '/services' => 'Services', '/contact' => 'Contact Us', '/privacy-policy' => 'Privacy Policy', '/terms-conditions' => 'Terms & Conditions');
}
function checkSlug($slug, $table_name, $id = '')
{

    if (
        DB::Table($table_name)->where('slug', $slug)->when($id, function ($query) use ($id) {
            return $query->where('id', '!=', $id);
        })->count()
        > 0
    ) {
        $numInUN = countEndingDigits($slug);
        if ($numInUN > 0) {
            $base_portion = substr($slug, 0, -$numInUN);
            $digits_portion = abs(substr($slug, -$numInUN));
        } else {
            $base_portion = $slug . "-";
            $digits_portion = 0;
        }

        $slug = $base_portion . intval($digits_portion + 1);
        $slug = checkSlug($slug, $table_name);
    }

    return $slug;
}

function send_email($data, $template)
{
    require base_path("vendor/autoload.php");
    $mail = new PHPMailer(true);     // Passing `true` enables exceptions

    try {
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        //Enable SMTP debugging
        $mail->SMTPDebug = 0;
        $mail->ContentType = 'text/html; charset=utf-8';
        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';
        //Set the hostname of the mail server
        $mail->Host = 'ssl://mail.herosolutions.com.pk';
        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        // I tried PORT 25, 465 too
        $mail->Port = 465;
        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = 'tls';
        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = "noreply@herosolutions.com.pk";
        //Password to use for SMTP authentication
        $mail->Password = "B2sBcS^##C1z";
        //Set who the message is to be sent from
        $mail->setFrom($data['email_from'], $data['email_from_name']);

        //Set who the message is to be sent to
        $mail->addAddress($data['email_to'], $data['email_to_name']);
        $mail->isHTML(true);
        //Set the subject line
        $mail->Subject = $data['subject'];

        $e_data['site_settings'] = getSiteSettings();
        $e_data['content'] = $data;
        // pr($e_data)
        $eMessage = view('emails.' . $template, $e_data);
        // pr($eMessage);
        $mail->Body = $eMessage;
        //Replace the plain text body with one created manually
        $mail->AltBody = 'This is a plain-text message body';

        //send the message, check for errors
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
            return false;
        } else {
            return true;
        }
    } catch (\Exception $e) {
        echo ($e);
        echo ("Message could not be sent. Error >> " . $e->getMessage());
        return false;
    }
}
