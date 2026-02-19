<?php
    namespace App\Controllers\OrderGeneration;

    use App\Models\Crud_Model;
    use App\Models\OrderGeneration\IndentAllotmentModel;
    use CodeIgniter\Controller;

    class IndentAllotment Extends Controller {
        protected $session;
        protected $crudModel;
        protected $indentallotmentModel;
        protected $helpers = ['url', 'form', 'security'];

        public function __construct()
	{
		$this->session        = session();
		$this->crudModel      = new Crud_Model();
		$this->indentallotmentModel   = new IndentAllotmentModel();

		date_default_timezone_set('Asia/Calcutta');
	}

    public function index()
	{

		$result = [];

		$result['title'] = "IndentAllotment";
		$where = [];
		$result['indentallotment'] = $this->indentallotmentModel->findAll();

		echo view('header', $result);
		echo view('IndentAllotment/indentallotment_view', $result);
		echo view('footer');
	}

    }

?>