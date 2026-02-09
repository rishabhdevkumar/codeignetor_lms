<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\Customer\CountryModel;

class Country extends BaseController
{
    protected $countryModel;
    protected $helpers = ['url', 'form', 'security'];

    public function __construct()
    {
        $this->countryModel = new CountryModel();
        date_default_timezone_set('Asia/Calcutta');
    }

    public function index()
    {
        $result['title'] = "Countries";

        // Load active countries from database
        $result['countries'] = $this->countryModel->getActiveCountries();

        // Load views
        echo view('header', $result);
        echo view('customertransit/country_view', $result); // create this view for dropdown/list
        echo view('footer');
    }

    public function add()
    {
        $result['title'] = "Add Country";

        echo view('header', $result);
        echo view('customertransit/add_country_view', $result); // create this view for add form
        echo view('footer');
    }
}
