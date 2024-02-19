<?php

namespace Ipeweb\RecapSheets\Model;

use InvalidArgumentException;
use Ipeweb\RecapSheets\Model\ModelHandler;

class NewProjectData
{
    public function insert(array $data): array
    {
        if (isset($data["card"]) && isset($data["project"]) && isset($data["user"])) {
            $cardService = new CardData;
            $cardInsertData = $cardService->insert($data["card"]);

            $data["project"]["card_id"] = $cardInsertData["id"];

            $projectData = new ProjectData;
            $projectInsertData = $projectData->insert($data["project"]);

            $userProjectData = new UserProjectsData;
            $userProjectData->insert([
                "project_id" => $projectInsertData['id'],
                "user_id" => $data["user"]["id"],
                "user_permissions" => "own",
            ]);

            return $projectInsertData;
        }

        throw new InvalidArgumentException("Not enough data sent, some important information may be missing");
    }
}
