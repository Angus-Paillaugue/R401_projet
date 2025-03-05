<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../../../lib/connector.php';
require_once __DIR__ . '/../../../../lib/api.php';
require_once __DIR__ . '/../../../../modele/Joueur.php';
require_once __DIR__ . '/../../../../modele/RencontreDAO.php';
require_once __DIR__ . '/../../authenticate.php';

authenticate_request();

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    try {
      $DAO = new RencontreDAO();
      $stats = $DAO->getStatistics();

      API::deliver_response(200, 'Stats retrieved', $stats);
    } catch (Exception $e) {
      API::deliver_response(500, 'An error occurred', [
        'message' => $e->getMessage(),
      ]);
    }
    break;
  default:
    API::deliver_response(405, 'Method not allowed');
    break;
}
?>
