@extends('layouts.share')

@section('content')

<section class="container mx-auto" style="max-width: 800px">
    @if(isset($wishlist) && !empty($wishlist))
        <p>
            <span class="h4">{{$wishlist->user->name}}</span> @lang('gui.wl_share_title')
        </p>
        <div class="card">
            <h4 style="color: white; background: cadetblue;text-align: center" class="card-header">
                {{$wishlist->name}}
            </h4>
            <ul class="list-group list-group-flush">
                @foreach($wishlist->items as $item)
                    <li class="list-group-item">
                        <span style="font-size: 13pt">{{$item->name}}</span>
                        <span class="float-end" style="margin-top: 5px; font-size: 10pt; color: grey">{{formatAmount($item->gross)}}</span>
                    </li>
                @endforeach
            </ul>
            <div class="card-footer">
                <small class="text-muted float-end fw-bold">{{formatAmount($wishlist->sumGross())}}</small>
            </div>
        </div>
    @else
        <div class="alert alert-warning" role="alert">
            @lang('gui.wl_share_not_found')
        </div>
    @endif
    <div style="text-align: center" class="mt-3">
        <a style="text-decoration: none; font-size: 10pt" href="/">@lang('gui.wl_share_footer_text')</a>
    </div>
</section>

@endsection
