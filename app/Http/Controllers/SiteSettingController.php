<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Validator;
use Illuminate\Http\Request;
use App\Helpers\Classes\FileHandler;
use Auth;
use Cache;

class SiteSettingController extends Controller
{
    const VIEW_PATH = 'backend.site-setting.';
    public function __construct()
    {
        //$this->authorizeResource(SiteSetting::class);
    }
    public function index()
    {
        //return "sete settings index";
        $this->authorize('viewAny', Auth::user());
        $siteSetting = SiteSetting::first();
        //dd($siteSetting);
        return \view(self::VIEW_PATH . 'browse', compact('siteSetting'));
    }

    public function create()
    {
    }
    public function store(Request $request)
    {

    }

    public function show(SiteSetting $siteSetting)
    {
    }

    public function edit(SiteSetting $siteSetting)
    {
    }

    public function update(Request $request, SiteSetting $siteSetting)
    {
        $this->authorize('update', Auth::user());
        //dd($request->all());

        
        try {
            $this->validate($request,['site_logo'=>'max:100|mimes:png,jpg,jpeg','site_favicon'=>'max:100|mimes:png,jpg,jpeg,ico',]);
            $request['show_slider'] = (@$request->show_slider) ? '1' : '0' ;
            $request['show_glance'] = (@$request->show_glance) ? '1' : '0' ;
            $request['show_course'] = (@$request->show_course) ? '1' : '0' ;
            $request['show_gallary'] = (@$request->show_gallary) ? '1' : '0' ;
            $request['show_provider'] = (@$request->show_provider) ? '1' : '0' ;
            $request['show_lang'] = (@$request->show_lang) ? '1' : '0' ;
            $request['show_logo'] = (@$request->show_logo) ? '1' : '0' ;
            $request['show_favicon'] = (@$request->show_favicon) ? '1' : '0' ;
    
            $authUser = Auth::id();
            
            $data = [];
            if ($request->hasFile('site_logo')) {
                @unlink(storage_path('/app/public/' . $siteSetting->site_logo));
                $site_logo = $request->file('site_logo');
                $randNumber = rand(1, 999);
                $name = 'site_logo-' . $authUser . '-' . time() . $randNumber . '.' . $site_logo->getClientOriginalExtension();
                $year = date('Y/');
                $month = date('F/');
                $destinationPath = storage_path('app/public/site-setting/' . $year . $month);
                $site_logo->move($destinationPath, $name);
                $filePath = 'site-setting/' . $year . $month;
                $site_logo = $filePath . '' . $name;
                $data['site_logo'] = $site_logo;
            }

            if ($request->hasFile('site_favicon')) {
                @unlink(storage_path('/app/public/' . $siteSetting->site_favicon));
                $site_favicon = $request->file('site_favicon');
                $randNumber = rand(1, 999);
                $name = 'site_favicon-' . $authUser . '-' . time() . $randNumber . '.' . $site_favicon->getClientOriginalExtension();
                $year = date('Y/');
                $month = date('F/');
                $destinationPath = storage_path('app/public/site-setting/' . $year . $month);
                $site_favicon->move($destinationPath, $name);
                $filePath = 'site-setting/' . $year . $month;
                $site_favicon = $filePath . '' . $name;
                $data['site_favicon'] = $site_favicon;
            }
            $siteSetting->update($request->all());
            SiteSetting::find($request->id)->update($data);

            Cache::forget('siteSettingInfo');

            return redirect()->route('admin.site-setting.index')->with([
                'message' => __('generic.object_updated_successfully', ['object' => 'Site Setting']),
                'alert-type' => 'success'
            ]);
        } catch (\Throwable $exception) {
            return back()->with([
                //'message' => __('generic.something_wrong_try_again'),
                'message' => __($exception->getMessage()),
                'alert-type' => 'error'
            ]);
        }


        
    }

    public function destroy(SiteSetting $siteSetting)
    {
    }


}
