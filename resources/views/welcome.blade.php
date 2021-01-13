@extends('layouts.gui')

@section('css_extend')
    <style>
        .btn-xlg {
            padding: 18px 28px;
            font-size: 25px;
            line-height: normal;
            -webkit-border-radius: 8px;
            -moz-border-radius: 8px;
            border-radius: 8px;
        }
        a {
            color: white;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline!important;
            color: white;
        }
    </style>
@endsection

@section('content')
    <section style="
            background: #1A2980;  /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #26D0CE, #1A2980);  /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #26D0CE, #1A2980); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */;">
        <section class="position-relative overflow-hidden text-center bg-light text-white" style="margin-top: -20px; background-image: url('header.jpg');">
            <div class="p-lg-5 mx-auto my-5">
                <h1 class=" display-5 fw-normal" style="text-shadow: 1px 1px #000000">@lang('gui.main_title')</h1>
                @if(Auth::check() && Auth::user()->hasRole('admin'))
                    <a class="btn btn-lg btn-success" href="{{route('users.index')}}">@lang('gui.users')</a>
                @elseif(Auth::check() && Auth::user()->hasRole('user'))
                    <a class="btn btn-xlg btn-success" href="{{route('wishlists.index')}}"><i class="far fa-smile-beam" style="margin-right: 10px"></i>@lang('gui.wl_main_new')</a>
                @else
                    <a class="btn btn-xlg btn-success" href="{{route('login')}}"><i class="far fa-smile-beam" style="margin-right: 10px"></i>@lang('gui.wl_main_new')</a>
                @endif
            </div>
            <div class="product-device shadow-sm d-none d-md-block"></div>
            <div class="product-device product-device-2 shadow-sm d-none d-md-block"></div>
        </section>

        <section class="row row-cols-1 row-cols-md-3">
            <div class="col p-5 bg-primary text-center text-white overflow-hidden">
                <div>
                    <h2 class="display-5">@lang('gui.main_feature_1_title')</h2>
                    <p class="lead">@lang('gui.main_feature_1_subtitle')</p>
                    <i class="far fa-smile-wink" style="font-size: 50pt"></i>
                </div>
            </div>
            <div class="col p-5 bg-dark text-center text-white overflow-hidden">
                <div>
                    <h2 class="display-5">@lang('gui.main_feature_2_title')</h2>
                    <p class="lead">@lang('gui.main_feature_2_subtitle')</p>
                    <i class="far fa-share-square" style="font-size: 50pt"></i>
                </div>
            </div>
            <div class="col p-5 bg-info text-center text-white overflow-hidden">
                <div>
                    <h2 class="display-5">@lang('gui.main_feature_3_title')</h2>
                    <p class="lead">@lang('gui.main_feature_3_subtitle')</p>
                    <i class="fas fa-infinity" style="font-size: 50pt"></i>
                </div>
            </div>
        </section>
        <section class="row text-center p-5 text-white" style="">
            <h1 class="display-5 fw-normal mb-5">Lorem ipsum section</h1>
            <div class="row row-cols-md-2 p-3">
                <div class="col" style="font-size: 13pt; text-align: justify">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur nec felis purus. Proin cursus vitae tellus eget feugiat. Sed at posuere est. Curabitur elementum, quam nec sodales mollis, lorem lectus molestie risus, et placerat quam libero in velit. Cras pharetra turpis justo, rutrum venenatis neque placerat non. Suspendisse ut nisl nec ipsum dapibus elementum. Maecenas ut turpis sed magna placerat laoreet. Sed id dolor dolor. Proin consectetur tellus sed malesuada posuere. Sed semper nunc blandit pharetra lacinia. Nam ultrices eu felis sit amet sagittis.</p>
                    <p>In viverra mollis magna. Duis quis ipsum blandit, iaculis velit quis, lacinia lectus. Aenean ullamcorper ligula metus, ac varius nunc ullamcorper vitae. Morbi luctus nibh sit amet elit tristique congue. Praesent ac tortor nibh. Vestibulum dapibus mauris at congue malesuada. Mauris leo felis, tristique nec nulla sed, ultrices feugiat est. Vivamus vel elit venenatis, semper tellus ut, porta odio. Aenean sit amet justo tristique, aliquam nulla ut, vehicula nibh. Nulla ac tellus ligula.</p>
                </div>
                <div class="col">
                    <div class="row">
                        <div class="col">
                            <h3 class="display-6 fw-normal mb-3">@lang('gui.main_downloads')</h3>
                            <p>
                                <a href="/wishlists_user_manual.pdf" style="font-size: 15pt"><i class="far fa-file"></i> @lang('gui.main_user_manual_doc')</a>
                            </p>
                            <p>
                                <a href="/wishlists_install_manual.pdf" style="font-size: 15pt"><i class="fas fa-user-cog"></i> @lang('gui.main_install_doc')</a>
                            </p>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col">
                            <h3 class="display-6 fw-normal m3-5">Dolores amet ipsum blandit</h3>
                            <img class="p-3" src="/partners/partner1.png" alt="partner_1">
                            <img class="p-3" src="/partners/partner2.png" alt="partner_2">
                            <img class="p-3" src="/partners/partner3.png" alt="partner_3">
                        </div>
                    </div>

                </div>
            </div>

        </section>
    </section>

@endsection
