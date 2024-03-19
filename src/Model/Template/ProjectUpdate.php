<?php

namespace Ipeweb\RecapSheets\Model\Template;

use Ipeweb\RecapSheets\Bootstrap\Request;
use Ipeweb\RecapSheets\Exceptions\MissingRequiredParameterException;
use Ipeweb\RecapSheets\Model\Abstracts\CrudAbstract;
use Ipeweb\RecapSheets\Model\CardData;
use Ipeweb\RecapSheets\Model\ProjectData;
use Ipeweb\RecapSheets\Model\UserProjectsData;

class ProjectUpdate extends CrudAbstract
{
    public static array $requiredFields = ["imd"];

    public function update(array $params)
    {
        $this->validate($params);
        $params = $this->process($params);

        if (!isset($_GET['project_id'])) {
            http_response_code(400);
            throw new \Exception("Invalid given query. No 'project_id' read on request query");
        }

        $userProjectsData = new UserProjectsData();
        $result = $userProjectsData->getSearch(['user_id' => Request::$decodedToken['id'], 'project_id' => $_GET['project_id']], strict: true);

        if ($result !== [] && $result[0]['user_permissions'] !== 'guest') {
            $projectData = new ProjectData();
            $projectResult = $projectData->getSearch(['id' => $_GET['project_id']], strict: true);
            if ($projectResult !== []) {;
                $cardData = new CardData();

                http_response_code(200);
                return $cardData->update($projectResult[0]['card_id'], $this->process($params));
            }
        } elseif ($result === []) {
            http_response_code(404);
            throw new \Exception("No project found with the given id");
        }

        http_response_code(405);
        throw new \Exception("This user is not allowed to change this project");
    }

    public function delete(array $params)
    {
        $this->process($params, 'no_body');

        $userProjectsData = new UserProjectsData();
        $result = $userProjectsData->getSearch(['user_id' => Request::$decodedToken['id'], 'project_id' => $_GET['project_id']], strict: true);

        if ($result !== [] && $result[0]['user_permissions'] === 'own') {
            $projectData = new ProjectData();
            $projectResult = $projectData->getSearch(['id' => $_GET['project_id']], strict: true);
            if ($projectResult !== []) {
                http_response_code(200);
                return $projectData->inactive($projectResult[0]['id']);
            }
        } elseif ($result === []) {
            http_response_code(404);
            throw new \Exception("No project found with the given id");
        }

        http_response_code(405);
        throw new \Exception("This user is not allowed to inactive this project");
    }

    public function validate(array $params, string $args = null)
    {
        if ($args === null) {
            $missingList = [];
            foreach (self::$requiredFields as $requiredField) {
                if (!array_key_exists($requiredField, $params)) {
                    $missingList[] = $requiredField;
                }
            }

            if ($missingList !== []) {
                throw new MissingRequiredParameterException($missingList);
            }
        }

        if (!isset($_GET['project_id'])) {
            http_response_code(400);
            throw new \Exception("Invalid given query. No 'project_id' read on request query");
        }
    }

    public function prepare(array $params)
    {
        $params['imd'] = $this->storeString($params['imd']);
        $params["last_change"] = date('Y-m-d H:i:s');
        return $params;
    }

    public function storeString(string $string = null)
    {
        if ($string === null) {
            return '';
        }

        $string = str_replace("\\'", '&1qt;', $string);
        $string = str_replace("\\\"", '&2qt;', $string);
        $string = str_replace("'", '&1qt;', $string);
        $string = str_replace('"', '&2qt;', $string);
        $string = str_replace("\\n", '&nln;', $string);
        $string = str_replace("\\r", '&crt;', $string);
        $string = str_replace("\\t", '&tab;', $string);
        $string = str_replace("    ", '&tab;', $string);
        $string = str_replace("\\v", '&vtab;', $string);
        $string = str_replace("\\e", '&esc;', $string);
        $string = str_replace("\\f", '&form;', $string);
        $string = str_replace("\\\$", '&sif;', $string);
        return str_replace("\\\\", '&rbar;', $string);
    }

    public function restoreString(string $string = null)
    {
        if ($string === null) {
            return '';
        }

        $string = str_replace('&1qt;', "'", $string);
        $string = str_replace('&2qt;', '"', $string);
        $string = str_replace('&1qt;', "'", $string);
        $string = str_replace('&2qt;', '"', $string);
        $string = str_replace('&nln;', "\\n", $string);
        $string = str_replace('&crt;', "\\r", $string);
        $string = str_replace('&tab;', "    ", $string);
        $string = str_replace('&vtab;', "\\v", $string);
        $string = str_replace('&esc;', "\\e", $string);
        $string = str_replace('&form;', "\\f", $string);
        $string = str_replace('&sif;', "\\\$", $string);
        return str_replace('&rbar;', "\\\\", $string);
    }
}

/*  codes:
    &1qt; single quote '
    &2qt; double quote "
    &nln; new line
    &crt; reverse bar \
    &tab; table
    &vtab; vertical table
    &esc; escape
    &form; form feed
    &sif; dollar
    &rbar; reverse bar
*/