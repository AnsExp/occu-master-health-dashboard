<?php

namespace App\Enums;

enum PermissionEnum
{
    case VIEW_LOGS;

    case VIEW_PATIENTS;
    case STORE_PATIENTS;
    case UPDATE_PATIENTS;
    case DESTROY_PATIENTS;

    case VIEW_PLANS;
    case STORE_PLANS;
    case UPDATE_PLANS;
    case DESTROY_PLANS;

    case VIEW_USERS;
    case STORE_USERS;
    case UPDATE_USERS;
    case DESTROY_USERS;

    case VIEW_ORDERS;
    case STORE_ORDERS;
    case UPDATE_ORDERS;
    case DESTROY_ORDERS;

    case VIEW_CERTIFICATES;
    case STORE_CERTIFICATES;
    case UPDATE_CERTIFICATES;
    case DESTROY_CERTIFICATES;

    case VIEW_DOCTORS;
    case STORE_DOCTORS;
    case UPDATE_DOCTORS;
    case DESTROY_DOCTORS;

    case VIEW_OPHTHALMOLOGY;
    case STORE_OPHTHALMOLOGY;
    case UPDATE_OPHTHALMOLOGY;
    case DESTROY_OPHTHALMOLOGY;

    case VIEW_AUDIOLOGY;
    case STORE_AUDIOLOGY;
    case UPDATE_AUDIOLOGY;
    case DESTROY_AUDIOLOGY;

    case VIEW_OCCUPATIONAL;
    case STORE_OCCUPATIONAL;
    case UPDATE_OCCUPATIONAL;
    case DESTROY_OCCUPATIONAL;

    public function code(): string
    {
        return str_replace('_', '.', strtolower($this->name));
    }

    public static function can(PermissionEnum $permission): bool
    {
        if (auth()->check()) {
            return auth()->user()->can($permission->code());
        }
        return false;
    }
}
