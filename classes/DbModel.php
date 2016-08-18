<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 09/08/16
 * Time: 18:23
 */
class DbModel extends BaseInit
{

    /**
     * DbModel constructor.
     */
    private $con;
    public function __construct()
    {
        parent::__construct();
        $this->con = mysqli_connect(DBHOST, DBUSER, DBPASS, DB) or die("Error " . mysqli_error($this->con));
        // Check connection
        if (mysqli_connect_errno())
        {
            $this->logger("Failed to connect to MySQL: " . mysqli_connect_error());
            die("DB issue");
        }
    }

    public function insertTeam($name, $leader, $mobile, $email, $kreds, $group) {
        $this->logger->info(__CLASS__." > ".__FUNCTION__.": "."Log create team: $name, $mobile, $email, $kreds, $group");
        $this->con->query("INSERT INTO tivoli2016_teams (name, leader, mobile, email, kreds, groups, updated_at) VALUES ('$name', '$leader', '$mobile', '$email', '$kreds', '$group', now())");
        return $this->con->insert_id;

    }

    public function insertScore($team, $point, $post, $creator) {
        $this->logger->info(__CLASS__." > ".__FUNCTION__.": "."Log point: $team, $point, $post, $creator");
        $this->con->query("INSERT INTO tivoli2016_score (teamid, point, postid, creator, updated_at) VALUES ('$team', '$point', '$post', '$creator', now()) ON DUPLICATE KEY UPDATE point = '$point'");
    }

    public function insertCheckin($postid, $sender) {
        $this->logger->info(__CLASS__." > ".__FUNCTION__.": "."Log check-in: $postid, $sender");
        $this->con->query("INSERT INTO tivoli2016_postcheckin (mobile, postid, updated_at) VALUES ('$sender', '$postid', now()) ON DUPLICATE KEY UPDATE postid = '$postid' ");
    }

    public function getScore($group) {
        $score = array();
        if ($result = $this->con->query("select concat(t.id, \". \", t.name) team, if(sum(s.point), sum(s.point), 0) point from tivoli2016_teams t left join tivoli2016_score s on s.teamid = t.id where t.groups = '$group' group by t.id order by point desc")) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $score[] = $row;
            }
        }
        $this->logger->info(__CLASS__." > ".__FUNCTION__.": ".$score);
        return $score;
    }

    public function getCheckedinPost($sender) {
        $postid = 0;
        if ($result = $this->con->query("select postid from tivoli2016_postcheckin where mobile = '$sender'")) {
            $row = mysqli_fetch_assoc($result);
            $postid = $row['postid'];
        }
        $this->logger->info(__CLASS__." > ".__FUNCTION__.": ".$postid);
        return $postid;
    }

    public function getTeamPoints($team) {
        $postid = 0;
        if ($result = $this->con->query("select sum(point) point from tivoli2016_score where teamid = '$team'")) {
            $row = mysqli_fetch_assoc($result);
            $postid = $row['point'];
        }
        $this->logger->info(__CLASS__." > ".__FUNCTION__.": ".$postid);
        return $postid;
    }


    public function __destruct()
    {
        $this->con->close();
    }
}