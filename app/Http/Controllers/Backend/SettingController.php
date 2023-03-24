<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return view('backend.setting.general');
    }

    public function update(Request $request)
    {

        $this->validate($request, [
            'site_title' => 'required |min:2|max:190|string',
            'site_description' => 'nullable|min:2|max:190|string',
            'site_address' => 'nullable|min:2|max:190|string'
        ]);
        Setting::updateOrCreate(['name' => 'site_title'], ['value' => $request->get('site_title')]);
        Setting::updateOrCreate(['name' => 'site_description'], ['value' => $request->get('site_description')]);
        Setting::updateOrCreate(['name' => 'site_address'], ['value' => $request->get('site_address')]);
        // Setting::updateSettings($request->validated());
        // // Update .env file
        Artisan::call("env:set APP_NAME='" . $request->site_title . "'");
        notify()->success('Settings Successfully Updated.', 'Success');
        return back();
    }

    public function appearance()
    {
        return view('backend.setting.appearance');
    }

    /**
     * Update Appearance
     * @param UpdateAppearanceRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAppearance(Request $request)
    {
        if ($request->hasFile('site_logo')) {
            $this->deleteOldLogo(config('settings.site_logo'));
            Setting::set('site_logo', Storage::disk('public')->putFile('logos', $request->file('site_logo')));
        }
        if ($request->hasFile('site_favicon')) {
            $this->deleteOldLogo(config('settings.site_favicon'));
            Setting::set('site_favicon', Storage::disk('public')->putFile('logos', $request->file('site_favicon')));
        }
        notify()->success('Settings Successfully Updated.', 'Success');
        return back();
    }

    private function deleteOldLogo($path)
    {
        Storage::disk('public')->delete($path);
    }

    /**
     * Show Mail Settings Page
     * @return \Illuminate\View\View
     */
    public function mail()
    {
        return view('backend.setting.mail');
    }

    /**
     * Update Mail Settings
     * @param Request $request
     */
    public function updateMailSettings(Request $request)
    {

        $this->validate($request,(
            [
                'mail_mailer' => 'string|max:255',
                'mail_host' => 'nullable|string|max:255',
                'mail_port' => 'nullable|numeric',
                'mail_username' => 'nullable|string|max:255',
                'mail_password' => 'nullable|max:255',
                'mail_encryption' => 'nullable|string|max:255',
                'mail_from_address' => 'nullable|email|max:255',
                'mail_from_name' => 'nullable|string|max:255',
            ]
        ));

        Setting::updateOrCreate(['name' => 'MAIL_MAILER'], ['value' => $request->mail_mailer]);
        Setting::updateOrCreate(['name' => 'MAIL_HOST'], ['value' => $request->mail_host]);
        Setting::updateOrCreate(['name' => 'MAIL_PORT'], ['value' => $request->mail_port]);
        Setting::updateOrCreate(['name' => 'MAIL_USERNAME'], ['value' => $request->mail_username]);
        Setting::updateOrCreate(['name' => 'MAIL_PASSWORD'], ['value' => $request->mail_password]);
        Setting::updateOrCreate(['name' => 'MAIL_ENCRYPTION'], ['value' => $request->mail_encryption]);
        Setting::updateOrCreate(['name' => 'MAIL_FROM_ADDRESS'], ['value' => $request->mail_from_address]);
        Setting::updateOrCreate(['name' => 'MAIL_FROM_NAME'], ['value' => $request->mail_from_name]);
    
      //  Update .env mail settings
        Artisan::call("env:set MAIL_MAILER='" . $request->mail_mailer . "'");
        Artisan::call("env:set MAIL_HOST='" . $request->mail_host . "'");
        Artisan::call("env:set MAIL_PORT='" . $request->mail_port . "'");
        Artisan::call("env:set MAIL_USERNAME='" . $request->mail_username . "'");
        Artisan::call("env:set MAIL_PASSWORD='" . $request->mail_password . "'");
        Artisan::call("env:set MAIL_ENCRYPTION='" . $request->mail_encryption . "'");
        Artisan::call("env:set MAIL_FROM_ADDRESS='" . $request->mail_from_address . "'");
        Artisan::call("env:set MAIL_FROM_NAME='" . $request->mail_from_name . "'");
        notify()->success('Settings Successfully Updated.', 'Success');
        return back();
    }

    /**
     * Show Socialite Settings Page
     * @return \Illuminate\View\View
     */
    public function socialite()
    {
        return view('backend.setting.socialite');
    }

    /**
     * Update Socialite Settings
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSocialiteSettings(Request $request)
    {

        $this->validate($request, [
            'facebook_client_id' => 'string|min:3|max:190|nullable',
            'facebook_client_secret' => 'string|min:3|max:190|nullable',

            'google_client_id' => 'string|min:3|max:190|nullable',
            'google_client_secret' => 'string|min:3|max:190|nullable',

            'github_client_id' => 'string|min:3|max:190|nullable',
            'github_client_secret' => 'string|min:3|max:190|nullable',
        ]);
        // Update .env file

        Setting::updateOrCreate(['name' => 'facebook_client_id'], ['value' => $request->facebook_client_id]);
        Setting::updateOrCreate(['name' => 'facebook_client_secret'], ['value' => $request->facebook_client_secret]);
        Setting::updateOrCreate(['name' => 'google_client_id'], ['value' => $request->google_client_id]);
        Setting::updateOrCreate(['name' => 'google_client_secret'], ['value' => $request->google_client_secret]);
        Setting::updateOrCreate(['name' => 'github_client_id'], ['value' => $request->github_client_id]);
        Setting::updateOrCreate(['name' => 'github_client_secret'], ['value' => $request->github_client_secret]);

        Artisan::call("env:set FACEBOOK_CLIENT_ID='" . $request->facebook_client_id . "'");
        Artisan::call("env:set FACEBOOK_CLIENT_SECRET='" . $request->facebook_client_secret . "'");

        Artisan::call("env:set GOOGLE_CLIENT_ID='" . $request->google_client_id . "'");
        Artisan::call("env:set GOOGLE_CLIENT_SECRET='" . $request->google_client_secret . "'");

        Artisan::call("env:set GITHUB_CLIENT_ID='" . $request->github_client_id . "'");
        Artisan::call("env:set GITHUB_CLIENT_SECRET='" . $request->github_client_secret . "'");

        notify()->success('Settings Successfully Updated.', 'Success');
        return back();
    }
}
