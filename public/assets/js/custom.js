$(document).ready(function () {
    // For DataTables
    // Reusable function to initialize DataTable
    function initDataTable(selector, emptyMessage) {
        if ($(selector).length === 0) return;

        const config = {
            dom: '',
            language: {
                emptyTable: emptyMessage,
                zeroRecords: emptyMessage,
                searchPlaceholder: 'Search'
            },
            ordering: false,
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
            ],
        };

        const table = new DataTable(selector, config);
        table.buttons().container().appendTo('#exportButtons');
    }

    // Initialize all tables
    initDataTable('#members-table', 'No Member Available');
    // initDataTable('#subscriptions-table', 'No Subscription Available');
    initDataTable('#plans-table', 'No Plan Available');
    initDataTable('#coaches-table', 'No Coach Available');
    // initDataTable('#access-logs-table', 'No Access Log Available');

    // Phone number format PH
    const contactInput = document.getElementById('phone');
    contactInput.addEventListener('input', function (e) {
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