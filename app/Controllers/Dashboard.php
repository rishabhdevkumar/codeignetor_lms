<?php

namespace App\Controllers;

use App\Models\Crud_Model;
use App\Models\IndentAllotment\IndentAllotmentModel;
use App\Models\ProductionPlanning\PlanningProductionModel;
use App\Models\ProductionPlanning\PpMachineAvailabilityModel;
use CodeIgniter\Controller;

class Dashboard extends BaseController
{
	protected $session;
	protected $crudModel;
	protected $indentAllotmentModel;
	protected $planningProductionModel;
	protected $availabilityModel;
	protected $helpers = ['url', 'form', 'security'];

	public function __construct()
	{
		// Load services
		$this->session        = session();
		$this->crudModel      = new Crud_Model();
		$this->indentAllotmentModel = new IndentAllotmentModel();
		$this->planningProductionModel = new PlanningProductionModel();
		$this->availabilityModel = new PpMachineAvailabilityModel();

		date_default_timezone_set('Asia/Calcutta');
	}


	public function index()
	{

		// if ($this->session->get('erp_user_id')) {

		// $arr = array("PP_ID" => 1);
		// $result["settings"] = $this->crudModel->select("pp_settings", $arr, "PP_ID", "ASC");

		// $user_id = $this->session->get('erp_user_id');

		// $arr = array("PP_ID" => $user_id);
		// $result["user_details"] = $this->crudModel->select("pp_users_master", $arr, "PP_ID", "ASC");

		$tomorrowStart = date('Y-m-d 00:00:00', strtotime('+1 day'));
		$tomorrowEnd   = date('Y-m-d 23:59:59', strtotime('+1 day'));

		$totalPlanning = $this->planningProductionModel
			->select("
					SUM(BALANCE_QTY) as totalBalance,
					SUM(UTILISED_QTY) as totalUtilized,
					SUM(QTY_MT) as totalScheduled
				")
			->where('FROM_DATE_TIME >=', $tomorrowStart)
			->where('FROM_DATE_TIME <=', $tomorrowEnd)
			->groupBy('MACHINE')
			->get()
			->getRow();

		$totalBalance   = $totalPlanning->totalBalance   ?? 0;
		$totalUtilized  = $totalPlanning->totalUtilized  ?? 0;
		$totalScheduled = $totalPlanning->totalScheduled ?? 0;

		$result = [
			'title'          => 'Dashboard',
			'totalScheduled' => $totalScheduled,
			'totalBalance'   => $totalBalance,
			'totalUtilized'  => $totalUtilized
		];

		$monthStart = date('Y-m-01 00:00:00');
		$monthEnd   = date('Y-m-d 23:59:59', strtotime(date('Y-m-t')));

		$result['machinePlanning'] = $this->planningProductionModel
			->select("
			        MACHINE_TPM_ID,
					SUM(BALANCE_QTY) as Balance,
					SUM(UTILISED_QTY) as Utilized,
					SUM(QTY_MT) as Scheduled
				")
			->join(
				'pp_machine_master m',
				'm.PP_ID = pp_production_planning_master.MACHINE',
				'inner'
			)
			->where('FROM_DATE_TIME >=', $monthStart)
			->where('FROM_DATE_TIME <=', $monthEnd)
			->groupBy('MACHINE')
			->orderBy('MACHINE', 'ASC')
			->findAll();

		$result['gradePlanning'] = $this->planningProductionModel
			->select("
        m.MACHINE_TPM_ID,
        mr.GRADE,
        SUM(pp_production_planning_master.BALANCE_QTY) as totalBalance,
        SUM(pp_production_planning_master.UTILISED_QTY) as totalUtilized,
        SUM(pp_production_planning_master.QTY_MT) as totalScheduled
        ")
			->join(
				'pp_machine_master m',
				'm.PP_ID = pp_production_planning_master.MACHINE',
				'inner'
			)
			->join(
				'pp_mr_material_master mr',
				'mr.MR_MATERIAL_CODE = pp_production_planning_master.SAP_MR_FG_CODE',
				'inner'
			)
			->where('FROM_DATE_TIME >=', $monthStart)
			->where('FROM_DATE_TIME <=', $monthEnd)
			->groupBy(['m.MACHINE_TPM_ID', 'mr.GRADE'])
			->orderBy('mr.GRADE', 'ASC')
			->findAll();

		// echo "<pre>";
		// print_r($result);
		// echo "</pre>";
		// exit;

		$result['indentallotment'] = $this->indentAllotmentModel
			->select([
				'pp_indent_allotment.INDENT_NO',
				'pp_indent_allotment.QUANTITY',
				'pp_indent_allotment.REMARKS',
				'c.cust_name'
			])
			->join(
				'vtiger_bp_placed_order_header h',
				'h.in_no = pp_indent_allotment.INDENT_NO',
				'inner'
			)
			->join(
				'vtiger_bp_customer_master c',
				'c.cust_no = h.bill_to_code AND c.parent_cust_no = h.bill_to_code',
				'inner'
			)
			->where('pp_indent_allotment.FULFILLMENT_FLAG', 0)
			->orderBy('pp_indent_allotment.PP_ID', 'ASC')
			->findAll();

		$currentDateTime = date('Y-m-d H:i:s');

		$db = \Config\Database::connect();

		$result['downtime'] = $db->query("
			SELECT a.*
			FROM pp_machine_availability a
			INNER JOIN (
				SELECT MACHINE_TPM_ID, MIN(FROM_DATE) as next_date
				FROM pp_machine_availability
				WHERE FROM_DATE > ?
				GROUP BY MACHINE_TPM_ID
			) t
			ON a.MACHINE_TPM_ID = t.MACHINE_TPM_ID
			AND a.FROM_DATE = t.next_date
			ORDER BY a.FROM_DATE ASC
		", [$currentDateTime])->getResultArray();

		echo view('header', $result);
		echo view('dashboard/dashboard_view', $result);
		echo view('footer');
		// } else {
		// 	return redirect()->to('Auth/login');
		// }
	}
}
