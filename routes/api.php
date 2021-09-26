<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'customers', 'namespace' => 'Api\Customer', 'middleware' => ['auth.firebase']], function ($api) {
    $api->post('/', 'CustomerController@store');
    $api->put('/{id}', 'CustomerController@completeRegister');
    $api->get('/{id}', 'CustomerController@show');
    $api->get('/{firebase_uid}/firebase', 'CustomerController@showWithUid');
    $api->post('/stravaIntegration', 'StravaCustomerIntegrationController@store');
});

Route::group(['prefix' => 'categories', 'namespace' => 'Api'], function ($api) {
    $api->get('/', 'CategoryController@findCategories');
});

Route::group(['prefix' => 'products', 'namespace' => 'Api'], function ($api) {
    $api->get('/', 'ProductController@findAllBy');
    $api->get('/suggested', 'ProductController@searchSuggested');
    $api->get('/{productId}', 'ProductController@view');
});


Route::group(['prefix' => 'providers', 'namespace' => 'Api'], function ($api) {
    $api->get('', 'ProviderController@getAll');
    $api->get('/{providerId}', 'ProviderController@view');
    $api->get('/{code}/code', 'ProviderController@viewByCode');
});
Route::group(['prefix' => 'publicity', 'namespace' => 'Api'], function ($api) {
    $api->get('/', 'PublicityController@findAll');
});

Route::group(['prefix' => 'orders', 'namespace' => 'Api'], function ($api) {
    $api->post('/', 'OrderController@create');
    $api->put('/qualification', 'OrderController@qualification');
    $api->get('/{orderId}', 'OrderController@view');
    $api->get('/', 'OrderController@findAll');
});


Route::group(['prefix' => 'favorites', 'namespace' => 'Api'], function ($api) {
    $api->post('/', 'FavoriteProductController@create');
    $api->get('/', 'FavoriteProductController@findAll');
    $api->delete('/', 'FavoriteProductController@delete');
});

Route::group(['prefix' => 'subscriptions', 'namespace' => 'Api'], function ($api) {
    $api->post('/', 'SubscriptionController@create');
});
Route::group(['prefix' => 'plan', 'namespace' => 'Api'], function ($api) {
    $api->get('/', 'PlanController@findAll');
    $api->get('/comparative', 'PlanController@comparativePlan');
    $api->get('/{id?}', 'PlanController@findById');
    $api->get('/{code}/code', 'PlanController@findByCode');
    $api->get('with/price', 'PlanController@getPlanWithPrice');
});

Route::group(['prefix' => 'hospital', 'namespace' => 'Api'], function ($api) {
    $api->get('/', 'HospitalController@findAll');
});

Route::group(['prefix' => 'province', 'namespace' => 'Api'], function ($api) {
    $api->get('/', 'ProvinceController@findAll');
});

Route::group(['prefix' => 'registerCivil', 'namespace' => 'Api'], function ($api) {
    $api->get('/', 'RegisterCivilController@findUserByCI');
});

Route::group(['prefix' => 'subscriptions', 'namespace' => 'Api'], function ($api) {
    $api->post('/pay/kushki', 'SubscriptionController@create');
    $api->post('first-payment', 'SubscriptionController@firstPaymentsubscription');
    $api->get('/discount', 'SubscriptionController@temporaryDiscount');
    $api->get('sigmep-create', 'SubscriptionController@sigmepSoap');
});

Route::group(['prefix' => 'sale', 'namespace' => 'Api'], function ($api) {
    $api->get('/{id?}', 'SaleController@view');
    $api->post('save', 'SaleController@saveSale');
});


Route::group(['prefix' => 'inscriptions', 'namespace' => 'Api'], function ($api) {
    $api->post('webhook', 'SubscriptionController@handleWebhook');
});

Route::group(['prefix' => 'emails', 'namespace' => 'Api'], function ($api) {
    $api->post('termsAndConditions', 'EmailController@sendTermsAndConditions');
    $api->post('summaryPurchase', 'EmailController@sendSummaryPurchase');
});
