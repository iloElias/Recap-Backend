<?php

namespace Ipeweb\RecapSheets\Controller;

use Ipeweb\RecapSheets\Bootstrap\Request;
use Ipeweb\RecapSheets\Exceptions\MissingRequiredParameterException;
use Ipeweb\RecapSheets\Model\CardData;
use Ipeweb\RecapSheets\Model\NewProjectData;
use Ipeweb\RecapSheets\Model\ProjectData;
use Ipeweb\RecapSheets\Model\QueryGet;
use Ipeweb\RecapSheets\Model\Template\ProjectUpdate;
use Ipeweb\RecapSheets\Model\UserProjectsData;

class ProjectController
{
    public static function getUserProjects()
    {
        $query = QueryGet::getQueryItems(["field" => true]);

        $preparedParams = [];
        if (str_contains($query['field'], ':')) {
            $preparedParams = explode(':', $query['field']);
            if (isset($preparedParams[0]) && isset($preparedParams[1])) {
                $userProjectsData = new UserProjectsData();

                try {
                    http_response_code(200);
                    return $userProjectsData->get([$preparedParams[0] => $preparedParams[1]]);
                } catch (\Throwable) {
                    http_response_code(500);
                    throw new \Exception("Something went wrong on getting a user");
                }
            }
        }

        http_response_code(400);
        throw new \InvalidArgumentException('Invalid given "field" value. No key or value detected');
    }

    public static function postNewProject()
    {
        try {
            $newProjectData = new NewProjectData();
            http_response_code(201);
            return $newProjectData->insert(Request::$request['body']);
        } catch (\Throwable $throwable) {
            http_response_code(500);
            throw new \Exception('Was not possible to create a new project: ' . $throwable->getMessage(), $throwable->getCode(), $throwable);
        }
    }

    public static function updateProjectMd()
    {
        $projectUpdate = new ProjectUpdate();

        try {
            return $projectUpdate->update(Request::$request['body']);
        } catch (MissingRequiredParameterException $missE) {
            http_response_code(400);
            throw new \InvalidArgumentException('Some of the required params was not supplied: ' . $missE->getMessage(), $missE->getCode(), $missE);
        } catch (\Throwable $e) {
            http_response_code(500);
            throw new \Exception("Something went wrong on updating the project:" . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public static function inactivateProject()
    {
        $projectUpdate = new ProjectUpdate();

        try {
            return $projectUpdate->delete(Request::$request['body']);
        } catch (MissingRequiredParameterException $missE) {
            http_response_code(400);
            throw new \InvalidArgumentException('Some of the required params was not supplied: ' . $missE->getMessage(), $missE->getCode(), $missE);
        } catch (\Throwable $e) {
            http_response_code(500);
            throw new \Exception("Something went wrong on inactivating project:" . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public static function getProjectMarkdown()
    {
        $requestToken = Request::$decodedToken;
        $query = QueryGet::getQueryItems(["project_id" => true]);

        try {

            $userProjectsData = new UserProjectsData();
            $userProjectResult = $userProjectsData->getSearch(['user_id' => $requestToken['id'], 'project_id' => $query['project_id']], strict: true);

            if ($userProjectResult !== []) {
                $projectService = new ProjectData();
                $projectResult = $projectService->getSearch(['id' => $query['project_id']], strict: true);
                if ($projectResult !== []) {
                    if ($projectResult[0]['state'] === 'archived') {
                        http_response_code(400);
                        throw new \Exception('Cannot get markdown from an inactivated project');
                    }

                    $cardData = new CardData();
                    $cardResult = $cardData->getSearch(['id' => $projectResult[0]['card_id']], strict: true);
                    $projectUpdate = new ProjectUpdate();

                    $cardResult[0]['user_permissions'] = $userProjectResult[0]['user_permissions'];
                    $cardResult[0]['name'] = $projectResult[0]['name'];
                    $cardResult[0]['imd'] = $projectUpdate->restoreString($cardResult[0]['imd']) ?? "";

                    http_response_code(200);
                    return $cardResult;
                }
            } elseif ($userProjectResult === []) {
                $projectService = new ProjectData();
                $projectResult = $projectService->getSearch(['id' => $query['project_id']], strict: true);
                if ($projectResult !== []) {
                    http_response_code(403);
                    throw new \Exception("User not invited");
                }
                http_response_code(404);
                throw new \Exception('Cannot get markdown from an inactivated project');
            }

            http_response_code(403);
            throw new \Exception("This user is not invited to this project");
        } catch (MissingRequiredParameterException $missE) {
            http_response_code(400);

            throw new \InvalidArgumentException('Some of the required data is missing: ' . $missE->getMessage(), $missE->getCode(), $missE);
        } catch (\Throwable $e) {
            http_response_code(500);

            throw new \Exception("Something went wrong on getting the project markdown: " . $e->getMessage(), $e->getCode(), $e);
        }
    }
}