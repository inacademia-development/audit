<html>
<head>
<style>
table {
    border-collapse: collapse;
    border: 0;
    width: 80%;
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
<a name=top><h1>Audit</h1></a>

<?php
session_start();

$MYSQL_HOST = getenv('MYSQL_HOST');
$MYSQL_USER = getenv('MYSQL_USER');
$MYSQL_PWD  = getenv('MYSQL_PWD');
$MYSQL_DB   = getenv('MYSQL_DB');

$t = isset($_GET['t'])?$_GET['t']:time();
$p = isset($_GET['p'])?$_GET['p']:"month";
if (isset($_POST['action']) and $_POST['action'] == 'clear') {
    $f = '';
} else {
    $f = isset($_POST['f'])?$_POST['f']:(isset($_SESSION['f'])?$_SESSION['f']:'');
}

echo "<a href=\"?t=" . strtotime('-1 ' . $p, $t) . "&p=$p\">previous</a> | \n";
echo "<a href=\"/audit/\">now</a> | \n";
echo "<a href=\"?t=" . strtotime('+1 ' . $p, $t) . "&p=$p\">next</a>\n";
echo "<br>\n";
echo "<a href=\"?t=$t&p=day\">day</a> | \n";
echo "<a href=\"?t=$t&p=week\">week</a> | \n";
echo "<a href=\"?t=$t&p=month\">month</a> | \n";
echo "<a href=\"?t=$t&p=year\">year</a>\n";
echo "</br></br>\n";

$date = getdate($t);
$d = $date['mday'];
$m = $date['mon'];
$y = $date['year'];
$wd = ($date['wday']+6)%7; // week starts on monday

// All days start at 00:00:00
$s = strtotime("$d-$m-$y");
$explain = "";
switch($p) {
    case 'day':
        $e = strtotime("+1 day", $s);
        $explain = date("l", $s);
        break;
    case 'week':
        $s = strtotime("-$wd days", $s);
        $e = strtotime("+1 week", $s);
        $explain = date("W", $s);
        break;
    case 'month':
        $s = strtotime("1-$m-$y", $s);
        $e = strtotime("+1 month", $s);
        $explain = date("F", $s);
        break;
    case 'year':
        $s = strtotime("1-1-$y", $s);
        $e = strtotime("+1 year", $s);
        $explain = date("Y", $s);
        break;
}
// Day ends at 23:59:59
$e -= 1;

$start = date('Y-m-d H:i:s', $s);
$end = date('Y-m-d H:i:s', $e);

echo "<form method=post>\n";
echo "<input type=text name=f value='$f'>\n";
echo "<input type=submit name=action value=filter>\n";
echo "<input type=submit name=action value=clear>\n";
echo "</form>\n";


echo "<pre>\n";
echo "p: $p ($explain)\n";
echo "d: " . date('Y-m-d', $t) . "\n";
echo "s: $start\n";
echo "e: $end\n";
echo "</pre>\n";
?>

<a href="#sessions"># unique sessions</a><br>
<a href="#idpsessions"># sessions per IdP</a><br>
<a href="#spsessions"># sessions per SP</a><br>
<a href="#spperidp"># SP's per IdP</a><br>
<a href="#idppersp"># IdP's per SP</a><br>
<a href="#domains"># domain</a><br>
<a href="#countries"># country</a><br>
<a href="#affiliations"># affiliation</a><br>
<br>
<a href="#idps"># IdP's</a><br>
<a href="#clients"># clients</a><br>
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

echo "<h1># unique sessions <a href=#top name=sessions>^</a></h1>\n";
$query  = "select count(distinct(l.log_sessionid)) c ";
$query .= "from logs l ";
$query .= "where l.log_timestamp between '$start' and '$end';";
echo querytable($query);

echo "<h1># sessions per IdP <a href=#top name=idpsessions>^</a></h1>\n";
$query  = "select count(l.log_sessionid) c, l.log_idp, ANY_VALUE(i.idp_displayname) displayname ";
$query .= "from logs l left join idps i on l.log_idp=i.idp_entityid ";
$query .= "where l.log_timestamp between '$start' and '$end' ";
$query .= "group by l.log_idp ";
$query .= "order by c desc;";
echo querytable($query);

echo "<h1># sessions per SP <a href=#top name=spsessions>^</a></h1>\n";
$query  = "select count(l.log_sessionid) c, l.log_sp ";
$query .= "from logs l ";
$query .= "where l.log_timestamp between '$start' and '$end' ";
$query .= "group by l.log_sp ";
$query .= "order by c desc;";
echo querytable($query);

echo "<h1># SP's per IdP <a href=#top name=spperidp>^</a></h1>\n";
$query  = "select count(l.log_sp) c, l.log_idp, ANY_VALUE(i.idp_displayname) displayname ";
$query .= "from logs l left join idps i on l.log_idp=i.idp_entityid ";
$query .= "where l.log_timestamp between '$start' and '$end' ";
$query .= "group by l.log_idp ";
$query .= "order by c desc;";
echo querytable($query);

echo "<h1># IdP's per SP <a href=#top name=idppersp>^</a></h1>\n";
$query  = "select count(l.log_idp) c, l.log_sp ";
$query .= "from logs l ";
$query .= "where l.log_timestamp between '$start' and '$end' ";
$query .= "group by l.log_sp ";
$query .= "order by c desc;";
echo querytable($query);

echo "<h1># domain <a href=#top name=domains>^</a></h1>\n";
$query  = "select count(l.log_sessionid) c, l.log_domain ";
$query .= "from logs l ";
$query .= "where l.log_timestamp between '$start' and '$end' ";
$query .= "group by l.log_domain ";
$query .= "order by c desc;";
echo querytable($query);

echo "<h1># country <a href=#top name=countries>^</a></h1>\n";
$query  = "select count(l.log_sessionid) c, i.idp_country ";
$query .= "from logs l left join idps i on l.log_idp=i.idp_entityid ";
$query .= "where l.log_timestamp between '$start' and '$end' ";
$query .= "group by i.idp_country ";
$query .= "order by c desc;";
echo querytable($query);

# log_affiliate | log_employee | log_member | log_faculty | log_staff | log_student
echo "<h1># affiliaton <a href=#top name=affiliations>^</a></h1>\n";
$query  =       "select count(log_affiliate) c, 'affiliate' from logs l where log_affiliate=1 ";
$query .= "and l.log_timestamp between '$start' and '$end' ";
$query .= "group by log_affiliate ";

$query .= "union select count(log_employee) c, 'employee' from logs l where log_employee=1 ";
$query .= "and l.log_timestamp between '$start' and '$end' ";
$query .= "group by log_employee ";

$query .= "union select count(log_member) c, 'member' from logs l where log_member=1 ";
$query .= "and l.log_timestamp between '$start' and '$end' ";
$query .= "group by log_member ";

$query .= "union select count(log_faculty) c, 'faculty' from logs l where log_faculty=1 ";
$query .= "and l.log_timestamp between '$start' and '$end' ";
$query .= "group by log_faculty ";

$query .= "union select count(log_staff) c, 'staff' from logs l where log_staff=1 ";
$query .= "and l.log_timestamp between '$start' and '$end' ";
$query .= "group by log_staff ";

$query .= "union select count(log_student) c, 'student' from logs l where log_student=1 ";
$query .= "and l.log_timestamp between '$start' and '$end' ";
$query .= "group by log_student ";

$query .= "order by c desc;";
echo querytable($query);

echo "<h1># IdP's <a href=#top name=idps>^</a></h1>\n";
$query = "select count(*) c from idps ";
echo querytable($query);

echo "<h1># clients <a href=#top name=clients>^</a></h1>\n";
$query = "select count(*) c from clients;";
echo querytable($query);

echo "<h1># logs <a href=#top name=logs>^</a></h1>\n";
$query = "select count(*) c from logs;";
echo querytable($query);


?>

</body>
</html>
