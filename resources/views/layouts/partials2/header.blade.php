<div id="kt_header" class="header header-fixed">
    <!--begin::Container-->
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
        <!--begin::Header Menu Wrapper-->
        <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">     
        </div>
        <!--end::Header Menu Wrapper-->
        <!--begin::Topbar-->
        <div class="topbar">
        <!--begin::User-->
        <div class="topbar-item">
            <div
                    class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2"
                    id="kt_quick_user_toggle">
                <div class="d-flex flex-column text-right pr-3">
                    <span class="text-muted opacity-50 font-weight-bold font-size-sm d-none d-md-inline">
                       {{--  {{ Auth::user()->provider_id ? Auth::user()->provider->name: "" }} --}}
                    </span>
                    <span class="text-dark-50 font-weight-bolder font-size-sm d-none d-md-inline">{{ Auth::user()->name }}</span>
                </div>
                <span class="symbol symbol-lg-35 symbol-25 symbol-light-success">
                    <span class="symbol-label font-size-h5 font-weight-bold">
                        {{substr(Auth::user()->name,0,1)}}
                    </span>
                </span>
            </div>
        </div>
        <!--end::User-->
        </div>
        <!--end::Topbar-->
    </div>
    <!--end::Container-->
</div>