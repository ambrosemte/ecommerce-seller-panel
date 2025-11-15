<?php

namespace App\Constants;

class ApiEndpoints
{
    //const BASE_URL = "127.0.0.1:8000";
    const BASE_URL = "https://backend-ecommerce.mtedev.com.ng";

    // AUTH
    const LOGIN = "/api/v1/auth/login";
    const LOGIN_VIA_GOOGLE = "/api/v1/auth/login-via-google";
    const REGISTER = "/api/v1/auth/register";
    const LOGOUT = "/api/v1/auth/logout";

    // USER
    const GET_PROFILE = "/api/v1/user/profile";
    const CHECK_AUTH = "/api/v1/user/check-authentication";
    const UPDATE_PROFILE_IMAGE = "/api/v1/user/profile/image/update";
    const UPDATE_PROFILE = "/api/v1/user/profile/update";
    const UPDATE_PREFERRED_CURRENCY = "/api/v1/user/preferred-currency";

    // STORE
    const LIST_STORES = "/api/v1/store";
    const CREATE_STORE = "/api/v1/store";
    const VIEW_STORE = "/api/v1/store";
    const DELETE_STORE = "/api/v1/store";

    // PRODUCT
    const LIST_PRODUCTS = "/api/v1/product";
    const CREATE_PRODUCT = "/api/v1/product";
    const VIEW_PRODUCT = "/api/v1/product";
    const UPDATE_PRODUCT = "/api/v1/product/{id}/update";
    const DELETE_PRODUCT = "/api/v1/product";

    // ORDER
    const LIST_ORDERS = "/api/v1/order";
    const ACCEPT_ORDER = "/api/v1/order/accept";
    const DECLINE_ORDER = "/api/v1/order/decline";

    //CATEGORY
    const LIST_CATEGORIES = "/api/v1/category";

    //SPECIFICATION
    const LIST_SPECIFICATIONS_BY_CATEGORY = "/api/v1/specification";

}
