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

		$weekDay = date("l", strtotime(date("d").'-'.date("M").'-'.date("Y")));
		$month = strftime("%B", time());

		// $month = strftime((string)date("M"));
		$sweWeekday = $this->GetSwedishWeekday(date("d"), date("M"), date("Y"));
		$sweMonth = $this->GetSwedishMonth(strftime("%Y"), strftime("%m"), strftime("%d"));
		$date = '<div id="date">';
		$date .= '<p>' . $weekDay . ', ' . $month . ' ' . $year . '. ' . 'Time is ' . $time . '</p>';
		$date .= '</div>';

        echo "
			<!DOCTYPE html>
			<html>
				<head>
				    <title>Photo Gallery</title>
				    <meta name='viewport' content='width=device-width'>
				    <meta http-equiv='content-type' content='text/html; charset=utf-8' />
				    <link href='http://localhost:8888/www/git/PHP_PhotoGallery/css/photo.css' rel='stylesheet' />
				    <link href='http://localhost:8888/www/git/PHP_PhotoGallery/css/photoView.css' rel='stylesheet' />
				    <link href='http://localhost:8888/www/git/PHP_PhotoGallery/css/comment.css' rel='stylesheet' />
				    <link href='http://localhost:8888/www/git/PHP_PhotoGallery/css/responseMessage.css' rel='stylesheet' />
				    <link href='http://localhost:8888/www/git/PHP_PhotoGallery/css/gallery.css' rel='stylesheet' />
				    <link href='http://localhost:8888/www/git/PHP_PhotoGallery/css/pagination.css' rel='stylesheet' />
				    <link href='http://localhost:8888/www/git/PHP_PhotoGallery/css/date.css' rel='stylesheet' />
				    <link href='http://localhost:8888/www/git/PHP_PhotoGallery/css/nav.css' rel='stylesheet' />
				    <link href='http://localhost:8888/www/git/PHP_PhotoGallery/css/table.css' rel='stylesheet' />
				    <link href='http://localhost:8888/www/git/PHP_PhotoGallery/css/upload.css' rel='stylesheet' />
				    <link href='http://localhost:8888/www/git/PHP_PhotoGallery/css/login.css' rel='stylesheet' />
				</head>
				<body>
					<h1>Photo Gallery</h1>
					$body
					<footer>
					$date
					</footer>
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