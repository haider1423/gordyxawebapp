@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">Themes</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('user.dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Settings</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Themes</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title">{{ __('Theme Settings') }}</div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <form id="ajaxForm" action="{{ route('user.theme.update') }}" method="post">
                                @csrf

                                <div class="form-group">
                                    <label class="form-label">{{ __('Theme') }} *</label>
                                    <div class="row">
                                        <div class="col-4 col-sm-4">
                                            <label class="imagecheck mb-2">
                                                <input name="theme" type="radio" value="fastfood"
                                                    class="imagecheck-input"
                                                    {{ !empty($abs->theme) && $abs->theme == 'fastfood' ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/tenant/img/themes/multipurpose.png') }}"
                                                        alt="title" class="imagecheck-image">
                                                </figure>
                                            </label>


                                            <h5 class="text-center">{{ __('Fastfood Theme') }}</h5>
                                        </div>
                                        <div class="col-4 col-sm-4">
                                            <label class="imagecheck mb-2">
                                                <input name="theme" type="radio" value="bakery" class="imagecheck-input"
                                                    {{ !empty($abs->theme) && $abs->theme == 'bakery' ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/tenant/img/themes/bakery.png') }}"
                                                        alt="title" class="imagecheck-image">
                                                </figure>
                                            </label>
                                            <h5 class="text-center">{{ __('Bakery Theme') }}</h5>
                                        </div>
                                        <div class="col-4 col-sm-4">
                                            <label class="imagecheck mb-2">
                                                <input name="theme" type="radio" value="pizza" class="imagecheck-input"
                                                    {{ !empty($abs->theme) && $abs->theme == 'pizza' ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/tenant/img/themes/pizza.png') }}"
                                                        alt="title" class="imagecheck-image">
                                                </figure>
                                            </label>
                                            <h5 class="text-center">{{ __('Pizza Theme') }}</h5>
                                        </div>
                                        <div class="col-4 col-sm-4">
                                            <label class="imagecheck mb-2">
                                                <input name="theme" type="radio" value="coffee" class="imagecheck-input"
                                                    {{ !empty($abs->theme) && $abs->theme == 'coffee' ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/tenant/img/themes/coffee.png') }}"
                                                        alt="title" class="imagecheck-image">
                                                </figure>
                                            </label>
                                            <h5 class="text-center">{{ __('Coffee Theme') }}</h5>
                                        </div>
                                        <div class="col-4 col-sm-4">
                                            <label class="imagecheck mb-2">
                                                <input name="theme" type="radio" value="medicine"
                                                    class="imagecheck-input"
                                                    {{ !empty($abs->theme) && $abs->theme == 'medicine' ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/tenant/img/themes/medicine.png') }}"
                                                        alt="title" class="imagecheck-image">
                                                </figure>
                                            </label>
                                            <h5 class="text-center">{{ __('Medicine Theme') }}</h5>
                                        </div>
                                        <div class="col-4 col-sm-4">
                                            <label class="imagecheck mb-2">
                                                <input name="theme" type="radio" value="grocery"
                                                    class="imagecheck-input"
                                                    {{ !empty($abs->theme) && $abs->theme == 'grocery' ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/tenant/img/themes/grocery.png') }}"
                                                        alt="title" class="imagecheck-image">
                                                </figure>
                                            </label>
                                            <h5 class="text-center">{{ __('Grocery Theme') }}</h5>
                                        </div>
                                        <div class="col-4 col-sm-4">
                                            <label class="imagecheck mb-2">
                                                <input name="theme" type="radio" value="beverage"
                                                    class="imagecheck-input"
                                                    {{ !empty($abs->theme) && $abs->theme == 'beverage' ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/tenant/img/themes/beverage.png') }}"
                                                        alt="title" class="imagecheck-image">
                                                </figure>
                                            </label>
                                            <h5 class="text-center">{{ __('Beverage Theme') }}</h5>
                                        </div>
                                        <div class="col-4 col-sm-4">
                                            <label class="imagecheck mb-2">
                                                <input name="theme" type="radio" value="seabbq"
                                                    class="imagecheck-input"
                                                    {{ !empty($abs->theme) && $abs->theme == 'seabbq' ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/tenant/img/themes/seabbq.png') }}"
                                                        alt="title" class="imagecheck-image">
                                                </figure>
                                            </label>
                                            <h5 class="text-center">{{ __('Seabbq Theme') }}</h5>
                                        </div>

                                        <div class="col-4 col-sm-4">
                                            <label class="imagecheck mb-2">
                                                <input name="theme" type="radio" value="desifoodie"
                                                    class="imagecheck-input"
                                                    {{ !empty($abs->theme) && $abs->theme == 'desifoodie' ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/tenant/img/themes/desifoodie.png') }}"
                                                        alt="title" class="imagecheck-image">
                                                </figure>
                                            </label>
                                            <h5 class="text-center">{{ __('Desifoodie Theme') }}</h5>
                                        </div>
                                        <div class="col-4 col-sm-4">
                                            <label class="imagecheck mb-2">
                                                <input name="theme" type="radio" value="desices"
                                                    class="imagecheck-input"
                                                    {{ !empty($abs->theme) && $abs->theme == 'desices' ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/tenant/img/themes/desices.png') }}"
                                                        alt="title" class="imagecheck-image">
                                                </figure>
                                            </label>
                                            <h5 class="text-center">{{ __('Desices Theme') }}</h5>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center mb-4">
                            <button type="submit" id="submitBtn" class="btn btn-success px-4 py-2">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>

                    <hr>

                    <div class="row pt-3">
                        <!-- Button 1: Theme Styles Demo Data -->
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light border p-3 h-100 shadow-none">
                                <p class="text-muted mb-3 font-weight-bold">
                                    {{ __('Click on this button to import themes demo data.') }}
                                </p>
                                <div class="mt-auto">
                                    <button type="button" id="importThemeStyleBtn" class="btn btn-primary btn-block">
                                        <i class="fas fa-file-import mr-1"></i> {{ __('Import') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Button 2: Product Categories Demo Data -->
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light border p-3 h-100 shadow-none">
                                <p class="text-muted mb-3 font-weight-bold">
                                    {{ __('Click on this button to import product categories demo data.') }}
                                </p>
                                <div class="mt-auto">
                                    <button type="button" id="importProductCategoriesBtn" class="btn btn-info btn-block">
                                        <i class="fas fa-boxes mr-1"></i> {{ __('Import') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            function themeExtrafeature() {
                const selectedTheme = $('input[name="theme"]:checked').val();
                if (selectedTheme == "multipurpose") {
                    $('.Home_version').css({
                        'display': 'block'
                    });
                } else {
                    $('.Home_version').css({
                        'display': 'none'
                    });
                }
            }
            themeExtrafeature();

            $(".imagecheck").on("change", function() {
                themeExtrafeature();
            });

            // Button 1: Import Theme Styles Demo Data
            $('#importThemeStyleBtn').on('click', function(e) {
                e.preventDefault();
                const selectedTheme = $('input[name="theme"]:checked').val();
                if (!selectedTheme) {
                    bootnotify("Please select a theme first", "Warning", "warning");
                    return;
                }

                swal({
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __('This will import the demo style, banners, hero text, and settings for the selected theme.') }}",
                    type: 'warning',
                    buttons: {
                        cancel: {
                            visible: true,
                            text: "{{ __('Cancel') }}",
                            className: 'btn btn-danger'
                        },
                        confirm: {
                            text: "{{ __('Yes, Import It') }}",
                            className: 'btn btn-success'
                        }
                    }
                }).then((willImport) => {
                    if (willImport) {
                        const btn = $('#importThemeStyleBtn');
                        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> {{ __("Importing...") }}');

                        $.ajax({
                            url: "{{ route('user.theme.import_style') }}",
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                theme: selectedTheme
                            },
                            success: function(response) {
                                btn.prop('disabled', false).html('<i class="fas fa-file-import mr-1"></i> {{ __("Import") }}');
                                location.reload();
                            },
                            error: function(err) {
                                btn.prop('disabled', false).html('<i class="fas fa-file-import mr-1"></i> {{ __("Import") }}');
                                bootnotify("Something went wrong", "Error", "danger");
                            }
                        });
                    }
                });
            });

            // Button 2: Import Product Categories Demo Data
            $('#importProductCategoriesBtn').on('click', function(e) {
                e.preventDefault();
                const selectedTheme = $('input[name="theme"]:checked').val();
                if (!selectedTheme) {
                    bootnotify("Please select a theme first", "Warning", "warning");
                    return;
                }

                swal({
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __('This will import demo product categories and items for the selected theme while preserving your custom created products.') }}",
                    type: 'warning',
                    buttons: {
                        cancel: {
                            visible: true,
                            text: "{{ __('Cancel') }}",
                            className: 'btn btn-danger'
                        },
                        confirm: {
                            text: "{{ __('Yes, Import It') }}",
                            className: 'btn btn-info'
                        }
                    }
                }).then((willImport) => {
                    if (willImport) {
                        const btn = $('#importProductCategoriesBtn');
                        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> {{ __("Importing...") }}');

                        $.ajax({
                            url: "{{ route('user.theme.import_products') }}",
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                theme: selectedTheme
                            },
                            success: function(response) {
                                btn.prop('disabled', false).html('<i class="fas fa-boxes mr-1"></i> {{ __("Import") }}');
                                location.reload();
                            },
                            error: function(err) {
                                btn.prop('disabled', false).html('<i class="fas fa-boxes mr-1"></i> {{ __("Import") }}');
                                bootnotify("Something went wrong", "Error", "danger");
                            }
                        });
                    }
                });
            });

        });
    </script>
@endsection
