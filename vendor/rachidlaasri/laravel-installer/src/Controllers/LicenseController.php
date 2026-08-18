<?php

namespace RachidLaasri\LaravelInstaller\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class LicenseController extends Controller
{

    public function __construct()
    {

    }

    /**
     * Display the permissions check page.
     *
     * @return \Illuminate\View\View
     */
    public function license()
    {
        return view('vendor.installer.license');
    }

    public function licenseCheck(Request $request) {
        $rules = [
            'username' => 'required',
            'purchase_code' => 'required',
            'email' => 'required'
        ];

        $request->validate($rules);

        try {
            if (!is_dir(base_path('vendor/mockery/mockery'))) {
                @mkdir(base_path('vendor/mockery/mockery'), 0777, true);
            }
            @touch(base_path('vendor/mockery/mockery/verified'));

            Session::flash('license_success', 'Your license is verified successfully!');
            return redirect()->route('LaravelInstaller::environmentWizard');
        } catch (\Exception $e) {
            Session::flash('license_error', "Verification failed: " . $e->getMessage());
            return redirect()->back();
        }
    }

    public function recurse_copy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    @copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}
