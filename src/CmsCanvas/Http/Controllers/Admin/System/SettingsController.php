<?php 

namespace CmsCanvas\Http\Controllers\Admin\System;

use View, Request, stdClass, Theme, Config, Input, Redirect, Validator;
use CmsCanvas\Http\Controllers\Admin\AdminController;
use CmsCanvas\Models\Setting;
use CmsCanvas\Models\Content\Entry;

class SettingsController extends AdminController {

    /**
     * Display general settings
     *
     * @return View
     */
    public function getGeneralSettings()
    {
        $content = View::make('cmscanvas::admin.system.settings.generalSettings');

        $settingItems = Setting::all();
        $settings = new stdClass;
        foreach ($settingItems as $settingItem) {
            $settings->{$settingItem->setting} = $settingItem->value;
        }

        $content->settings = $settings;
        $content->themes = Theme::getThemes();
        $content->layouts = Theme::getThemeLayouts(Config::get('cmscanvas::config.theme'));
        // @todo add paginated entry search
        $content->entries = Entry::orderBy('title', 'asc')->get();

        $this->layout->breadcrumbs = [Request::path() => 'General Settings'];
        $this->layout->content = $content;
    }

    /**
     * Update setting values
     *
     * @return void
     */
    public function postGeneralSettings()
    {
        $rules = [
            'site_name' => 'required',
            'notification_email' => "required|email",
            'site_homepage' => 'required',
            'custom_404' => 'required',
            'theme' => 'required',
            'layout' => 'required',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::route('admin.system.settings.general-settings')
                ->withInput()
                ->with('error', $validator->messages()->all());
        }

        $settingItems = Setting::all();

        foreach ($settingItems as $settingItem) {
            $value = Input::get($settingItem->setting);

            if ($value !== null) {
                $settingItem->value = $value;
                $settingItem->save();
            }
        }

        return Redirect::route('admin.system.settings.general-settings')
            ->with('message', "Settings updated successfully.");
    }

    /**
     * Return a list of layouts belonging to the specified theme
     *
     * @return string
     */
    public function postThemeLayouts()
    {
        $response['status'] = 'OK';

        $theme = Input::get('theme');

        if ($theme != null) {
            $layouts = Theme::getThemeLayouts($theme);

            if (! empty($layouts)) {
                $response['layouts'] = $layouts;
            } else {
                $response['status'] = 'ERROR';
                $response['message'] = 'No layouts found';
            }
        } else {
            $response['status'] = 'ERROR';
            $response['message'] = 'No theme was specified';
        }

        return json_encode($response);
    }

}