e.preventDefault();
Swal.fire({
  title: "@lang('gui.swal_conf_title')",
  text: "@lang('gui.swal_conf_text_delete')",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  confirmButtonText: "@lang('gui.swal_conf_yes')",
  cancelButtonColor: '#d33',
  cancelButtonText:"@lang('gui.swal_conf_no')",
}).then((result) => {
  if (result.isConfirmed) {
    $(this).unbind('submit').submit()
  }
})
