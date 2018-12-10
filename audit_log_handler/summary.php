<html>
<head>
<style>
table {
    border-collapse: collapse;
    border: 0;
/*     width: 80%; */
    box-shadow: 1px 2px 3px #ccc;
}
td, th {
    border: 1px solid #666;
    font-size: 75%;
    vertical-align: baseline;
    padding: 4px 5px;
}
h1 {
    margin-bottom: 0px;
}
a {
    text-decoration: none;
}
pre {
    font-size: 1.4em;
    font-weight: bold;
}
</style>
<head>
<body>
<a name=top><h1>Summary</h1></a>
<?php

$MYSQL_HOST = getenv('MYSQL_HOST');
$MYSQL_USER = getenv('MYSQL_USER');
$MYSQL_PWD  = getenv('MYSQL_PWD');
$MYSQL_DB   = getenv('MYSQL_DB');

$t = isset($_GET['t'])?$_GET['t']:time();
$p = 'year';

echo "<a href=\"?t=" . strtotime('-1 ' . $p, $t) . "&p=$p\">previous</a> | \n";
echo "<a href=\"/summary/\">now</a> | \n";
echo "<a href=\"?t=" . strtotime('+1 ' . $p, $t) . "&p=$p\">next</a>\n";
echo "</br></br>\n";

$date = getdate($t);
$d = $date['mday'];
$m = $date['mon'];
$y = $date['year'];
$wd = ($date['wday']+6)%7; // week starts on monday

// All days start at 00:00:00
$s = strtotime("$d-$m-$y");
$explain = "";
$s = strtotime("1-1-$y", $s);
$e = strtotime("+1 year", $s);
$explain = date("Y", $s);
// Day ends at 23:59:59
$e -= 1;

$start = date('Y-m-d H:i:s', $s);
$end = date('Y-m-d H:i:s', $e);

echo "<pre>\n";
echo "p: $p ($explain)\n";
echo "d: " . date('Y-m-d', $t) . "\n";
echo "s: $start\n";
echo "e: $end\n";
echo "</pre>\n";
?>

<a href="#logs"># logs</a><br>

<?php
$mysqli = new mysqli($MYSQL_HOST, $MYSQL_USER, $MYSQL_PWD, $MYSQL_DB);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
} else {
//     echo $mysqli->host_info . "\n";
}


function querytable($query) {
    global $mysqli;
    $res = $mysqli->query($query);
    if (!$res) {
        return $mysqli->error;
    }
    $res->data_seek(0);

    $ret = "<table>\n";
    $header = false;
    while ($row = $res->fetch_assoc()) {
        if (!$header) {
            $ret .= "<tr>\n";
            foreach(array_keys($row) as $name) {
                $ret .= "<td><b>" . $name . "</b></td>\n";
            }
            $ret .= "</tr>\n";
            $header = true;
        }
        $ret .= "<tr>\n";
        foreach($row as $column) {
            $ret .= "<td style='border-left: 1px solid; padding: 0px 4px; margin: 0px;'>" . $column . "</td>\n";
        }
        $ret .= "</tr>\n";
    }
    $ret .= "</table>\n";
    return $ret;
}



echo "<h1># logs <a href=#top name=logs>^</a></h1>\n";
$query  = "select count(log_timestamp) c, year(log_timestamp) y, monthname(log_timestamp) m, day(log_timestamp) d from logs ";
$query .= "where log_timestamp between '$start' and '$end' ";
$query .= "group by d,m,y;";
echo querytable($query);
