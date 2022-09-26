<script type="text/javascript">
    var BASE_URL = "<?php echo url('/') ?>";
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('.clients').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/admin/clients/ajaxclients')}}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'company_name', name: 'company_name'},
                {data: 'package', name: 'package'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        $('body').on('click', '.manage_client', function () {
            window.location.href = BASE_URL + '/admin/clients/show/' + $(this).attr('data-id');
        });

    });
</script>