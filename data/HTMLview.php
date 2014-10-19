<?php
class HTMLview {

    public function echoHTML($body) {

        if ($body === NULL) {
            throw new \Exception("HTMLview::echoHTML does not allow body to be null");
        }

		$time = '[' . strftime("%H:%M:%S") . ']';
		$year = date("Y");
		$month = date("M");
		$day = (int)(date("d"));

		$sweWeekday = $this->GetSwedishWeekday(date("d"), date("M"), date("Y"));
		$sweMonth = $this->GetSwedishMonth(strftime("%Y"), strftime("%m"), strftime("%d"));
		$date = '<p id="date">' . $sweWeekday . ', den ' . $day . ' ' . $sweMonth . ' år ' . $year . '. ' . 'Klockan är ' . $time . '</p>';

        echo "
			<!DOCTYPE html>
			<html>
				<head>
				    <title>Photo Gallery</title>
				    <meta name='viewport' content='width=device-width'>
				    <meta http-equiv='content-type' content='text/html; charset=utf-8' />
				    <link href='../css/photo.css' rel='stylesheet' />
				</head>
				<body>
					<h1>Photo Gallery</h1>
					$body
					$date
				</body>
			</html>";
    }

	// Pattern ref: http://scriptcult.com/subcategory_4/article_885-get-weekday-name-based-on-date.html
	protected function GetSwedishWeekday ($day, $month, $yearForDay) {

		$weekDay = Array(
			'Monday'	=>	'Måndag', 
			'Tuesday'	=>	'Tisdag', 
			'Wednesday'	=>	'Onsdag',
			'Thursday'	=>	'Torsdag', 
			'Friday'	=>	'Fredag', 
			'Saturday'	=>	'Lördag', 
			'Sunday'	=>	'Söndag'
		);

		return $weekDay[date("l", strtotime($yearForDay.'-'.$month.'-'.$day))];
	}

	protected function GetSwedishMonth ($year, $month, $day) {

		$date = $year.'-'.$month.'-'.$day;

		$month = Array(
			'Jan'=>'Januari', 
			'Feb'=>'Februari',
			'Mar'=>'Mars',
			'Apr'=>'April',
			'May'=>'Maj',
			'Jun'=>'Juni',
			'Jul'=>'Juli',
			'Aug'=>'Augusti',
			'Sep'=>'September',
			'Oct'=>'Oktober',
			'Nov'=>'November',
			'Dec'=>'December'
		);

			return $month[strftime("%h", strtotime((string)$date))];
	}
}