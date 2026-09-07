<?php

namespace App\Http\Controllers\subadmin\Concerns;

use App\Models\Tags;
use Auth;

trait HandlesSubadminPermissions
{
    protected function subadmin()
    {
        return Auth::guard('subadmin')->user();
    }

    protected function canAccessAllDistricts($admin = null)
    {
        $admin = $admin ?: $this->subadmin();

        // Specific district always wins; only "all" means all-districts mode
        if (!empty($admin->district) && (string) $admin->district !== 'all') {
            return false;
        }

        return !empty($admin->select_all_district) || (string) ($admin->district ?? '') === 'all';
    }

    protected function isSingleDistrictSubadmin($admin = null)
    {
        return !$this->canAccessAllDistricts($admin);
    }

    protected function generateSubadminAthleteCode($userId, $districtId)
    {
        $tag = Tags::find($districtId);
        $shortCode = $tag ? $tag->short_code : 'XX';

        return 'RTUID/' . $shortCode . '/SA/' . (100 + (int) $userId);
    }

    protected function applyDistrictScope($query, $admin = null, $requestDistrict = null)
    {
        $admin = $admin ?: $this->subadmin();

        if ($this->canAccessAllDistricts($admin)) {
            if (!empty($requestDistrict) && $requestDistrict !== 'all') {
                $query->where('district', $requestDistrict);
            }
        } elseif (!empty($admin->district)) {
            $query->where('district', $admin->district);
        }

        return $query;
    }

    protected function getAssignedDistrictName($admin = null)
    {
        $admin = $admin ?: $this->subadmin();

        if ($this->canAccessAllDistricts($admin)) {
            return 'All Districts';
        }

        if (empty($admin->district)) {
            return 'N/A';
        }

        $tag = Tags::find($admin->district);

        return $tag ? $tag->title : $admin->district;
    }

    protected function subadminCan($permission, $admin = null)
    {
        $admin = $admin ?: $this->subadmin();

        if ($permission === 'apply_tournament') {
            return (int) ($admin->apply_tournament ?? 0) === 1
                || (int) ($admin->can_apply_tournament ?? 0) === 1;
        }

        return (int) ($admin->{$permission} ?? 0) === 1;
    }

    protected function subadminPermissions($admin = null)
    {
        $admin = $admin ?: $this->subadmin();

        return [
            'select_all_district' => $this->canAccessAllDistricts($admin),
            'add_weight'          => $this->subadminCan('add_weight', $admin),
            'can_apply'           => $this->subadminCan('can_apply', $admin),
            'athlete_detail'      => $this->subadminCan('athlete_detail', $admin),
            'athlete_edit'        => $this->subadminCan('athlete_edit', $admin),
            'apply_tournament'    => $this->subadminCan('apply_tournament', $admin),
            'can_coach'           => $this->subadminCan('can_coach', $admin),
            'can_referee'         => $this->subadminCan('can_referee', $admin),
            'can_draw_sheet'      => $this->subadminCan('can_draw_sheet', $admin),
            'can_create_tournament' => $this->subadminCan('can_create_tournament', $admin),
        ];
    }

    protected function applyDistrictScopeOnColumn($query, $column, $admin = null, $requestDistrict = null)
    {
        $admin = $admin ?: $this->subadmin();

        if ($this->canAccessAllDistricts($admin)) {
            if (!empty($requestDistrict) && $requestDistrict !== 'all') {
                $query->where($column, $requestDistrict);
            }
        } elseif (!empty($admin->district)) {
            $query->where($column, $admin->district);
        }

        return $query;
    }

    protected function subadminViewData(array $extra = [])
    {
        $admin = $this->subadmin();

        return array_merge([
            'subadminUser'          => $admin,
            'permissions'           => $this->subadminPermissions($admin),
            'canAccessAllDistricts' => $this->canAccessAllDistricts($admin),
            'assignedDistrictName'  => $this->getAssignedDistrictName($admin),
            'districts'             => Tags::where('status', 1)->get(),
        ], $extra);
    }

    protected function denySubadminUnless($condition, $message = 'You do not have permission to access this page.')
    {
        if (!$condition) {
            return redirect()->route('subadmin_dashboard')->withErrors($message);
        }

        return null;
    }

    protected function denyAllDistrictWrite($message = 'You do not have permission to modify records in all-district mode.')
    {
        if ($this->canAccessAllDistricts()) {
            return redirect()->route('subadmin_dashboard')->withErrors($message);
        }

        return null;
    }
}
