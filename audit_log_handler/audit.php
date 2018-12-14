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
include('queries.php');
session_start();

$t = isset($_GET['t'])?$_GET['t']:time();
$p = isset($_GET['p'])?$_GET['p']:"month";
if (isset($_POST['action']) and $_POST['action'] == 'clear') {
    $filter = '';
} else {
    $filter = isset($_POST['f'])?$_POST['f']:(isset($_SESSION['f'])?$_SESSION['f']:'');
}
$_SESSION['f'] = $filter;

echo "<a href=\"?t=" . strtotime('-1 ' . $p, $t) . "&p=$p\">previous</a> | \n";
echo "<a href=\"/audit/\">now</a> | \n";
echo "<a href=\"?t=" . strtotime('+1 ' . $p, $t) . "&p=$p\">next</a>\n";
echo "<br>\n";
echo "<a href=\"?t=$t&p=day\">day</a> | \n";
echo "<a href=\"?t=$t&p=week\">week</a> | \n";
echo "<a href=\"?t=$t&p=month\">month</a> | \n";
echo "<a href=\"?t=$t&p=year\">year</a> | \n";
echo "<a href=\"/summary?t=$t&p=" . (in_array($p, array('year', 'month'))?$p:'month') . "\">summary</a>\n";
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
echo "<input type=text name=f value='$filter'>\n";
echo "<input type=submit name=action value=filter>\n";
echo "<input type=submit name=action value=clear>\n";
echo "</form>\n";


echo "<pre>\n";
echo "p: $p ($explain)\n";
echo "d: " . date('Y-m-d', $t) . "\n";
echo "s: $start\n";
echo "e: $end\n";
echo "f: $filter\n";
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
function table($res) {
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
            $ret .= "<td style='border-left: 1px solid; padding: 0px 4px; margin: 0px;'>" . $column . "</td>";
        }
        $ret .= "</tr>\n";
    }
    $ret .= "</table>\n";
    return $ret;
}

echo "<h1># unique sessions <a href=#top name=sessions>^</a></h1>\n";
echo table(get_sessions($start, $end));

echo "<h1># sessions per IdP <a href=#top name=idpsessions>^</a></h1>\n";
echo table(get_idpsessions($start, $end, $filter));

echo "<h1># sessions per SP <a href=#top name=spsessions>^</a></h1>\n";
echo table(get_spsessions($start, $end, $filter));

echo "<h1># SP's per IdP <a href=#top name=spperidp>^</a></h1>\n";
echo table(get_spperidp($start, $end, $filter));

echo "<h1># IdP's per SP <a href=#top name=idppersp>^</a></h1>\n";
echo table(get_idppersp($start, $end, $filter));

echo "<h1># domain <a href=#top name=domains>^</a></h1>\n";
echo table(get_domains($start, $end, $filter));

echo "<h1># country <a href=#top name=countries>^</a></h1>\n";
echo table(get_countries($start, $end, $filter));

# log_affiliate | log_employee | log_member | log_faculty | log_staff | log_student
echo "<h1># affiliaton <a href=#top name=affiliations>^</a></h1>\n";
echo table(get_affiliations($start, $end));

echo "<h1># IdP's <a href=#top name=idps>^</a></h1>\n";
echo table(get_idps());

echo "<h1># clients <a href=#top name=clients>^</a></h1>\n";
echo table(get_clients());

echo "<h1># logs <a href=#top name=logs>^</a></h1>\n";
echo table(get_logs());


?>

</body>
</html>
