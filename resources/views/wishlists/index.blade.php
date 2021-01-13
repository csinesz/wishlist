@extends('layouts.gui')

@section('content')

<section class="container">
    <button id="new_list" class="btn btn-sm btn-outline-success mb-3">@lang('gui.wl_new')</button>
    <div class="row row-cols-1 row-cols-md-3 g-4" id="wish-container">
    </div>
</section>

@endsection


@section('js_extend')
    <script>

        @include('includes.copy_to_clipboard_js');

        @include('includes.number_format_js');

        $('#new_list').on('click', function(e){
            Swal.fire({
              title: "@lang('gui.wl_new')",
              input: 'text',
              inputAttributes: {
                autocapitalize: 'off',
                  placeholder: "@lang('gui.wl_name')"
              },
              showCancelButton: true,
              cancelButtonText: '@lang('gui.cancel')',
              confirmButtonText: '@lang('gui.add_action')',
              confirmButtonColor: '#146e44',
              showLoaderOnConfirm: true,
              preConfirm: (wishListName) => {
                 $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: '{{route('wishlists.store')}}',
                    data: {
                        'name': wishListName,
                    },
                    dataType: 'json',
                    error: function(jqXHR, textStatus, errorThrown) {
                        @include('includes.alerts.error_ajax');
                        return false;
                    },
                    success: function(ret) {
                        if (ret.errors == undefined) {
                            Swal.fire({
                                icon: 'success',
                                title: "@lang('gui.swal_success_create')",
                                text: "",
                                showConfirmButton: false,
                                timer: 3000,
                            });
                            getWishlists()
                            return true;
                        }
                        else {
                            var errors = '';
                            $.each(ret.errors,function(key, value) {
                                errors+='<p>'+value+'</p>';
                            });

                            Swal.fire({
                                icon: 'error',
                                title: "@lang('gui.swal_error_create')",
                                html: errors,
                                showConfirmButton: false,
                                timer: 3000,
                            });
                            return false;
                        }

                    }
                });

              },
              allowOutsideClick: () => !Swal.isLoading()
            });
        });

        function getWishlists() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "GET",
                url: '{{route('wishlists.get')}}',
                dataType: 'json',
                error: function (jqXHR, textStatus, errorThrown) {
                    @include('includes.alerts.error_ajax')
                },
                success: function(ret) {
                    $('#wish-container').empty();

                    $.each(ret,function(key, wishlist) {
                        var listRow = '';
                        var editLink = "{{route('wishlists.edit', ['wishlist' => '%id%'])}}".replace('%id%', wishlist.i_wishlist);

                         listRow+=
                             '<div class="col">' +
                                '<div class="card">' +
                                    '<h4 style="text-align: center; color: white; background: cadetblue" class="card-header">' +
                                        '<a style="color: white; text-decoration: none" href="'+editLink+'">'+wishlist.name+'</a>' +
                                        '<i data-hash="'+wishlist.hash+'" class="share_link far fa-share-square float-end" style="color: blanchedalmond; font-size: 12pt; margin-top: 5px; cursor: pointer;"></i>' +
                                    '</h4>' +
                                    '<ul class="list-group list-group-flush">';
                                        $.each(wishlist.items,function(key, wItem) {
                                            listRow+='<li class="list-group-item"><span data-itemid="'+wItem.i_wish_item+'" style="cursor:pointer; font-size: 13pt;" class="edit-item">'+wItem.name+'</>' +
                                                '<span class="float-end" style="font-size: 10pt; color: grey; margin-top: 5px">'+numberFormat(wItem.gross)+'</span>';
                                        });
                         listRow+='</ul>' +
                                   '<div class="card-footer">' +
                                       '<div class="row">' +
                                           '<div class="col-6">' +
                                               '<a href="'+editLink+'"><i class="fas fa-edit" style="color: cadetblue"></i></a>' +
                                           '</div>' +
                                           '<div class="col-6">'
                                            if(wishlist.items.length > 0) {
                         listRow +=             '<small class="text-muted float-end fw-bold">' + numberFormat(wishlist.sumGross) + '</small>';
                                            }
                         listRow +=        '</div>' +
                                       '</div>' +
                                   '</div>' +
                               '</div>';

                        $('#wish-container').append(listRow);
                    });

                    $('html, body').animate({ scrollTop: 0 }, 'fast');

                    @include('includes.share_link_js')

                },
            });
    }

        $(document).ready(function () {
            getWishlists();
        });



    </script>

@endsection

