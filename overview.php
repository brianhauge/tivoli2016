<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 07/09/16
 * Time: 22:23
 */


setlocale(LC_ALL, "da_DK");
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});
include "config.php";

if (!$_GET['code'] == LOGCODE) die("No Access");
$db = new DbModel();

if ($_GET['section'] == "postoverview") {

    $uniqteam = $db->queryToArray("select concat(groups,id) cid, id from ".DBPREFIX."_teams order by groups, id");
    $uniqpost = $db->queryToArray("select pc.postid,group_concat(DISTINCT p.mobile SEPARATOR '<br />') mobile from ".DBPREFIX."_postcheckin_change_log pc left join ".DBPREFIX."_postcheckin p on pc.postid = p.postid GROUP BY pc.postid ORDER BY LPAD(lower(pc.postid), 10,0)");

    print("<h3>Post / Point Oversigt</h3><table class='table table-striped table-bordered'><tr><th></th>");
    foreach ($uniqpost as $post) {
        print("<th>".$post['postid'].($post['mobile'] ? "<br/><span style='font-weight: normal; font-size: 10px'>Postmandskab:<br />".$post['mobile']."</span></th>" : ""));
    }
    print("</tr>");

    foreach($uniqteam as $team) {
        print("<tr><th>".$team['cid']."</th>");
        foreach ($uniqpost as $post) {
            $s = $db->queryToArray("select action, teamid, point, postid, creator, DATE_FORMAT(updated_at, '%H:%i:%S') updated_at1 from ".DBPREFIX."_score_change_log where postid = '".$post['postid']."' and teamid = '".$team['id']."' order by updated_at desc");
            if($s[0]['point']) {
                $history = "";
                foreach ($s as $s1) {
                    $history .= $s1['updated_at1']." ".$s1['creator'].": ".$s1['point']."<br>";
                }
                $popover = "tabindex=\"0\" data-placement=\"top\" data-toggle=\"popover\" data-trigger=\"focus\" title=\"Pointhistorik\" data-html=\"true\" data-content=\"".$history."\"";
                if ($s[0]['action'] == "INSERT") print ("<td class='bg-success' style='cursor: pointer' $popover>".$s[0]['point']."</td>");
                else if ($s[0]['action'] == "UPDATE") print ("<td class='bg-warning' style='cursor: pointer' $popover>".$s[0]['point']."</td>");
                else if ($s[0]['action'] == "DELETE") print ("<td class='bg-danger' style='cursor: pointer' $popover><del>".$s[0]['point']."</del></td>");
            }
            else
            {
                print("<td></td>");
            }

        }
        print("</tr>\n");
    }
    print("</table>");
}
else if($_GET['section'] == "log") {
    // Tail Log
    print("<h3>Log fra denne server:</h3><pre style='font-size: 8px'>");
    $cmd = "tail -n50 logs/log_".date("Y-m-d").".txt";
    print(str_replace(PHP_EOL, '<br />', shell_exec($cmd)));
    print("</pre>");
}
else if($_GET['section'] == "teamoverview") {
    // Team Overview
    $sql = "select concat(t.groups,t.id) id, if(sum(s.point), sum(s.point), 0) point from ".DBPREFIX."_teams t left join ".DBPREFIX."_score s on s.teamid = t.id group by t.id order by t.groups, t.id asc";
    $result = $db->printResultTable($sql);
    print("<h3>Antal hold: ".$result['count']." - Antal deltagere: ".$db->getMemberCount()."</h3>");
    print($result['table']);
}

else if($_GET['section'] == "trace") {
    // Trace
    $sql = "select DATE_FORMAT(tstamp, '%Y-%m-%d %H:%i:%S') tid,msisdn mobil,input modtaget,output sendt from ".DBPREFIX."_trace ORDER BY tstamp desc limit 50";
    $result = $db->printResultTable($sql);
    print($result['table']);
}