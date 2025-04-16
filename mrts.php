<?php
/***************************************************************************\
*                 MRTS - MRTG RRDtool Total Statistics v0.1                 *
*****************************************************************************
* This program is free software; you can redistribute it and/or modify it   *
* under the terms of the GNU General Public License as published by the     *
* Free Software Foundation; either version 2 of the License, or (at your    *
* option) any later version.                                                *
*****************************************************************************
* This script is written by Thor Dreier                                     *
* More information can be found at  http://apt-get.dk/mrts/                 *
\***************************************************************************/



/* The directory where the rrd files are located */
//$dir = '/var/log/mrtg';
$dir = '/var/www/html/mrtg';



/* List all devices that MRTS should'n display, */
$exclude = array('secret', 'topsecret');



/* RRDtool path - where are the the executable located */
$rrdcommand = '/usr/bin/rrdtool';



/* Change this to get another top on the site */
function top($name)
{ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title><?php echo $GLOBALS['title']; ?></title>
  <style type="text/css">
   <!--

   .datahead,.total1head,.total2head,.data,.total1,.total2
   {
   	 border: 1px solid black;
     width: 50px;
   }

   .datahead,.total1head,.total2head
   {
    font-size: 12px;
    font-weight: bold;
   }

   .data,.total1,.total2
   {
    font-size: 10px;
   }

   .total1,.total1head
   {
    background-color: #dddddd;
   }

   .total2,.total2head
   {
    background-color: #bbbbbb;
   }

   h1,h2,h3
   {
    text-align: center;
   }

   -->
  </style>
 </head>
 <body>
 <h1><?php echo $GLOBALS['title']; ?></h1>
 <h2><?php echo $name; ?></h2>
<?php }



/* Change this to get another bottom on the site */
function bottom()
{ ?>
  <hr>
  Created by <a href="http://apt-get.dk/mrts/"><?php echo $GLOBALS['title'] ?></a>
 </body>
</html>
<?php }



/***************************************************************************\
*                It should be no need to edit anything below                *
*                   this point, unless there are problems                   *
*****************************************************************************
* - or said in another way - if you change anything radical below here, you *
* have to make the changes public, as this code is GNU licensed code        *
\***************************************************************************/






/***************************************************************************\
*                                 Variables                                 *
\***************************************************************************/



	/* File extension of the MRTG-RRD-files */
	$extension = '.rrd';

	/* This version */
	$version = 'v0.1.1';

	/* The title */
	$title = "MRTS - MRTG RRDtool Total Statistics $version";






