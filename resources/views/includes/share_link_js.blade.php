$('.share_link').on('click', function(e){
    var hash = $(this).data('hash');
    var shareLink = "{{route('wishlists.share', ['hash' => '%hash%'])}}".replace('%hash%', hash);
    copyLink = shareLink;

    Swal.fire({
        title: "@lang('gui.wl_share_link_title')",
        html: '<code>'+shareLink+'</code>',
        footer: '<div style="text-decoration: underline;cursor: pointer" onclick="copyToClipboard()"><i class="far fa-copy" style="font-size: 13pt"></i> @lang('gui.wl_copy_clipboard')</div>',
        showConfirmButton: false,
    });

});
