$(document).ready(function () {
    // delete user
    $(document).on('click', '.module_delete_record', function () {
        const id = $(this).data("id");
        const url = $(this).data("url");
        deleteRecordModule(id, `${url}/${id}`);
    });

    // Get user data
    let moduke_index_url = $("#moduke_index_url").val();
    var table = $('#data_table_main').DataTable({
        processing: true,
        serverSide: true,
        "sScrollX": '100%',
        scrollX: true,
        dom: 'Bfrtip',  // Position of the buttons
        buttons: [
            'excel',  // Export to Excel
            'csv',    // Export to CSV
            'pdf'     // Export to PDF
        ],
        ajax: moduke_index_url,
        "order": [],
        columnDefs: [{
            'targets': [0],
            'searchable': false,
            'orderable': false,
            'className': 'dt-body-center',
            'render': function (data, type, full, meta) {
                return '<input type="checkbox" class="dt_checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
            }
        }],
        select: {
            style: 'multi'
        },

        columns: [{
            data: 'id',
            name: 'id',
            searchable: false
        },
        {
            data: 'image',
            name: 'image',
            searchable: false,
            orderable: false,
        },
        {
            data: 'first_name',
            name: 'first_name'
        },
        {
            data: 'last_name',
            name: 'last_name'
        },
        {
            data: 'email',
            name: 'email'
        },
        {
            data: 'contact_number',
            name: 'contact_number'
        },
        {
            data: 'gender',
            name: 'gender'
        },
        {
            data: 'city_name',
            name: 'city.name'
        },
        {
            data: 'state_name',
            name: 'state.name'
        },
        {
            data: 'created_at_text',
            name: 'created_at'
        },
        {
            data: 'actions',
            name: 'actions',
            searchable: false,
            orderable: false,
        }
        ],
    });
});