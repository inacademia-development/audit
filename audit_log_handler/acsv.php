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

function csv_table($res) {
    $res->data_seek(0);

    $ret = "";
    $header = false;
    while ($row = $res->fetch_assoc()) {
        if (!$header) {
            $ret = implode(",", array_keys($row)) . "\n";
            $header = true;
        }
        foreach($row as $column) {
            $ret .= $column . ",";
        }
        $ret .= "\n";
    }
    return $ret;
}

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
        $explain = "$y-$m-${d}_InAcademia_" . date("l", $s);
        break;
    case 'week':
        $s = strtotime("-$wd days", $s);
        $e = strtotime("+1 week", $s);
        $explain = "$y-$m-${d}_InAcademia_w" . date("W", $s);
        break;
    case 'month':
        $s = strtotime("1-$m-$y", $s);
        $e = strtotime("+1 month", $s);
        $explain = "$y-${m}_InAcademia_" . date("F", $s);
        break;
    case 'year':
        $s = strtotime("1-1-$y", $s);
        $e = strtotime("+1 year", $s);
        $explain = "${y}_InAcademia_" . date("Y", $s);
        break;
}
// Day ends at 23:59:59
$e -= 1;

$start = date('Y-m-d H:i:s', $s);
$end = date('Y-m-d H:i:s', $e);

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=$explain.csv");
header("Pragma: no-cache");
header("Expires: 0");

echo "Audit\n";
echo "p,$p ($explain)\n";
echo "d," . date('Y-m-d', $t) . "\n";
echo "s,$start\n";
echo "e,$end\n";
echo "f,$filter\n";

echo "# unique sessions\n";
echo csv_table(get_sessions($start, $end));

echo "# sessions per IdP\n";
echo csv_table(get_idpsessions($start, $end, $filter));

echo "# sessions per SP\n";
echo csv_table(get_spsessions($start, $end, $filter));

echo "# SP's per IdP\n";
echo csv_table(get_spperidp($start, $end, $filter));

echo "# IdP's per SP\n";
echo csv_table(get_idppersp($start, $end, $filter));

echo "# domain\n";
echo csv_table(get_domains($start, $end, $filter));

echo "# country\n";
echo csv_table(get_countries($start, $end, $filter));

echo "# affiliatons\n";
echo csv_table(get_affiliations($start, $end));

echo "# IdPs\n";
echo csv_table(get_idps());

echo "# clients\n";
echo csv_table(get_clients());

echo "# logs\n";
echo csv_table(get_logs());
