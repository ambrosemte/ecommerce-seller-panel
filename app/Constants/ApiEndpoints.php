<?php

namespace App\Constants;

class ApiEndpoints
{
    const BASE_URL = "127.0.0.1:8000";
    //const BASE_URL = "https://backend-ecommerce.mtedev.com.ng";

    // AUTH
    const LOGIN = "/api/v1/auth/login";
    const LOGIN_VIA_GOOGLE = "/api/v1/auth/login-via-google";
    const REGISTER = "/api/v1/auth/register";
    const LOGOUT = "/api/v1/auth/logout";

    // USER
    const GET_PROFILE = "/api/v1/user/profile";
    const CHECK_AUTH = "/api/v1/user/check-authentication";

    //DASHBOARD
    const GET_DASHBOARD = "/api/v1/dashboard/seller";

    // STORE
    const LIST_STORES = "/api/v1/store";
    const CREATE_STORE = "/api/v1/store";
    const VIEW_STORE = "/api/v1/store";
    const ACTIVATE_STORE = "/api/v1/store/activate";
    const DEACTIVATE_STORE = "/api/v1/store/deactivate";
    const DELETE_STORE = "/api/v1/store";

    // PRODUCT
    const LIST_PRODUCTS = "/api/v1/product";
    const CREATE_PRODUCT = "/api/v1/product";
    const VIEW_PRODUCT = "/api/v1/product";
    const SEARCH_PRODUCT = "/api/v1/product/search/seller";
    const EDIT_PRODUCT = "/api/v1/product";
    const ACTIVATE_PRODUCT_VARIATION = "/api/v1/product/variation";
    const DEACTIVATE_PRODUCT_VARIATION = "/api/v1/product/variation";
    const DELETE_PRODUCT = "/api/v1/product";
    const DELETE_PRODUCT_VARIATION = "/api/v1/product";

    // ORDER
    const LIST_ORDERS = "/api/v1/order";
    const ACCEPT_ORDER = "/api/v1/order/accept";
    const DECLINE_ORDER = "/api/v1/order/decline";

    //CATEGORY
    const LIST_CATEGORIES = "/api/v1/category";

    //SPECIFICATION
    const LIST_SPECIFICATIONS_BY_CATEGORY = "/api/v1/specification";

    // STORY
    const LIST_STORIES = "/api/v1/story/all";
    const CREATE_STORY = "/api/v1/story";

    //PROMO BANNER
    const LIST_PROMO_BANNER = "/api/v1/promo-banner/all";
    const CREATE_PROMO_BANNER = "/api/v1/promo-banner";
    const VIEW_PROMO_BANNER = "/api/v1/promo-banner";
    const EDIT_PROMO_BANNER = "/api/v1/promo-banner";

}
