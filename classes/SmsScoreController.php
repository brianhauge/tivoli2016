<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 10/08/16
 * Time: 16:47
 */
class SmsScoreController extends BaseInit
{
    var $smsSender;
    var $dbModel;
    public function __construct()
    {
        parent::__construct();
        $this->smsSender = new SendSmsModel();
        $this->dbModel = new DbModel();
    }

    public function handleReceivedPoints(SmsScoreModel $smsModel) {
        if (!$smsModel->getPost()) {
            $message = "Du er ikke tjekket ind på en post. Send 'tjek p xx' for at tjekke ind.".SMS_HELPTEXT;
            $this->smsSender->sendSms($smsModel->getMsisdn(),$message);
            $this->dbModel->insertTrace($smsModel->getMsisdn(),$smsModel->getSmscontent(),$message);
        }
        elseif(!$smsModel->getTeam()) {
            $message = "'h' (for hold-id) ikke fundet i beskeden eller dens værdi er ugyldig (ex H12).".SMS_HELPTEXT;
            $this->smsSender->sendSms($smsModel->getMsisdn(),$message);
            $this->dbModel->insertTrace($smsModel->getMsisdn(),$smsModel->getSmscontent(),$message);
        }
        elseif (!$smsModel->getPoint()) {
            $message = "'p' (for point) ikke fundet i beskeden eller dens værdi er ugyldig (ex p50).".SMS_HELPTEXT;
            $this->smsSender->sendSms($smsModel->getMsisdn(),$message);
            $this->dbModel->insertTrace($smsModel->getMsisdn(),$smsModel->getSmscontent(),$message);
        }
        else {
            // Insert Score
            $this->dbModel->insertScore($smsModel->getTeam(),$smsModel->getPoint(),$smsModel->getPost(),$smsModel->getMsisdn());

            // Send status to $sender
            if(NOTIFS == true) {
                $message = $smsModel->getPoint()." point givet til hold ".$smsModel->getTeam()." på post ".$smsModel->getPost().". Holdet har nu ".$this->dbModel->getTeamPoints($smsModel->getTeam())." point.";
                $this->smsSender->sendSms($smsModel->getMsisdn(),$message);
                $this->dbModel->insertTrace($smsModel->getMsisdn(),$smsModel->getSmscontent(),$message);
            }
            else {
                $this->dbModel->insertTrace($smsModel->getMsisdn(),$smsModel->getSmscontent(),"N/A (Notifs disabled)");
            }
        }



    }
    
    public function getScoreTableByGroup($group) {
        $scoreTable = "<table class=\"table table-striped\"><thead><tr><th>Placering</th><th>ID</th><th>Holdnavn</th><th>Point</th></tr></thead><tbody>";
        $counter = 1;
        foreach ($this->dbModel->getScore($group) as $score) {
            $scoreTable .= "<tr>";
            $scoreTable .= "<th width='10%'>$counter</th>";
            $scoreTable .= "<td width='10%'>" . $score['cid'] . "</td>";
            $scoreTable .= "<td width='70%'>" . $score['team'] . "</td>";
            $scoreTable .= "<td><span class=\"badge\">" . $score['point'] . "</span></td>";
            $scoreTable .= "</tr>";
            $counter++;
        }
        $scoreTable .= "</tbody>";
        $scoreTable .= "</table>";
        //$this->logger->info(__METHOD__.": $group");
        return $scoreTable;
    }
}
