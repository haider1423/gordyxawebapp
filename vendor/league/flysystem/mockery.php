<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\User;
use App\Models\Language;
use App\Models\User\SEO;
use Illuminate\Support\Carbon;
use App\Models\User\BasicExtra;
use App\Models\User\PageHeading;
use App\Models\User\BasicSetting;
use App\Models\User\Menu as UserMenu;
use App\Models\User\BasicExtended;
use App\Models\User\UserPermission;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use App\Models\User\Language as UserLanguage;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\Social as UserSocialMedia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function changePreferences($userId)
    {
        $currentPackage = UserPermissionHelper::currentPackage($userId);
        $preference = UserPermission::query()
            ->where('user_id', $userId)
            ->first();

        // if current package does not match with 'package_id' of 'user_permissions' table, then change 'package_id' in 'user_permissions'
        if (!empty($currentPackage) && ($currentPackage->id != $preference->package_id)) {
            $features = !empty($currentPackage->features) ? json_decode($currentPackage->features, true) : [];
            $features[] = "Contact";
            $preference->permissions = json_encode($features);
            $preference->package_id = $currentPackage->id;
            $preference->save();
        }
    }

    public function getMenus($userMenu, $packagePermissions, $array)
    {
        foreach ($userMenu->children as $key1 => $child) {
            $exists = array_key_exists('children', get_object_vars($child));
            if (!$exists) {
                $newChildrenUserMenu = [];
                if (!is_null($packagePermissions) && !in_array('Online Order', $packagePermissions)) {
                    if ($child->type === 'cart' || $child->type === 'checkout') {
                        $newChildrenUserMenu[] = $key1;
                    }
                }
                if (!is_null($packagePermissions) && !in_array('Table Reservation', $packagePermissions)) {
                    if ($child->type === 'reservation') {
                        $newChildrenUserMenu[] = $key1;
                    }
                }
                if (!is_null($packagePermissions) && !in_array('Blog', $packagePermissions)) {
                    if ($child->type === 'blogs') {
                        $newChildrenUserMenu[] = $key1;
                    }
                }
                if (!is_null($packagePermissions) && !in_array('Custom Page', $packagePermissions)) {
                    if (!in_array($child->type, $array)) {
                        $newChildrenUserMenu[] = $key1;
                    }
                }
                foreach ($newChildrenUserMenu as $index) {
                    unset($userMenu->children[$index]);
                }
            } else {
                $this->getMenus($child, $packagePermissions, $array);
            }
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        if (!app()->runningInConsole()) {
            $langs = Language::all();

            view()->composer('*', function ($view) {

                if (session()->has('lang')) {
                    $currentLang = Language::where('code', session()->get('lang'))->first();
                } else {
                    $currentLang = Language::where('is_default', 1)->first();
                }

                $bs = $currentLang->basic_setting;
                $be = $currentLang->basic_extended;

                $apopups = $currentLang->popups()->where('status', 1)->orderBy('serial_number', 'ASC')->get();

                if (Menu::query()->where('language_id', $currentLang->id)->count() > 0) {
                    $menus = Menu::query()->where('language_id', $currentLang->id)->first()->menus;
                } else {
                    $menus = json_encode([]);
                }

                if ($currentLang->rtl == 1) {
                    $rtl = 1;
                } else {
                    $rtl = 0;
                }

                $view->with('bs', $bs);
                $view->with('be', $be);
                $view->with('currentLang', $currentLang);
                $view->with('popups', $apopups);
                $view->with('menus', $menus);
                $view->with('rtl', $rtl);
            });
            View::composer(['user.*'], function ($view) {
                if (Auth::guard('web')->check()) {
                    $userId = getRootUser()->id;
                    // change package_id in 'user_permissions'
                    $this->changePreferences($userId);
                    $userBs = BasicSetting::query()
                        ->where('user_id', $userId)
                        ->first();
                    $userBe = BasicExtended::query()
                        ->where('user_id', $userId)
                        ->first();
                    $userBex = BasicExtra::query()
                        ->where('user_id', $userId)
                        ->first();
                    $userLangs = UserLanguage::query()
                        ->where('user_id', $userId)
                        ->get();

                    $activeTheme = $userBs->theme;

                    if ($userBe?->timezone) {
                        Config::set('app.timezone', $userBe->timezone);
                    }

                    if (!is_null(Auth::guard('web')->user()->admin_id)) {
                        $user = User::find(Auth::guard('web')->user()->admin_id);
                        $tusername = $user->username;
                    } else {
                        $tusername = Auth::guard('web')->user()->username;
                    }

                    $packagePermissions = UserPermissionHelper::packagePermission($userId);
                    $packagePermissions = json_decode($packagePermissions, true);
                    $view->with('userBs', $userBs);
                    $view->with('tusername', $tusername);
                    $view->with('userBe', $userBe);
                    $view->with('userBex', $userBex);
                    $view->with('userLangs', $userLangs);
                    $view->with('activeTheme', $activeTheme);
                    $view->with('packagePermissions', $packagePermissions);
                }
            });


            View::composer(['user-front.*'], function ($view) {
                if (Session::has('user')) {
                    $user = Session::get('user');
                } else {
                    $user = getUser();
                }
                // change package_id in 'user_permissions'
                $this->changePreferences($user->id);

                if (session()->has('user_lang')) {
                    $userCurrentLang = UserLanguage::query()
                        ->where('code', session()->get('user_lang'))
                        ->where('user_id', $user->id)
                        ->first();
                    if (empty($userCurrentLang)) {
                        $userCurrentLang = UserLanguage::query()
                            ->where('is_default', 1)
                            ->where('user_id', $user->id)
                            ->first();
                        session()->put('user_lang', $userCurrentLang->code);
                    }
                } else {
                    $userCurrentLang = UserLanguage::query()
                        ->where('is_default', 1)
                        ->where('user_id', $user->id)
                        ->first();
                }
                $allLanguages = UserLanguage::query()->where('user_id', $user->id)->get();
                $socialMedias = UserSocialMedia::query()->where('user_id', $user->id)->get();
                $userSeo = SEO::query()->where('user_id', $user->id)->where('language_id', $userCurrentLang->id)->first();

                $userPageHeading = PageHeading::query()->where('user_id', $user->id)->where('language_id', $userCurrentLang->id)->first();

                $packagePermissions = UserPermissionHelper::packagePermission($user->id);
                $packagePermissions = json_decode($packagePermissions, true);

                $keywords = json_decode($userCurrentLang->keywords, true);

                $apopups = $userCurrentLang
                    ->popups()
                    ->where('user_id', $user->id)
                    ->where('status', 1)
                    ->orderBy('serial_number', 'ASC')
                    ->get();

                if (
                    UserMenu::query()
                    ->where('language_id', $userCurrentLang->id)
                    ->where('user_id', $user->id)
                    ->count() > 0
                ) {

                    $userMenus = UserMenu::query()
                        ->where('language_id', $userCurrentLang->id)
                        ->where('user_id', $user->id)
                        ->first()
                        ->menus;
                } else {
                    $userMenus = json_encode([]);
                }

                /*
                 * Menu Restriction for checking items availability in package
                 */
                $array = array('home', 'menu', 'items', 'team', 'career', 'gallery', 'faq', 'blogs', 'contact', 'cart', 'checkout', 'reservation', 'custom');
                $userMenus = json_decode($userMenus);
                if ($userMenus !== null) {

                    foreach ($userMenus as $key => $userMenu) {
                        $exists = array_key_exists('children', get_object_vars($userMenu));
                        if (!$exists) {
                            $newUserMenu = [];
                            if (!is_null($packagePermissions) && !in_array('Online Order', $packagePermissions)) {
                                if ($userMenu->type === 'cart' || $userMenu->type === 'checkout') {
                                    $newUserMenu[] = $key;
                                }
                            }
                            if (!is_null($packagePermissions) && !in_array('Table Reservation', $packagePermissions)) {
                                if ($userMenu->type === 'reservation') {
                                    $newUserMenu[] = $key;
                                }
                            }
                            if (!is_null($packagePermissions) && !in_array('Blog', $packagePermissions)) {
                                if ($userMenu->type === 'blogs') {
                                    $newUserMenu[] = $key;
                                }
                            }
                            if (!is_null($packagePermissions) && !in_array('Custom Page', $packagePermissions)) {
                                if (!in_array($userMenu->type, $array)) {
                                    $newUserMenu[] = $key;
                                }
                            }
                            foreach ($newUserMenu as $index) {
                                unset($userMenus[$index]);
                            }
                        } else {
                            $this->getMenus($userMenu, $packagePermissions, $array);
                        }
                    }
                } else {
                    $userMenus = [];
                };
                $userMenus = json_encode($userMenus);
                $bs = BasicSetting::query()->where('user_id', $user->id)->where('language_id', $userCurrentLang->id);
                $userBs = $bs->first();
                $websiteInfo = $bs->select('favicon', 'website_title', 'logo')->first();

                $activeTheme = $userCurrentLang->basic_setting->theme;

                $userBe = BasicExtended::query()
                    ->where('user_id', $user->id)->where('language_id', $userCurrentLang->id)
                    ->first();
                $userBex = BasicExtra::query()
                    ->where('user_id', $user->id)
                    ->first();
                if ($userCurrentLang->rtl == 1) {
                    $rtl = 1;
                } else {
                    $rtl = 0;
                }
                if ($userBe?->timezone) {
                    Config::set('app.timezone', $userBe->timezone);
                }

                $view->with('user', $user);
                $view->with('upageHeading', $userPageHeading);
                $view->with('userBs', $userBs);
                $view->with('userBe', $userBe);
                $view->with('userBex', $userBex);
                $view->with('userMenus', $userMenus);
                $view->with('allLanguageInfos', $allLanguages);
                $view->with('socialMediaInfos', $socialMedias);
                $view->with('userCurrentLang', $userCurrentLang);
                $view->with('keywords', $keywords);
                $view->with('packagePermissions', $packagePermissions);
                $view->with('websiteInfo', $websiteInfo);
                $view->with('apopups', $apopups);
                $view->with('rtl', $rtl);
                $view->with('userSeo', $userSeo);
                $view->with('activeTheme', $activeTheme);
            });
            View::share('langs', $langs);
        }
    }
}
