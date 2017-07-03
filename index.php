<?php

require_once( __DIR__.'/random.php' );
$random = new random();

if( isset($_GET['run']) ) {
   $run = (int)$_GET['run'];
   if( !$run || !is_int($run) || $run > 1000 ) { $run = 1; }
   $random->add_more_random( $run );
}

if( isset($_GET['restart']) ) {
    $restart = (int)$_GET['restart'];
    if( !$restart || !is_int($restart) || $restart > 100000 || $restart < 1 ) { 
        $restart = $random->default_table_size;
    }
    $random->delete_test_table();
    $random->create_test_table( $restart );
}
?>


<!doctype html>
<html><head><title>Testing SQLite ORDER BY RANDOM()</title>
<meta charset="utf-8" />
<meta name="viewport" content="initial-scale=1" />
<style>
body {
   background-color:white; 
   color:black; 
   margin:10px 20px 20px 20px; 
   font-family:sans-serif,helvetica,arial;
}
h1 { font-size:150%; margin:0px 0px 5px 0px; padding:0px; }
h2 { font-size:95%; font-weight:normal; margin:0px; padding:0px; }
a { text-decoration:none; color:darkblue; background-color:#e8edd3; }
a:visited { color:darkblue; background-color:#e8edd3; }
a:hover { background-color:yellow; color:black; }
ul { margin:0px; }
.results {
   white-space:pre;
   font-family:monospace;
   display:inline-block;
}
.datah {
   display:inline-block;
   background-color:lightgrey;
   color:black;
   margin:0px;
   font-weight:bold;
}
.datab {
   display:inline-block;
   background-color:salmon;
   color:black;
   padding-left:10px
   padding-right:10px;
   margin-bottom:1px;
   font-weight:bold;
}
.notice {
   background-color:lightyellow;
   font-size:90%;
   border:1px solid black;
   padding:2px;
}
.pre { white-space:pre; font-family:monospace; font-size:120%; }
</style>
</head><body><a name="top"></a>
<div style="float:right;"><a href="./#about">&nbsp;About&nbsp;</a> &nbsp; <a href="./">&nbsp;Refresh&nbsp;</a></div>
<h1>Testing SQLite ORDER BY RANDOM()</h1>
<div class="results"><?php 

$distribution_chart = $random->display_distribution();
$info = $random->get_test_table_info();

print '<span style="font-size:130%; font-weight:bold;"><a href="./?run=1"
> +1  </a> <a href="./?run=10"
> +10  </a> <a href="./?run=25"
> +25  </a> <a href="./?run=50"
> +50  </a> <a href="./?run=100"
> +100</a> <a href="./?run=500"
> +500</a> <a href="./?run=1000"
>+1000</a></span><br />'
. '<br />Table size      : <b>' . number_format(@$info['class_size']) . ' rows</b>'
. '<br /># Data points   : <b>' . number_format(@$info['data_sum']) . '</b>'
. '<br /># Frequencies   : <b>' . number_format($random->frequencies_count) . '</b>'
. '<br />Freq Hi/Lo/Range: <b>' 
   . number_format($random->highest_frequency) 
   . ' / ' . number_format($random->lowest_frequency) 
   . ' / ' . number_format( $random->highest_frequency - $random->lowest_frequency )
   . '</b>'
. '<br />Freqs Average   : <b>' . $random->frequencies_average . '</b>'
. '<br />Rows Hi/Lo/Range: <b>' 
   . number_format($random->highest_count) 
   . ' / ' . number_format($random->lowest_count) 
   . ' / ' . number_format( $random->highest_count - $random->lowest_count )
   . '</b>'
. '<br />Rows Average    : <b>' . $random->count_average . '</b>'

. '<br /><br />' 
. $distribution_chart
;


$get_data  = isset($random->timer['get_data'])  ? number_format($random->timer['get_data'],  10) : '0';
$save_data = isset($random->timer['save_data']) ? number_format($random->timer['save_data'], 10) : '0';
$run = isset($run) ? $run : '0';
?>

SQL Test count: <?php print $run; ?> runs
Avg per SQL   : <?php 
	if( isset($run) && $run > 0 ) {
		print number_format( ($get_data / $run), 10);
	} else {
		print '0';
	}
?> seconds
SQL Test time : <?php print $get_data; ?> seconds
Data Save time: <?php print $save_data; ?> seconds

</div>

<p><hr /></p>

<div class="pre">Restart test with: 
<a 
href="?restart=2"> 2 </a> <a 
href="?restart=5"> 5 </a> <a 
href="?restart=10"> 10</a> <a 
href="?restart=25"> 25</a> <a 
href="?restart=50"> 50</a> <a 
href="?restart=100"> 100</a> <a 
href="?restart=250"> 250</a> <a 
href="?restart=500"> 500</a> <a 
href="?restart=1000"> 1,000</a> <a 
href="?restart=10000"> 10,000</a> <a 
href="?restart=100000"> 100,000</a> rows
</div>


<a name="about"></a>
<p><hr /></p>

<h3>About Getting Random with SQLite</h3>
<p>This page tests the randomness of the ORDER BY RANDOM() functionality in SQLite.</p>

<p>The test table is defined as:</p>
<span class="pre"><?php print $random->table[1]; ?> </span>

<p>Add more test data by clicking a <span style="background-color:#e8edd3;">&nbsp;+&nbsp;</span> number button above.</p>

<p>Each data point is individually generated via the SQL call:</p>
<span class="pre"><?php print $random->method[1]; ?> </span>

<p>A <em>Frequency of Frequencies</em> chart displays:
<ul>
<li>Frequency: the list of unique frequencies (the number of times a row was randomly selected)
<li>Rows: the number of rows for each frequency present</li>
</ul>
</p>

<p>This site was created with Open Source software.</p>
<p>Find out more on Github: <a href="https://github.com/attogram/random-sqlite-test">random-sqlite-test v<?php print __RST__; ?></a></p>


<footer>
<p><hr /></p>
<p><a href="#top">Back to top</a></p>
<p>SQL count: <?php print $random->sql_count; ?></p>
<p>Hosted by: <a href="//<?php print $_SERVER['SERVER_NAME']; ?>/"><?php print $_SERVER['SERVER_NAME']; ?></a></p>
<p>Page generated in <?php 
	$random->end_timer('page');
	print number_format($random->timer['page'], 10); ?> seconds</p>
</footer>
</body>
</html>

