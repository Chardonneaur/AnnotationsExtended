<?php

namespace Piwik\Plugins\AnnotationsExtended;

use Piwik\Access;
use Piwik\Common;
use Piwik\Nonce;
use Piwik\Piwik;
use Piwik\View;
use Piwik\Plugins\Annotations\API as AnnotationsAPI;
use Piwik\Plugins\Annotations\Model as AnnotationsModel;
use Piwik\Plugins\SitesManager\API as SitesManagerAPI;

class Controller extends \Piwik\Plugin\ControllerAdmin
{
    const NONCE_NAME = 'AnnotationsExtended.write';

    public function index(): string
    {
        AnnotationsExtended::checkAccess();

        $sites = $this->getSitesWithAccess();
        $annotations = $this->getAllAnnotations($sites);

        $view = new View('@AnnotationsExtended/index');
        $this->setBasicVariablesView($view);
        $view->annotations = $annotations;
        $view->sites = $sites;
        $view->nonce = Nonce::getNonce(self::NONCE_NAME);

        return $view->render();
    }

    public function add()
    {
        header('Content-Type: application/json');

        try {
            AnnotationsExtended::checkAccess();
            Nonce::checkNonce(self::NONCE_NAME, Common::getRequestVar('nonce', '', 'string'));

            $idSite = Common::getRequestVar('idSite', 0, 'int');
            $date = Common::getRequestVar('date', '', 'string');
            $note = Common::getRequestVar('note', '', 'string');
            $starred = Common::getRequestVar('starred', '0', 'string') === '1';

            if ($idSite <= 0 || empty($date) || empty(trim($note))) {
                echo json_encode(['success' => false, 'error' => Piwik::translate('AnnotationsExtended_SiteDateNoteRequired')]);
                exit;
            }

            Piwik::checkUserHasViewAccess($idSite);

            $api = AnnotationsAPI::getInstance();
            $result = $api->add($idSite, $date, $note, $starred);

            echo json_encode(['success' => true, 'id' => $result['idNote']]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }

        exit;
    }

    public function save()
    {
        header('Content-Type: application/json');

        try {
            AnnotationsExtended::checkAccess();
            Nonce::checkNonce(self::NONCE_NAME, Common::getRequestVar('nonce', '', 'string'));

            $idSite = Common::getRequestVar('idSite', 0, 'int');
            $idNote = Common::getRequestVar('idNote', 0, 'int');
            $date = Common::getRequestVar('date', '', 'string');
            $note = Common::getRequestVar('note', '', 'string');
            $starred = Common::getRequestVar('starred', '0', 'string') === '1';

            if ($idSite <= 0 || $idNote <= 0) {
                echo json_encode(['success' => false, 'error' => Piwik::translate('AnnotationsExtended_InvalidAnnotation')]);
                exit;
            }

            $api = AnnotationsAPI::getInstance();
            $api->save($idSite, $idNote, $date ?: null, $note ?: null, $starred);

            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }

        exit;
    }

    public function delete()
    {
        header('Content-Type: application/json');

        try {
            AnnotationsExtended::checkAccess();
            Nonce::checkNonce(self::NONCE_NAME, Common::getRequestVar('nonce', '', 'string'));

            $idSite = Common::getRequestVar('idSite', 0, 'int');
            $idNote = Common::getRequestVar('idNote', 0, 'int');

            if ($idSite <= 0 || $idNote <= 0) {
                echo json_encode(['success' => false, 'error' => Piwik::translate('AnnotationsExtended_InvalidAnnotation')]);
                exit;
            }

            $api = AnnotationsAPI::getInstance();
            $api->delete($idSite, $idNote);

            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }

        exit;
    }

    public function export()
    {
        AnnotationsExtended::checkAccess();

        $format = Common::getRequestVar('format', 'csv', 'string');
        $sites = $this->getSitesWithAccess();
        $annotations = $this->getAllAnnotations($sites);

        if ($format === 'json') {
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="annotations_' . date('Y-m-d') . '.json"');
            echo json_encode($annotations, JSON_PRETTY_PRINT);
        } else {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="annotations_' . date('Y-m-d') . '.csv"');

            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID', 'Site ID', 'Site Name', 'Date', 'Note', 'Starred', 'User', 'Can Edit']);

            foreach ($annotations as $annotation) {
                fputcsv($output, [
                    $annotation['id'],
                    $annotation['idsite'],
                    $annotation['site_name'],
                    $annotation['date'],
                    $annotation['note'],
                    $annotation['starred'] ? 'Yes' : 'No',
                    $annotation['user'],
                    $annotation['canEdit'] ? 'Yes' : 'No',
                ]);
            }

            fclose($output);
        }

        exit;
    }

    private function getSitesWithAccess(): array
    {
        $sites = [];

        if (Piwik::hasUserSuperUserAccess()) {
            $allSites = SitesManagerAPI::getInstance()->getAllSites();
            foreach ($allSites as $site) {
                $sites[$site['idsite']] = $site['name'];
            }
        } else {
            $siteIds = Access::getInstance()->getSitesIdWithAtLeastViewAccess();
            foreach ($siteIds as $idSite) {
                $site = SitesManagerAPI::getInstance()->getSiteFromId($idSite);
                $sites[$idSite] = $site['name'];
            }
        }

        return $sites;
    }

    private function getAllAnnotations(array $sites): array
    {
        $annotations = [];
        $model = new AnnotationsModel();
        $currentUser = Piwik::getCurrentUserLogin();
        $isSuperUser = Piwik::hasUserSuperUserAccess();

        foreach (array_keys($sites) as $idSite) {
            try {
                $siteAnnotations = $model->getAllAnnotationsForSiteInRange($idSite, null, null);

                foreach ($siteAnnotations as $annotation) {
                    $canEdit = $isSuperUser ||
                               Piwik::isUserHasAdminAccess($idSite) ||
                               $annotation['user'] === $currentUser;

                    $annotations[] = [
                        'id' => $annotation['id'],
                        'idsite' => $idSite,
                        'site_name' => $sites[$idSite],
                        'date' => substr($annotation['date'], 0, 10),
                        'note' => $annotation['note'],
                        'starred' => (int) $annotation['starred'] === 1,
                        'user' => $annotation['user'],
                        'canEdit' => $canEdit,
                    ];
                }
            } catch (\Exception $e) {
                // Skip sites with errors
            }
        }

        usort($annotations, function ($a, $b) {
            return strcmp($b['date'], $a['date']);
        });

        return $annotations;
    }
}
