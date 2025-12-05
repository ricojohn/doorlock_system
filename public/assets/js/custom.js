$(document).ready(function() {
    // For DataTables
    if($("#members-table").length != 0){
        const config = {
            dom: '',
            language: {
                emptyTable: 'No Member Available',
                zeroRecords: 'No Member Available',
                searchPlaceholder: 'Search'
            },
            pageLength: 10,
            responsive: true,
            buttons: [
                {
                    extend: 'collection',
                    text: '<i class="bi bi-file-earmark-excel"></i> Export',
                    className: 'btn btn-success btn-sm',
                    buttons: [
                        { extend: 'copy', className: 'dropdown-item' },
                        { extend: 'csv', className: 'dropdown-item' },
                        { extend: 'excel', className: 'dropdown-item' },
                        { extend: 'pdf', className: 'dropdown-item' },
                    ]
                }
            ]
        };
        
        var table = new DataTable('#members-table', config);
        table.buttons().container().appendTo('#exportButtons');
    }

    if($("#subscriptions-table").length != 0){
        const config = {
            dom: '',
            language: {
                emptyTable: 'No Subscription Available',
                zeroRecords: 'No Subscription Available',
                searchPlaceholder: 'Search'
            },
            pageLength: 10,
            responsive: true,
            buttons: [
                {
                    extend: 'collection',
                    text: '<i class="bi bi-file-earmark-excel"></i> Export',
                    className: 'btn btn-success btn-sm',
                    buttons: [
                        { extend: 'copy', className: 'dropdown-item' },
                        { extend: 'csv', className: 'dropdown-item' },
                        { extend: 'excel', className: 'dropdown-item' },
                        { extend: 'pdf', className: 'dropdown-item' },
                    ]
                }
            ]
        };
        var table = new DataTable('#subscriptions-table', config);
        table.buttons().container().appendTo('#exportButtons');
    }

    if($("#plans-table").length != 0){
        const config = {
            dom: '',
            language: {
                emptyTable: 'No Plan Available',
                zeroRecords: 'No Plan Available',
                searchPlaceholder: 'Search'
            },
            pageLength: 10,
            responsive: true,
            buttons: [
                {
                    extend: 'collection',
                    text: '<i class="bi bi-file-earmark-excel"></i> Export',
                    className: 'btn btn-success btn-sm',
                    buttons: [
                        { extend: 'copy', className: 'dropdown-item' },
                        { extend: 'csv', className: 'dropdown-item' },
                        { extend: 'excel', className: 'dropdown-item' },
                        { extend: 'pdf', className: 'dropdown-item' },
                    ]
                }
            ]
        };
        var table = new DataTable('#plans-table', config);
        table.buttons().container().appendTo('#exportButtons');
    }

    // Phone number format PH
    const contactInput = document.getElementById('phone');
    contactInput.addEventListener('input', function(e) {
        let digits = e.target.value.replace(/\D/g, ''); // only digits

        // Remove country code if user typed it manually
        if (digits.startsWith('63')) {
            digits = digits.substring(2);
        } else if (digits.startsWith('0')) {
            digits = digits.substring(1);
        }

        // Block typing more than 10 digits
        if (digits.length > 10) {
            digits = digits.substring(0, 10);
        }

        // Always format as +63xxxxxxxxxx
        e.target.value = '+63' + digits;
    });
});