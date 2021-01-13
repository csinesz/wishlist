@extends('layouts.gui')

@section('content')

<section class="container mx-auto" style="max-width: 800px">
    <form name="delete_form" action="{{route('wishlists.delete', $wishlist)}}" method="POST" id="delete_form">
        @csrf
        @method('DELETE')
    </form>
{{--    <div class="row mb-2">--}}
{{--        <div class="col">--}}
{{--            <button class="btn btn-sm btn-outline-success" class="create-item">@lang('gui.wi_add')</button>--}}
{{--        </div>--}}
{{--    </div>--}}

    <section class="col" id="wish-container">

    </section>
    <div class="mt-4" style="text-align: center">
        <a href="{{route('wishlists.index')}}" style="text-decoration: none; color: grey; font-size: 10pt">
            <i class="fas fa-chevron-left"></i>
            @lang('gui.wl_back')
        </a>
    </div>


</section>

@endsection


@section('js_extend')
    <script>

        @include('includes.copy_to_clipboard_js');

        function getWishlist() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "GET",
                url: '{{route('wishlist.get', $wishlist)}}',
                dataType: 'json',
                error: function (jqXHR, textStatus, errorThrown) {
                    @include('includes.alerts.error_ajax')
                },
                success: function(ret) {
                    $('#wish-container').empty();

                    var listRow= '<div class="card">' +
                        '<h4 style="color: white; text-align: center; background: cadetblue" class="card-header"><span id="update-list" style="cursor: pointer">'+ret.name+'</span>' +
                            '<i data-hash="'+ret.hash+'" class="share_link far fa-share-square float-end" style="color: blanchedalmond; font-size: 12pt; margin-top: 5px; cursor: pointer;"></i>' +
                        '</h4>' +
                        '<ul class="list-group list-group-flush">';
                            $.each(ret.items,function(key, value) {
                                listRow+='<li class="list-group-item"><span data-itemid="'+value.i_wish_item+'" style="cursor:pointer; font-size: 13pt;" class="edit-item">'+value.name+'</span>' +
                                    '<span class="float-end" style="font-size: 10pt; color: grey; margin-top: 5px">'+numberFormat(value.gross)+'' +
                                    '<i data-itemid="'+value.i_wish_item+'" style="color: red; margin-left: 10px; cursor:pointer; font-size: 13pt" class="delete-item far fa-trash-alt"></i></span></li>';
                            });
                            listRow+='<li class="list-group-item create-item" style="cursor:pointer;"><i class="fas fa-plus" style="color: green"></i></a></li>';

                    listRow+='</ul>' +
                        '<div class="card-footer">' +
                        '<small class="text-muted fst-italic" style="font-size: 8pt">'+ret.created_at.split(' ')[0]+'</small>';
                    if(ret.items.length > 0) {
                        listRow += '<small class="text-muted float-end fw-bold">' + numberFormat(ret.sumGross) + '</small>';
                    }

                    listRow+='</div>' +
                        '</div>';

                    $('#wish-container').append(listRow);

                    $('html, body').animate({ scrollTop: 0 }, 'fast');

                    @include('includes.share_link_js')

                    $('.create-item').on('click', function(e) {
                        addWishItem()
                    });

                    function addWishItem() {
                        Swal.fire({
                              title: "@lang('gui.wi_add')",
                              html:
                                '<div class="form-floating mb-3"><input required type="text" class="form-control" name="name" id="name" placeholder="" value=""><label for="name">@lang('gui.wi_name')</label></div>'+
                                '<div class="form-floating mb-3"><input required type="text" class="form-control number_only" name="gross" id="gross" placeholder="" value=""><label for="gross">@lang('gui.wi_gross')</label></div>',
                              showCancelButton: true,
                              cancelButtonText: '@lang('gui.cancel')',
                              confirmButtonText: '@lang('gui.add_action')',
                              confirmButtonColor: '#146e44',
                              showLoaderOnConfirm: true,
                              willOpen: () => {
                                  $(".number_only").inputFilter(function(value) {
                                    return /^\d*$/.test(value);
                                  });
                              },
                              preConfirm: () => {
                                 $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });

                                $.ajax({
                                    type: "POST",
                                    url: '{{route('wishitem.store')}}',
                                    data: {
                                        'name': $('#name').val(),
                                        'gross': $('#gross').val(),
                                        'wishlist': {{$wishlist->i_wishlist}},
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
                                            getWishlist();
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
                                        }
                                    }
                                });

                              },
                              allowOutsideClick: () => !Swal.isLoading()
                        });
                    }

                    $('.edit-item').on('click', function(e){
                        var itemID = $(this).data('itemid');
                        var wishList = $(this).data('wishlistid');
                        getWishItem(itemID, wishList);
                    });


                    $('.delete-item').on('click', function(e) {
                        var wishItem = $(this).data('itemid');
                        deleteItem(wishItem)
                    });

                    function deleteItem(wishItemId) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            type: "DELETE",
                            url: '{{route('wishitem.delete', ['wish_item' => '%itemid%'])}}'.replace('%itemid%',wishItemId),
                            dataType: 'json',
                            error: function (jqXHR, textStatus, errorThrown) {
                                @include('includes.alerts.error_ajax')
                            },
                            success: function(ret) {
                                Swal.fire({
                                    icon: 'success',
                                    title: "@lang('gui.swal_success_delete')",
                                    text: "",
                                    showConfirmButton: false,
                                    timer: 3000,
                                });
                                getWishlist();
                            },
                        });

                    }

                    function getWishItem(wishItemId) {

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            type: "GET",
                            url: '{{route('wishitem.get', ['wish_item' => '%itemid%'])}}'.replace('%itemid%',wishItemId),
                            dataType: 'json',
                            error: function (jqXHR, textStatus, errorThrown) {
                                @include('includes.alerts.error_ajax')
                            },
                            success: function(ret) {

                                Swal.fire({
                                  title: "@lang('gui.wi_update')",
                                  html:
                                    '<div class="form-floating mb-3"><input required type="text" class="form-control" name="name" id="name" placeholder="" value="'+$('<div/>').html(ret.name).text()+'"><label for="name">@lang('gui.wi_name')</label></div>'+
                                    '<div class="form-floating mb-3"><input required type="text" class="form-control number_only" name="gross" id="gross" placeholder="" value="'+$('<div/>').html(ret.gross).text()+'"><label for="name">@lang('gui.wi_gross')</label></div>',
                                  showCancelButton: true,
                                  cancelButtonText: '@lang('gui.cancel')',
                                  confirmButtonText: '@lang('gui.update_action')',
                                  confirmButtonColor: '#146e44',
                                  showLoaderOnConfirm: true,
                                  footer: '<span style="font-size: 10pt">@lang('gui.wi_updated_at') '+ret.updated_at+'</span>',
                                  willOpen: () => {
                                      $(".number_only").inputFilter(function(value) {
                                        return /^\d*$/.test(value);
                                      });
                                  },
                                  preConfirm: () => {
                                     $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        }
                                    });

                                    $.ajax({
                                        type: "PUT",
                                        url: '{{route('wishitem.update', ['wish_item' => '%itemid%'])}}'.replace('%itemid%',wishItemId),
                                        data: {
                                            'name': $('#name').val(),
                                            'gross': $('#gross').val(),
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
                                                    title: "@lang('gui.swal_success_update')",
                                                    text: "",
                                                    showConfirmButton: false,
                                                    timer: 3000,
                                                });
                                                getWishlist();
                                            }
                                            else {
                                                var errors = '';
                                                $.each(ret.errors,function(key, value) {
                                                    errors+='<p>'+value+'</p>';
                                                });

                                                Swal.fire({
                                                    icon: 'error',
                                                    title: "@lang('gui.swal_error_update')",
                                                    html: errors,
                                                    showConfirmButton: false,
                                                    timer: 3000,
                                                });
                                            }
                                            return true;
                                        }
                                    });

                                  },
                                  allowOutsideClick: () => !Swal.isLoading()
                                });
                            },
                        });
                    }

                    $('#update-list').on('click', function(e) {
                        updateWishList()
                    });

                    function updateWishList() {
                        var wishlistName = "{{$wishlist->name}}";
                        Swal.fire({
                          title: "@lang('gui.wl_update')",
                          html:
                            '<div class="form-floating mb-3">' +
                              '<input required type="text" class="form-control" name="name" id="name" value="'+$('<div/>').html(wishlistName).text()+'">' +
                            '<label for="name">@lang('gui.wl_name')</label></div>',
                          showCancelButton: true,
                          cancelButtonText: '@lang('gui.cancel')',
                          confirmButtonText: '@lang('gui.update_action')',
                          confirmButtonColor: '#146e44',
                          showLoaderOnConfirm: true,
                          footer:'<div id="deleteButton" style="margin-top: 10px; color: red; font-size: 9pt; cursor: pointer;" class="float-end"><i class="far fa-trash-alt"></i> @lang('gui.wl_delete')</div>',
                          willOpen: () => {
                            $('#deleteButton').click(function(e) {
                                $('#delete_form').submit();
                            });
                          },
                          preConfirm: () => {
                             $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });

                            $.ajax({
                                type: "PUT",
                                url: '{{route('wishlists.update',$wishlist)}}',
                                data: {
                                    'name': $('#name').val(),
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
                                            title: "@lang('gui.swal_success_update')",
                                            text: "",
                                            showConfirmButton: false,
                                            timer: 3000,
                                        });
                                        getWishlist();
                                    }
                                    else {
                                        var errors = '';
                                        $.each(ret.errors,function(key, value) {
                                            errors+='<p>'+value+'</p>';
                                        });

                                        Swal.fire({
                                            icon: 'error',
                                            title: "@lang('gui.swal_error_update')",
                                            html: errors,
                                            showConfirmButton: false,
                                            timer: 3000,
                                        });
                                    }
                                    return true;
                                }
                            });

                          },
                          allowOutsideClick: () => !Swal.isLoading()
                        });
                    }

                },
            });
        }

        $(document).ready(function () {
             (function($) {
              $.fn.inputFilter = function(inputFilter) {
                return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
                  if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                  } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                  } else {
                    this.value = "";
                  }
                });
              };
            }(jQuery));
            getWishlist();
        });

        @include('includes.number_format_js');

        $('#delete_form').submit(function(e) {
            @include('includes.alerts.confirmation_delete')
        });

    </script>

@endsection

