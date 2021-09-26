<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/api/img/{path}', 'Multimedia\ImageController@show')->where('path', '.*');
Route::group(['middleware' => ['auth', 'rbac']], function () {

    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/index', 'HomeController@index')->name('home');
    Route::get('/formatActivities', 'HomeController@formatActivities')->name('formatActivities');

    Route::group(['prefix' => 'geographic', 'namespace' => 'Geographic'], function () {
        Route::group(['prefix' => 'country'], function () {
            Route::get('/', 'CountryController@index')->name('viewIndexCountry');
            Route::get('index', 'CountryController@index')->name('viewIndexCountry');
            Route::get('form', 'CountryController@getFormCountry')->name('getFormCountry');
            Route::get('form/{id?}', 'CountryController@getFormCountry')->name('getFormCountry');
            Route::get('list', 'CountryController@getListCountries')->name('listDataCountry');
            Route::get('list/select', 'CountryController@getListSelect2')->name('listDataSelectCountry');
            Route::post('unique-name', 'CountryController@postIsNameUnique')->name('uniqueNameCountry');
            Route::post('save', 'CountryController@postSave')->name('saveCountry');
        });


        Route::group(['prefix' => 'region'], function () {
        Route::get('/', 'RegionController@index')->name('viewIndexRegion');
        Route::get('/form/{id?}', 'RegionController@getForm')->name('getFormRegion');
        Route::get('/list', 'RegionController@getList')->name('listDataRegion');
        Route::post('/unique-name', 'RegionController@postIsNameUnique')->name('uniqueNameRegion');
        Route::post('/save', 'RegionController@postSave')->name('saveRegion');
        });

        Route::group(['prefix' => 'province'], function () {
            Route::get('/', 'ProvinceController@index')->name('viewIndexProvince');
            Route::get('index', 'ProvinceController@index')->name('viewIndexProvince');
            Route::get('form', 'ProvinceController@getFormProvince')->name('getFormProvince');
            Route::get('form/{id?}', 'ProvinceController@getFormProvince')->name('getFormProvince');
            Route::get('list', 'ProvinceController@getListProvinces')->name('listDataProvince');
            Route::get('list/select', 'ProvinceController@getListSelect2')->name('listDataSelectProvince');
            Route::post('unique-name', 'ProvinceController@postIsNameUnique')->name('uniqueNameProvince');
            Route::post('save', 'ProvinceController@postSave')->name('saveProvince');
        });

        Route::group(['prefix' => 'city'], function () {
            Route::get('/', 'CityController@index')->name('viewIndexCity');
            Route::get('index', 'CityController@index')->name('viewIndexCity');
            Route::get('form/{id?}', 'CityController@getForm')->name('getFormCity');
            Route::get('list', 'CityController@getList')->name('listDataCity');
            Route::post('unique-name', 'CityController@postIsNameUnique')->name('uniqueNameCity');
            Route::post('save', 'CityController@postSave')->name('saveCity');
        });
        
        Route::group(['prefix' => 'zone'], function () {
            Route::get('/', 'ZoneController@index')->name('viewIndexZone');
            Route::get('index', 'ZoneController@index')->name('viewIndexZone');
            Route::get('form', 'ZoneController@getFormZone')->name('getFormZone');
            Route::get('form/{id?}', 'ZoneController@getFormZone')->name('getFormZone');
            Route::get('list', 'ZoneController@getList')->name('listDataZone');
            Route::get('list/select', 'ZoneController@getListSelect2')->name('listDataSelectZone');
            Route::post('unique-name', 'ZoneController@postIsNameUnique')->name('uniqueNameZone');
            Route::post('save', 'ZoneController@postSave')->name('saveZone');
            Route::delete('delete/{id?}', 'ZoneController@postDelete')->name('deleteZone');
            Route::get('list-map', 'ZoneController@getListZonesMap')->name('listDataZoneMap');
            Route::post('save-zones', 'ZoneController@postSaveZones')->name('saveZones');
        });
    });
    
    Route::group(['prefix' => 'rbac', 'namespace' => 'Rbac'], function () {
        Route::group(['prefix' => 'role'], function () {
            Route::get('role', 'RoleController@index')->name('viewIndexRole');
            Route::get('index', 'RoleController@index')->name('viewIndexRole');
            Route::get('form', 'RoleController@getFormRole')->name('getFormRole');
            Route::get('form/{id?}', 'RoleController@getFormRole')->name('getFormRole');
            Route::get('list', 'RoleController@getList')->name('listDataRole');
            Route::get('list/select', 'RoleController@getListSelect2')->name('listDataSelectRole');
            Route::post('unique-name', 'RoleController@postIsNameUnique')->name('uniqueNameRole');
            Route::post('save', 'RoleController@postSave')->name('saveRole');
        });
        Route::group(['prefix' => 'user'], function () {
            Route::get('/', 'UserController@index')->name('viewIndexUser');
            Route::get('index', 'UserController@index')->name('viewIndexUser');
            Route::get('form', 'UserController@getForm')->name('getFormUser');
            Route::get('form/{id?}', 'UserController@getForm')->name('getFormUser');
            Route::get('list', 'UserController@getList')->name('listDataUser');
            Route::post('unique-email', 'UserController@postIsEmailUnique')->name('uniqueEmailUser');
            Route::post('unique-name', 'UserController@postIsNameUnique')->name('uniqueNameUser');
            Route::post('save', 'UserController@postSave')->name('saveUser');
        });
    });

    Route::group(['prefix' => 'multimedia', 'namespace' => 'Multimedia'], function () {
        Route::group(['prefix' => 'image-parameter'], function () {
            Route::get('/', 'ImageParameterController@index')->name('viewIndexMultimedia');
            Route::get('index', 'ImageParameterController@index')->name('viewIndexMultimedia');
            Route::get('form', 'ImageParameterController@getForm')->name('getFormMultimedia');
            Route::get('form/{id?}', 'ImageParameterController@getForm')->name('getFormMultimedia');
            Route::get('list', 'ImageParameterController@getList')->name('listDataMultimedia');
            Route::post('unique-name', 'ImageParameterController@postIsNameUnique')->name('uniqueNameMultimedia');
            Route::post('unique-entity', 'ImageParameterController@postIsEntityUnique')->name('viewEntityMultimedia');
            Route::post('save', 'ImageParameterController@postSave')->name('saveMultimedia');
        });
    });

    Route::group(['prefix' => 'product'], function () {
        Route::get('/', 'ProductController@index')->name('viewIndexProduct');
        Route::get('/index', 'ProductController@index')->name('viewIndexProduct');
        Route::get('/form/{id?}', 'ProductController@getForm')->name('getFormProduct');
        Route::get('/list', 'ProductController@getList')->name('listDataProduct');
        Route::post('/unique-code', 'ProductController@postIsNameUnique')->name('uniqueCodeProduct');
        Route::post('/save', 'ProductController@postSave')->name('saveProduct');
    });

    Route::group(['prefix' => 'unit'], function () {
        Route::get('/', 'UnitController@index')->name('viewIndexUnit');
        Route::get('index', 'UnitController@index')->name('viewIndexUnit');
        Route::get('form/{id?}', 'UnitController@getForm')->name('getFormUnit');
        Route::get('list', 'UnitController@getList')->name('listDataUnit');
        Route::post('unique-name', 'UnitController@postIsNameUnique')->name('uniqueNameUnit');
        Route::post('saveUnit', 'UnitController@postSave')->name('saveUnit');
    });

    // Crud de tabla provider
    Route::group(['prefix' => 'provider'], function () {
        Route::get('/', 'ProviderController@index')->name('indexViewProvider');
        Route::get('/list', 'ProviderController@getList')->name('getListDataProvider');
        Route::get('/form/{id?}', 'ProviderController@getForm')->name('getFormProvider');
        Route::get('/view', 'ProviderController@view')->name('viewProfileProvider');
        Route::post('updateProfile', 'ProviderController@updateProviderUser')->name('updateProfile');
        Route::post('save', 'ProviderController@saveProvider')->name('saveProvider');
        Route::post('unique-code', 'ProviderController@postIsCodeUnique')->name('uniqueCodeProvider');
    });

    Route::group(['prefix' => 'category'], function () {
        Route::get('/', 'CategoryController@index')->name('indexViewCategory');
        Route::get('/list', 'CategoryController@getList')->name('getListDataCategory');
        Route::get('/form/{id?}', 'CategoryController@getForm')->name('getFormCategory');
        Route::post('save', 'CategoryController@postSave')->name('saveCategory');
        Route::post('unique-name', 'CategoryController@postIsNameUnique')->name('uniqueNameCategory');
    });

    Route::group(['prefix' => 'publicity'], function () {
        Route::get('/', 'PublicityController@index')->name('indexViewPublicity');
        Route::get('/list', 'PublicityController@getList')->name('getListDataPublicity');
        Route::get('/form/{id?}', 'PublicityController@getForm')->name('getFormPublicity');
        Route::post('save', 'PublicityController@savePublicity')->name('savePublicity');
    });
    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', 'OrderController@index')->name('indexViewOrder');
        Route::get('/form/{id?}', 'OrderController@getForm')->name('getFormOrder');
        Route::get('/list', 'OrderController@getList')->name('getListOrder');
        Route::post('/save', 'OrderController@postSave')->name('saveOrder');
    });
    Route::group(['prefix' => 'subscription'], function () {
        Route::get('/', 'SubscriptionController@index')->name('viewIndexSubscription');
        Route::get('list', 'SubscriptionController@getList')->name('listDataSubscription');
    });
    Route::group(['prefix' => 'store'], function () {
        Route::get('/', 'StoreController@index')->name('viewIndexStore');
        Route::get('index', 'StoreController@index')->name('viewIndexStore');
        Route::get('form/{id?}', 'StoreController@getForm')->name('getFormStore');
        Route::get('list', 'StoreController@getList')->name('listDataStore');
        Route::post('unique-name', 'StoreController@postIsNameUnique')->name('uniqueNameStore');
        Route::post('saveStore', 'StoreController@postSave')->name('saveStore');
    });
    
    Route::group(['prefix' => 'subscription'], function () {
        Route::get('/', 'SubscriptionController@index')->name('viewIndexSubscription');
        Route::get('list', 'SubscriptionController@getList')->name('listDataSubscription');
    });

    Route::group(['prefix' => 'plan'], function () {
        Route::get('/', 'PlanController@index')->name('indexViewPlan');
        Route::get('/list', 'PlanController@getList')->name('getListDataPlan');
        Route::get('/form/{id?}', 'PlanController@getForm')->name('getFormPlan');
        Route::post('save', 'PlanController@postSave')->name('savePlan');
        Route::post('unique-name', 'PlanController@postIsNameUnique')->name('uniqueNamePlan');
        Route::post('unique-code', 'PlanController@postIsCodeUnique')->name('uniqueCodePlan');
        Route::delete('deleted/{id?}', 'PlanController@deletedPlan')->name('deletedPlan');
    });

    Route::group(['prefix' => 'hospital'], function () {
        Route::get('/', 'HospitalController@index')->name('indexViewHospital');
        Route::get('/list', 'HospitalController@getList')->name('getListDataHospital');
        Route::get('/form/{id?}', 'HospitalController@getForm')->name('getFormHospital');
        Route::post('save', 'HospitalController@postSave')->name('saveHospital');
        Route::post('unique-name', 'HospitalController@postIsNameUnique')->name('uniqueNameHospital');
    });

    Route::group(['prefix' => 'planPrice'], function () {
        Route::get('/', 'PlanPriceController@index')->name('indexViewPlanPrice');
        Route::get('/list', 'PlanPriceController@getList')->name('getListDataPlanPrice');
        Route::get('/form/{id?}', 'PlanPriceController@getForm')->name('getFormPlanPrice');
        Route::get('plans-by-hospital', 'PlanPriceController@getPlansByHospital')->name('PlansByHospital');
        Route::post('save', 'PlanPriceController@postSave')->name('savePlanPrice');
    });

    Route::group(['prefix' => 'coverage'], function () {
        Route::get('/list/{id?}', 'CoverageController@getList')->name('getListDataCoverage');
        Route::get('/{id?}', 'CoverageController@index')->name('indexViewCoverage');
        Route::get('/form/create/{planId?}/{id?}', 'CoverageController@getForm')->name('getFormCoverage');
        Route::delete('deleted/{id?}', 'CoverageController@deletedCoverage')->name('deletedCoverage');
        Route::post('save', 'CoverageController@postSave')->name('saveCoverage');
        Route::post('unique-coverage', 'CoverageController@postIsCoverageTypeUnique')->name('uniqueCoverageType');
    });

    Route::group(['prefix' => 'typePlan'], function () {
        Route::get('/', 'TypePlanController@index')->name('viewIndexTypePlan');
        Route::get('index', 'TypePlanController@index')->name('viewIndexTypePlan');
        Route::get('form/{id?}', 'TypePlanController@getForm')->name('getFormTypePlan');
        Route::get('list', 'TypePlanController@getList')->name('listDataTypePlan');
        Route::post('unique-name', 'TypePlanController@postIsNameUnique')->name('uniqueNameTypePlan');
        Route::post('saveTypePlan', 'TypePlanController@postSave')->name('saveTypePlan');
    });

    Route::group(['prefix' => 'typeCoverage'], function () {
        Route::get('/', 'TypeCoverageController@index')->name('viewIndexTypeCoverage');
        Route::get('index', 'TypeCoverageController@index')->name('viewIndexTypeCoverage');
        Route::get('form/{id?}', 'TypeCoverageController@getForm')->name('getFormTypeCoverage');
        Route::get('list', 'TypeCoverageController@getList')->name('listDataTypeCoverage');
        Route::post('unique-name', 'TypeCoverageController@postIsNameUnique')->name('uniqueNameTypeCoverage');
        Route::post('saveTypeCoverage', 'TypeCoverageController@postSave')->name('saveTypeCoverage');
    });

    Route::group(['prefix' => 'section'], function () {
        Route::get('/list/{id?}', 'SectionController@getList')->name('getListDataSection');
        Route::get('/{id?}', 'SectionController@index')->name('indexViewSection');
        Route::get('/form/create/{planId?}/{id?}', 'SectionController@getForm')->name('getFormSection');
        Route::post('save', 'SectionController@postSave')->name('saveSection');
        Route::delete('deleted/{id?}', 'SectionController@deletedSection')->name('deletedSection');
        Route::post('unique-title', 'SectionController@postIsTitleUnique')->name('uniqueTitleSection');
    });
    Route::group(['prefix' => 'question'], function () {
        Route::get('/list/{id?}', 'FrequentQuestionController@getList')->name('getListDataQuestion');
        Route::get('/{id?}', 'FrequentQuestionController@index')->name('indexViewQuestion');
        Route::get('/form/create/{planId?}/{id?}', 'FrequentQuestionController@getForm')->name('getFormQuestion');
        Route::delete('deleted/{id?}', 'FrequentQuestionController@deletedQuestion')->name('deletedQuestion');
        Route::post('save', 'FrequentQuestionController@postSave')->name('saveQuestion');
       // Route::post('unique-coverage', 'CoverageController@postIsCoverageTypeUnique')->name('uniqueCoverageType');
    });

    Route::group(['prefix' => 'message'], function () {
        Route::get('/', 'MessageController@index')->name('viewIndexMessage');
        Route::post('saveMessage', 'MessageController@postSave')->name('saveMessage');
    });

    Route::group(['prefix' => 'kushki'], function () {
        Route::get('/', 'KushkiController@index')->name('viewIndexKushki');
        Route::post('/confirm', 'KushkiController@confirm')->name('KushkiConfirmar');
    });

    Route::group(['prefix' => 'saleTray'], function () {
        Route::get('/', 'SalesTrayController@index')->name('viewIndexSaleTray');
        Route::get('/list', 'SalesTrayController@getList')->name('getListDataSaleTray');
        Route::get('form/{id?}', 'SalesTrayController@getForm')->name('getFormSaleTray');
        Route::get('form-service/{id?}', 'SalesTrayController@getFormService')->name('getFormSaleService');
        Route::post('update-service-sale/{id?}', 'SalesTrayController@updateServiceSale')->name('retryService');
        Route::get('info-service/{id?}', 'SalesTrayController@getFormInfoService')->name('getInfoService');
    });

    Route::group(['prefix' => 'config'], function () {
        Route::get('/', 'ConfigController@index')->name('viewIndexConfig');
        Route::get('/list', 'ConfigController@getList')->name('getListDataConfig');
        Route::get('form/{id?}', 'ConfigController@getForm')->name('getFormConfig');
        Route::post('save', 'ConfigController@postSave')->name('saveConfig');
    });
});
Auth::routes();