<?php

namespace Ipeweb\RecapSheets\Model\Strategy;

interface InviteStrategy
{
    public function sendInvite($toId, $inviterData, $projectID);
}
