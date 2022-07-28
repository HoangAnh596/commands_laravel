<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Common\Controller;

class SiteController extends Controller
{

    public function hello()
    {
        return 'hello';
    }

    /**
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function menu()
    {
        $user = auth()->user();
        $isDD = $user->hasRole('DD');
        $isHR = $user->hasRole('HR');
        $isPM = $user->hasRole('PM');
        $isEMP = $user->hasRole('EMP');
        $menuCommon = [];
        $dashboard = [
            "name" => 'Trang chủ',
            "link" => "/",
            "group" => "common",
            "icon" => "ft-home"
        ];
        $historyCp = [
            "name" => "Lịch sử checkpoint",
            "link" => "/history-checkpoint",
            "group" => "common",
            "icon" => "ft-clock"
        ];
        $myCp = [
            "name" => 'Phiếu đánh giá',
            "link" => "/my-checkpoint",
            "group" => "common",
            "icon" => "ft-file-text",
        ];
        $report = [
            "name" => "Báo cáo",
            "link" => "/report",
            "group" => "common",
            "icon" => "ft-zap"
        ];
        if ($isDD || $isHR || $isPM) {
            array_push($menuCommon, $dashboard);
        }

        if ($isHR || $isDD) {
            array_push($menuCommon, $historyCp);
            array_push($menuCommon, $report);
        }

        if ($isEMP) {
            array_push($menuCommon, $myCp);
        }

        return [
            'menu' => $menuCommon,
            'permissions' => $this->permissions(),
            'role' => $this->roles()
        ];
    }

    public function permissions()
    {
        $permissions = auth()->user()->getAllPermissions();
        $collect = collect($permissions);
        $per = $collect->map(function ($item) {
            return $item->name;
        });
        return $per->all();
    }

    public function roles()
    {
        return auth()->user()->getRoleNames();
    }
}