/***************************************************************************\
*                                 Functions                                 *
\***************************************************************************/



	/* Checks if a name is a valid device name */
	function validname($name)
	{
		return in_array($name, $GLOBALS['legalnames']);
	} //function validname($name)



	/* Convert a device name to a file name */
	function filename($name)
	{
		return $GLOBALS['dir'].'/'.$name.$GLOBALS['extension'];
	} //function filename($name)



	/* Formats a number with KB, MB etc. */
	function humanreadable($size)
	{
		$names = array('B', 'KB', 'MB', 'GB', 'TB');
		$times = 0;
		while($size>1024)
		{
			$size = round(($size*100)/1024)/100;
			$times++;
		}
		return "$size " . $names[$times];
	} //function humanreadable($size)



	/* Convert a month number to a month name */
	function monthname($no)
	{
		$names = array(1 => 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
		return $names[$no];
	} //function monthname($no)



	/* Convert year and month number to a string useful for rrdtool  */
	function monthstartend($year, $month)
	{
		$start = mktime(0, 0, 0, $month, 1, $year);
		if($month==12)
			$end = mktime(0, 0, 0, 1, 1, $year+1);
		else
			$end = mktime(0, 0, 0, $month+1, 1, $year);
		return " -s $start -e $end ";
	}



	/* Output HTML for a year */
	function showyear($year, $months)
	{

		$sumyear = array();
		$sumquater = array();

		printf("<h3>Year: %s</h3>\n", $year);
		printf("<table><tr><td></td>\n");


		for($quater=1; $quater<=4; $quater++)
		{
			/* month */
			for($i=($quater-1)*3+1; $i<=($quater-1)*3+3; $i++)
			{
				printf("<td class=\"datahead\"><a href=\"%s?name=%s&amp;year=%s&amp;month=%s\">%s (%s)</a></td>\n", $_SERVER['SCRIPT_NAME'], $_GET['name'], $year, $i, monthname($i), $i);
			}
			/* quater */
			printf("<td class=\"total1head\">Quater %s</td>\n", $quater);
		}
		/* year */
		printf("<td class=\"total2head\">Year</td>\n");


		printf("</tr><tr><td class=\"datahead\">In</td>\n");


		for($quater=1; $quater<=4; $quater++)
		{
			/* month */
			for($i=($quater-1)*3+1; $i<=($quater-1)*3+3; $i++)
			{
				printf("<td class=\"data\">%s</td>\n", humanreadable($months[$i]['in']));
				$sumyear['in'] += $months[$i]['in'];
				$sumquater[$quater]['in'] += $months[$i]['in'];
			}
			/* quater */
			printf("<td class=\"total1\">%s</td>\n", humanreadable($sumquater[$quater]['in']));
		}
		/* year */
		printf("<td class=\"total2\">%s</td>\n", humanreadable($sumyear['in']));


		printf("</tr><tr><td class=\"datahead\">Out</td>\n");


		for($quater=1; $quater<=4; $quater++)
		{
			/* month */
			for($i=($quater-1)*3+1; $i<=($quater-1)*3+3; $i++)
			{
				printf("<td class=\"data\">%s</td>\n", humanreadable($months[$i]['out']));
				$sumyear['out'] += $months[$i]['out'];
				$sumquater[$quater]['out'] += $months[$i]['out'];
			}
			/* quater */
			printf("<td class=\"total1\">%s</td>\n", humanreadable($sumquater[$quater]['out']));
		}
		/* year */
		printf("<td class=\"total2\">%s</td>\n", humanreadable($sumyear['out']));


		printf("</tr><tr><td class=\"datahead\">Max</td>\n");


		for($quater=1; $quater<=4; $quater++)
		{
			/* month */
			for($i=($quater-1)*3+1; $i<=($quater-1)*3+3; $i++)
			{
				printf("<td class=\"data\">%s</td>\n", humanreadable(max($months[$i]['in'], $months[$i]['out'])));
			}
			/* quater */
			printf("<td class=\"total1\">%s</td>\n", humanreadable(max($sumquater[$quater]['in'], $sumquater[$quater]['out'])));
		}
		/* year */
		printf("<td class=\"total2\">%s</td>\n", humanreadable(max($sumyear['in'], $sumyear['out'])));


		printf("</tr><tr><td class=\"datahead\">Sum</td>\n");


		for($quater=1; $quater<=4; $quater++)
		{
			/* month */
			for($i=($quater-1)*3+1; $i<=($quater-1)*3+3; $i++)
			{
				printf("<td class=\"data\">%s</td>\n", humanreadable($months[$i]['in'] + $months[$i]['out']));
			}
			/* quater */
			printf("<td class=\"total1\">%s</td>\n", humanreadable($sumquater[$quater]['in'] + $sumquater[$quater]['out']));
		}
		/* year */
		printf("<td class=\"total2\">%s</td>\n", humanreadable($sumyear['in'] + $sumyear['out']));


		printf("</tr></table>\n");


	} //function showyear($year, $months)



	/* Output HTML for a month */
	function showmonth($year, $month, $days)
	{

		$summonth = array();
		$daysinmonth = date("t", mktime(0, 0, 0, $month, 1, $year));

		printf("<h3>Month: %s %s</h3>\n", $year, monthname($month));

		for($j=1; $j<=2; $j++)
		{
			
			if($j==1)
			{
				$start = 1;
				$end = 16;
			}
			else
			{
				$start = 17;
				$end = $daysinmonth;
			}
			
			printf("<table><tr><td></td>\n");

			for($i=$start; $i<=$end; $i++)
			{
				printf("<td class=\"datahead\">%s</td>\n", $i);
			}
			if($j==2)
			{
				printf("<td class=\"total2head\">Month</td>\n");
			}

			printf("</tr><tr><td class=\"datahead\">In</td>\n");

			for($i=$start; $i<=$end; $i++)
			{
				printf("<td class=\"data\">%s</td>\n", humanreadable($days[$i]['in']));
				$summonth['in'] += $days[$i]['in'];
			}
			if($j==2)
			{
				printf("<td class=\"total2\">%s</td>\n", humanreadable($summonth['in']));
			}

			printf("</tr><tr><td class=\"datahead\">Out</td>\n");

			for($i=$start; $i<=$end; $i++)
			{
				printf("<td class=\"data\">%s</td>\n", humanreadable($days[$i]['out']));
				$summonth['out'] += $days[$i]['out'];
			}
			if($j==2)
			{
				printf("<td class=\"total2\">%s</td>\n", humanreadable($summonth['out']));
			}

			printf("</tr><tr><td class=\"datahead\">Max</td>\n");

			for($i=$start; $i<=$end; $i++)
			{
				printf("<td class=\"data\">%s</td>\n", humanreadable(max($days[$i]['in'], $days[$i]['out'])));
			}
			if($j==2)
			{
				printf("<td class=\"total2\">%s</td>\n", humanreadable(max($summonth['in'], $summonth['out'])));
			}

			printf("</tr><tr><td class=\"datahead\">Sum</td>\n");

			for($i=$start; $i<=$end; $i++)
			{
				printf("<td class=\"data\">%s</td>\n", humanreadable($days[$i]['in'] + $days[$i]['out']));
			}
			if($j==2)
			{
				printf("<td class=\"total2\">%s</td>\n", humanreadable($summonth['in'] + $summonth['out']));
			}

			printf("</tr></table>\n");
			
		} //for($j=1; $j<=2; $j++)


	} //showmonth($year, $month, $days)





/***************************************************************************\
*                                All the rest                               *
\***************************************************************************/



	/* Find legalnames */
	$legalnames = array();
	if($dirhandler = @opendir($dir))
	{
		while(($filename = readdir($dirhandler)) !== false)
		{
			if(preg_match("/\\$extension$/", $filename))
			{
				$filename = substr($filename, 0, -strlen($extension));
				if(!in_array($filename, $exclude))
				{
					$legalnames[] = $filename;
				}
			}
		}
		closedir($dirhandler);
	}





	/* If a device have been chosen */
	if(isset($_GET['name']))
	{

		/* If the device name is valid */
		if(validname($_GET['name']))
		{

			/* If the script should generate a picture */
			if(isset($_GET['picture']))
			{

				$name = filename($_GET['name']);

				header("content-type: image/png");

				$rrdcommand = "$rrdcommand graph - -v 'Bytes/s' -b 1024 -w 390 DEF:avgin=$name:ds0:AVERAGE AREA:avgin#00CC00:'Traffic in' DEF:avgout=$name:ds1:AVERAGE LINE2:avgout#0000FF:'Traffic out'";

				/* Last day */
				if($_GET['period']=='day')
				{
					$rrdcommand .= ' -t "Traffic the last day" -s -86400';
				}
				/* Last week */
				else if($_GET['period']=='week')
				{
					$rrdcommand .= ' -t "Traffic the last week" -s -604800';
				}
				/* Last month */
				else if($_GET['period']=='month')
				{
					$rrdcommand .= ' -t "Traffic the last month" -s -2678400';
				}
				/* Last year */
				else if($_GET['period']=='year')
				{
					$rrdcommand .= ' -t "Traffic the last year" -s -31622400';
				}
				/* If year and month is supplied, then generate picture for that month */
				else if(is_numeric($_GET['year']) && is_numeric($_GET['month']))
				{
					$name = monthname($_GET['month']) . ' ' . $_GET['year'];
					$rrdcommand .= " -t 'Traffic for $name' " . monthstartend($_GET['year'], $_GET['month']);
					$rrdcommand .= " -x DAY:1:WEEK:1:DAY:1:86400:%d ";
				}

				echo  `$rrdcommand`;

			} //if(isset($_GET['picture']))
			/* If year and month is supplied, then generate page for that month */
			else if(is_numeric($_GET['year']) && is_numeric($_GET['month']))
			{

				echo top($_GET['name']);

				$name = monthname($_GET['month']) . ' ' . $_GET['year'];

				printf("<img src=\"%s?name=%s&amp;year=%s&amp;month=%s&amp;picture=yes\" alt=\"%s\">", $_SERVER['SCRIPT_NAME'], $_GET['name'], $_GET['year'], $_GET['month'], $name);


				$lastdate = 0;
				$days = array();

				/* Get statistics for the selected month */
				if($fp = popen("$rrdcommand fetch " . filename($_GET['name']) . " AVERAGE -r 86400 ".monthstartend($_GET['year'], $_GET['month']), 'r'))
				{
					fgets($fp, 4096);
					while(!feof($fp))
					{
						$line = trim(fgets($fp, 4096));

						if($line != '')
						{

							list($date, $in, $out) = preg_split('/( )+/', $line);
							list($date) = explode(':', $date);
							if($lastdate != 0)
							{

								if(!is_numeric($in))
									$in = 0;
								if(!is_numeric($out))
									$out = 0;

								$in  = $in*($date-$lastdate);
								$out = $out*($date-$lastdate);

								if($_GET['month'] == date('n', $lastdate) && $_GET['year'] == date('Y', $lastdate))
								{
									$day = date('j', $lastdate);
									$days[$day]['in']  += $in;
									$days[$day]['out'] += $out;
								}

							} //if($lastdate != 0)

							$lastdate = $date;

						} //if($line != '')
					} //while(!feof($fp))

					showmonth($_GET['year'], $_GET['month'], $days);

					pclose($fp);

				} //if($fp = popen($test, 'r'))


				echo bottom();

			}
			/* Else generate main device page */
			else
			{

				echo top($_GET['name']);

				/* Find out when the database was last updated */
				if($fp = popen("$rrdcommand info " . filename($_GET['name']), 'r'))
				{
					$key = '';
					while(!feof($fp))
					{
						list($key, $value) = explode(' = ', trim(fgets($fp, 4096)));
						if($key == 'last_update')
						{
							printf("Last updated: %s<br>\n", date("Y-m-d H:i:s", $value));
							break;
						}
					}
					pclose($fp);
				}

				printf("<img src=\"%s?name=%s&amp;period=day&amp;picture=yes\" alt=\"Dayly\">", $_SERVER['SCRIPT_NAME'], $_GET['name']);
				printf("<img src=\"%s?name=%s&amp;period=week&amp;picture=yes\" alt=\"Weekly\">", $_SERVER['SCRIPT_NAME'], $_GET['name']);
				printf("<img src=\"%s?name=%s&amp;period=month&amp;picture=yes\" alt=\"Monthly\">", $_SERVER['SCRIPT_NAME'], $_GET['name']);
				printf("<img src=\"%s?name=%s&amp;period=year&amp;picture=yes\" alt=\"Yearly\">\n", $_SERVER['SCRIPT_NAME'], $_GET['name']);


				$lastdate = 0;
				$months = array();

				/* Get statistics for the last two year */
				if($fp = popen("$rrdcommand fetch " . filename($_GET['name']) . " AVERAGE -s -63331200 -e +31622400", 'r'))
				{
					fgets($fp, 4096);
					while(!feof($fp))
					{
						$line = trim(fgets($fp, 4096));
						if($line != '')
						{

							list($date, $in, $out) = preg_split('/( )+/', $line);
							list($date) = explode(':', $date);
							if($lastdate != 0)
							{

								if(!is_numeric($in))
									$in = 0;
								if(!is_numeric($out))
									$out = 0;

								$in  = $in*($date-$lastdate);
								$out = $out*($date-$lastdate);

								$year = date('Y', $lastdate);
								$month = date('n', $lastdate);
								$months[$year][$month]['in']  += $in;
								$months[$year][$month]['out'] += $out;

							} //if($lastdate != 0)

							$lastdate = $date;

						} //if($line != '')
					} //while(!feof($fp))

					$year = date('Y');
					showyear($year, $months[$year]);

					$year = date('Y')-1;
					showyear($year, $months[$year]);

					pclose($fp);

				} //if($fp = popen($test, 'r'))


				echo bottom();

			} //else


		} //if(validname($_GET['name']))
		/* If device name has been provided, but it is not valid */
		else
		{
			printf("Don't do that");
		} //else


	} //if(isset($_GET['name']))
	/* If device name has been given, show the main page */
	else
	{

		echo top('All devices');

		foreach($legalnames as $name)
		{
			printf("<a href=\"%s?name=%s\">%s</a><br>\n", $_SERVER['SCRIPT_NAME'], $name, $name);
		}

		echo bottom();

	} //else






?>
