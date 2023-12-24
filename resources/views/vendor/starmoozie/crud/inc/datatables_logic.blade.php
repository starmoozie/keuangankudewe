@php
    $filter_page_has_footer = array_filter($page_has_footer, fn($item) => "{$item}.index" === $current_route);
    $current_page           = count($filter_page_has_footer) ? reset($filter_page_has_footer) : "";

    // as it is possible that we can be redirected with persistent table we save the alerts in a variable
    // and flush them from session, so we will get them later from localStorage.
    $starmoozie_alerts = \Alert::getMessages();
    \Alert::flush();
@endphp


{{-- DATA TABLES SCRIPT --}}
<script type="text/javascript" src="{{ asset('packages/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/datatables.net-fixedheader-bs4/js/fixedHeader.bootstrap4.min.js') }}"></script>

<script>
    // here we will check if the cached dataTables paginator length is conformable with current paginator settings.
    // datatables caches the ajax responses with pageLength in LocalStorage so when changing this
    // settings in controller users get unexpected results. To avoid that we will reset
    // the table cache when both lengths don't match.
    let $dtCachedInfo = JSON.parse(localStorage.getItem('DataTables_crudTable_/{{$crud->getRoute()}}'))
        ? JSON.parse(localStorage.getItem('DataTables_crudTable_/{{$crud->getRoute()}}')) : [];
    var $dtDefaultPageLength = {{ $crud->getDefaultPageLength() }};
    let $dtStoredPageLength = localStorage.getItem('DataTables_crudTable_/{{$crud->getRoute()}}_pageLength');

    if(!$dtStoredPageLength && $dtCachedInfo.length !== 0 && $dtCachedInfo.length !== $dtDefaultPageLength) {
        localStorage.removeItem('DataTables_crudTable_/{{$crud->getRoute()}}');
    }

    // in this page we allways pass the alerts to localStorage because we can be redirected with
    // persistent table, and this way we guarantee non-duplicate alerts.
    $oldAlerts = JSON.parse(localStorage.getItem('starmoozie_alerts'))
        ? JSON.parse(localStorage.getItem('starmoozie_alerts')) : {};

    $newAlerts = @json($starmoozie_alerts);

    Object.entries($newAlerts).forEach(function(type) {
        if(typeof $oldAlerts[type[0]] !== 'undefined') {
            type[1].forEach(function(msg) {
                $oldAlerts[type[0]].push(msg);
            });
        } else {
            $oldAlerts[type[0]] = type[1];
        }
    });

    // always store the alerts in localStorage for this page
    localStorage.setItem('starmoozie_alerts', JSON.stringify($oldAlerts));

    @if ($crud->getPersistentTable())

        var saved_list_url = localStorage.getItem('{{ Str::slug($crud->getRoute()) }}_list_url');

        //check if saved url has any parameter or is empty after clearing filters.
        if (saved_list_url && saved_list_url.indexOf('?') < 1) {
            var saved_list_url = false;
        } else {
            var persistentUrl = saved_list_url+'&persistent-table=true';
        }

    var arr = window.location.href.split('?');
    // check if url has parameters.
    if (arr.length > 1 && arr[1] !== '') {
        // IT HAS! Check if it is our own persistence redirect.
        if (window.location.search.indexOf('persistent-table=true') < 1) {
            // IF NOT: we don't want to redirect the user.
            saved_list_url = false;
        }
    }

    @if($crud->getPersistentTableDuration())
        var saved_list_url_time = localStorage.getItem('{{ Str::slug($crud->getRoute()) }}_list_url_time');

        if (saved_list_url_time) {
            var $current_date = new Date();
            var $saved_time = new Date(parseInt(saved_list_url_time));
            $saved_time.setMinutes($saved_time.getMinutes() + {{$crud->getPersistentTableDuration()}});

            // if the save time is not expired we force the filter redirection.
            if($saved_time > $current_date) {
                if (saved_list_url && persistentUrl!=window.location.href) {
                    window.location.href = persistentUrl;
                }
            } else {
                // persistent table expired, let's not redirect the user
                saved_list_url = false;
            }
        }

    @endif
        if (saved_list_url && persistentUrl!=window.location.href) {
            // finally redirect the user.
            window.location.href = persistentUrl;
        }
    @endif

    window.crud = {
        exportButtons: JSON.parse('{!! json_encode($crud->get('list.export_buttons')) !!}'),
        functionsToRunOnDataTablesDrawEvent: [],
        addFunctionToDataTablesDrawEventQueue: function (functionName) {
            if (this.functionsToRunOnDataTablesDrawEvent.indexOf(functionName) == -1) {
                this.functionsToRunOnDataTablesDrawEvent.push(functionName);
            }
        },
        responsiveToggle: function(dt) {
            $(dt.table().header()).find('th').toggleClass('all');
            dt.responsive.rebuild();
            dt.responsive.recalc();
        },
        executeFunctionByName: function(str, args) {
            var arr = str.split('.');
            var fn = window[ arr[0] ];

            for (var i = 1; i < arr.length; i++)
            { fn = fn[ arr[i] ]; }
            fn.apply(window, args);
        },
        updateUrl : function (new_url) {
            url_start = "{{ url($crud->route) }}";
            url_end = new_url.replace(url_start, '');
            url_end = url_end.replace('/search', '');
            new_url = url_start + url_end;

            window.history.pushState({}, '', new_url);
            localStorage.setItem('{{ Str::slug($crud->getRoute()) }}_list_url', new_url);
        },
        dataTableConfiguration: {
            bInfo: {{ var_export($crud->getOperationSetting('showEntryCount') ?? true) }},
            @if ($crud->getResponsiveTable())
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal( {
                            header: function ( row ) {
                                // show the content of the first column
                                // as the modal header
                                // var data = row.data();
                                // return data[0];
                                return '';
                            }
                        } ),
                        renderer: function ( api, rowIdx, columns ) {

                            var data = $.map( columns, function ( col, i ) {
                                var columnHeading = crud.table.columns().header()[col.columnIndex];

                                // hide columns that have VisibleInModal false
                                if ($(columnHeading).attr('data-visible-in-modal') == 'false') {
                                    return '';
                                }

                                return '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                            '<td style="vertical-align:top; border:none;"><strong>'+col.title.trim()+':'+'<strong></td> '+
                                            '<td style="padding-left:10px;padding-bottom:10px; border:none;">'+col.data+'</td>'+
                                        '</tr>';
                            } ).join('');

                            return data
                                ? $('<table class="table table-striped mb-0">').append( '<tbody>' + data + '</tbody>' )
                                : false;
                        },
                    }
                },
                columnDefs: [
                    { responsivePriority: 1, targets: 1 },
                ],
                fixedHeader: true,
            @else
                responsive: false,
                scrollX: true,
            @endif

            @if ($crud->getPersistentTable())
                stateSave: true,
                /*
                    if developer forced field into table 'visibleInTable => true' we make sure when saving datatables state
                    that it reflects the developer decision.
                */
                stateSaveParams: function(settings, data) {

                    localStorage.setItem('{{ Str::slug($crud->getRoute()) }}_list_url_time', data.time);

                    data.columns.forEach(function(item, index) {
                        var columnHeading = crud.table.columns().header()[index];
                        if ($(columnHeading).attr('data-visible-in-table') == 'true') {
                            return item.visible = true;
                        }
                    });
                },
                @if($crud->getPersistentTableDuration())
                    stateLoadParams: function(settings, data) {
                        var $saved_time = new Date(data.time);
                        var $current_date = new Date();

                        $saved_time.setMinutes($saved_time.getMinutes() + {{$crud->getPersistentTableDuration()}});

                        //if the save time as expired we force datatabled to clear localStorage
                        if($saved_time < $current_date) {
                            if (localStorage.getItem('{{ Str::slug($crud->getRoute())}}_list_url')) {
                                localStorage.removeItem('{{ Str::slug($crud->getRoute()) }}_list_url');
                            }
                            if (localStorage.getItem('{{ Str::slug($crud->getRoute())}}_list_url_time')) {
                                localStorage.removeItem('{{ Str::slug($crud->getRoute()) }}_list_url_time');
                            }
                        return false;
                        }
                    },
                @endif
            @endif
            autoWidth: false,
            pageLength: $dtDefaultPageLength,
            lengthMenu: @json($crud->getPageLengthMenu()),

            /* Disable initial sort */
            aaSorting: [],
            language: {
                "emptyTable":     "{{ trans('starmoozie::crud.emptyTable') }}",
                "info":           "{{ trans('starmoozie::crud.info') }}",
                "infoEmpty":      "{{ trans('starmoozie::crud.infoEmpty') }}",
                "infoFiltered":   "{{ trans('starmoozie::crud.infoFiltered') }}",
                "infoPostFix":    "{{ trans('starmoozie::crud.infoPostFix') }}",
                "thousands":      "{{ trans('starmoozie::crud.thousands') }}",
                "lengthMenu":     "{{ trans('starmoozie::crud.lengthMenu') }}",
                "loadingRecords": "{{ trans('starmoozie::crud.loadingRecords') }}",
                "processing":     "<img src='{{ asset('packages/starmoozie/base/img/spinner.svg') }}' alt='{{ trans('starmoozie::crud.processing') }}'>",
                "search": "_INPUT_",
                "searchPlaceholder": "{{ trans('starmoozie::crud.search') }}...",
                "zeroRecords":    "{{ trans('starmoozie::crud.zeroRecords') }}",
                "paginate": {
                    "first":      "{{ trans('starmoozie::crud.paginate.first') }}",
                    "last":       "{{ trans('starmoozie::crud.paginate.last') }}",
                    "next":       ">",
                    "previous":   "<"
                },
                "aria": {
                    "sortAscending":  "{{ trans('starmoozie::crud.aria.sortAscending') }}",
                    "sortDescending": "{{ trans('starmoozie::crud.aria.sortDescending') }}"
                },
                "buttons": {
                    "copy":   "{{ trans('starmoozie::crud.export.copy') }}",
                    "excel":  "{{ trans('starmoozie::crud.export.excel') }}",
                    "csv":    "{{ trans('starmoozie::crud.export.csv') }}",
                    "pdf":    "{{ trans('starmoozie::crud.export.pdf') }}",
                    "print":  "{{ trans('starmoozie::crud.export.print') }}",
                    "colvis": "{{ trans('starmoozie::crud.export.column_visibility') }}"
                },
            },
            processing: true,
            serverSide: true,
            searching: @json($crud->getOperationSetting('searchableTable') ?? true),
            ajax: {
                "url": "{!! url($crud->route.'/search').'?'.Request::getQueryString() !!}",
                "type": "POST",
                "data": {
                    "unfilteredQueryCount": "{{$crud->getOperationSetting('unfilteredQueryCount') ?? false}}"
                },
            },
            dom:
                "<'row hidden'<'col-sm-6'i><'col-sm-6 d-print-none'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row mt-2 d-print-none '<'col-sm-12 col-md-4'l><'col-sm-0 col-md-4 text-center'B><'col-sm-12 col-md-4 'p>>",

            footerCallback: function (row, data, start, end, display) {
                const current_page = "{{ $current_page }}";
                let api            = this.api();
                let amount         = 0;
                let column_number  = 5; // index kolom jumlah kalkulasi yang akan ditampilkan

                if (current_page) {
                    switch (current_page) {
                        case "report":
                            column_number          = [column_number];
                            const index_start_from = 3; // index kolom dikalkulasi mulai index 3

                            let balance = 0;
                            for (let index = index_start_from; index <= column_number[column_number.length - 1]; index++) {
                                if (index < column_number[0]) {
                                    amount = sumRow(api, index);
                                    balance = index === index_start_from ? amount : balance - amount;
                                } else {
                                    amount = balance;
                                }

                                showRowValue(api, index, amount);
                            }
                        break;

                        case "bank":
                            column_number = 2;
                            amount        = sumRow(api, column_number);

                            showRowValue(api, column_number, amount);
                        break;

                        default: // Income & Expense
                            amount = sumRow(api, column_number);

                            showRowValue(api, column_number, amount);
                        break;
                    }
                }
            },
        }
    }

    const showRowValue = (api, index, amount) => $(api.column(index).footer()).html(formatRupiah(amount));

    const formatRupiah = (money) => {
        const rupiah = new Intl.NumberFormat(
            'id-ID',
            {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(money);

        return rupiah.replace('Rp', '');
    }

    const sumRow = (api, rowNumber) => {
        return api
            .column(rowNumber)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b.replace(/\D/g, ''));
            }, 0);
    }

    // Remove the formatting to get integer data for summation
    const intVal = i => typeof i === 'string'
        ? i.replace(/[\$,]/g, '') * 1
        : typeof i === 'number' ? i : 0;

</script>

@include('crud::inc.export_buttons')

<script type="text/javascript">
    jQuery(document).ready(function($) {
        const current_page = "{{ $current_page }}";

        window.crud.table = $("#crudTable").DataTable(window.crud.dataTableConfiguration);

        // move search bar
        $("#crudTable_filter").appendTo($('#datatable_search_stack' ));
        $("#crudTable_filter input").removeClass('form-control-sm');

        // move "showing x out of y" info to header
        @if($crud->getSubheading())
            $('#crudTable_info').hide();
        @else
            $("#datatable_info_stack").html($('#crudTable_info')).css('display','inline-flex').addClass('animated fadeIn');
        @endif

        @if($crud->getOperationSetting('resetButton') ?? true)
            // create the reset button
            var crudTableResetButton = '<a href="{{url($crud->route)}}" class="ml-1" id="crudTable_reset_button">{{ trans('starmoozie::crud.reset') }}</a>';

            $('#datatable_info_stack').append(crudTableResetButton);

            // when clicking in reset button we clear the localStorage for datatables.
            $('#crudTable_reset_button').on('click', function() {

            //clear the filters
            if (localStorage.getItem('{{ Str::slug($crud->getRoute())}}_list_url')) {
                localStorage.removeItem('{{ Str::slug($crud->getRoute()) }}_list_url');
            }
            if (localStorage.getItem('{{ Str::slug($crud->getRoute())}}_list_url_time')) {
                localStorage.removeItem('{{ Str::slug($crud->getRoute()) }}_list_url_time');
            }

            //clear the table sorting/ordering/visibility
            if(localStorage.getItem('DataTables_crudTable_/{{ $crud->getRoute() }}')) {
                localStorage.removeItem('DataTables_crudTable_/{{ $crud->getRoute() }}');
            }
            });
        @endif

        // move the bottom buttons before pagination
        $("#bottom_buttons").insertBefore($('#crudTable_wrapper .row:last-child' ));

        // override ajax error message
        $.fn.dataTable.ext.errMode = 'none';
        $('#crudTable').on('error.dt', function(e, settings, techNote, message) {
            new Noty({
                type: "error",
                text: "<strong>{{ trans('starmoozie::crud.ajax_error_title') }}</strong><br>{{ trans('starmoozie::crud.ajax_error_text') }}"
            }).show();
        });

            // when changing page length in datatables, save it into localStorage
            // so in next requests we know if the length changed by user
            // or by developer in the controller.
            $('#crudTable').on( 'length.dt', function ( e, settings, len ) {
                localStorage.setItem('DataTables_crudTable_/{{$crud->getRoute()}}_pageLength', len);
            });

        // make sure AJAX requests include XSRF token
        $.ajaxPrefilter(function(options, originalOptions, xhr) {
            var token = $('meta[name="csrf_token"]').attr('content');

            if (token) {
                    return xhr.setRequestHeader('X-XSRF-TOKEN', token);
            }
        });

        // on DataTable draw event run all functions in the queue
        // (eg. delete and details_row buttons add functions to this queue)
        $('#crudTable').on( 'draw.dt',   function () {
            crud.functionsToRunOnDataTablesDrawEvent.forEach(function(functionName) {
                crud.executeFunctionByName(functionName);
            });

            // change line button to dropdown
            if ($('#crudTable').data('has-line-buttons-as-dropdown')) {
                formatActionColumnAsDropdown();
            }

            // Calculate balance each row
            if (current_page === "report") {
                calculateBalanceEachRow()
            }

        } ).dataTable();

        // when datatables-colvis (column visibility) is toggled
        // rebuild the datatable using the datatable-responsive plugin
        $('#crudTable').on( 'column-visibility.dt',   function (event) {
            crud.table.responsive.rebuild();
        } ).dataTable();

        @if ($crud->getResponsiveTable())
            // when columns are hidden by reponsive plugin,
            // the table should have the has-hidden-columns class
            crud.table.on( 'responsive-resize', function ( e, datatable, columns ) {
                if (crud.table.responsive.hasHidden()) {
                    $("#crudTable").removeClass('has-hidden-columns').addClass('has-hidden-columns');
                } else {
                    $("#crudTable").removeClass('has-hidden-columns');
                }
            } );
        @else
            // make sure the column headings have the same width as the actual columns
            // after the user manually resizes the window
            var resizeTimer;
            function resizeCrudTableColumnWidths() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    // Run code here, resizing has "stopped"
                    crud.table.columns.adjust();
                }, 250);
            }
            $(window).on('resize', function(e) {
                resizeCrudTableColumnWidths();
            });
            $('.sidebar-toggler').click(function() {
                resizeCrudTableColumnWidths();
            });
        @endif
    });

    const formatActionColumnAsDropdown = () => {
        // Get action column
        const actionColumnIndex = $('#crudTable').find('th[data-action-column=true]').index();
        if (actionColumnIndex !== -1) {
            $('#crudTable tr').each(function (i, tr) {
                const actionCell = $(tr).find('td').eq(actionColumnIndex);
                const actionButtons = $(actionCell).find('a.btn.line');
                // Wrap the cell with the component needed for the dropdown
                actionCell.wrapInner('<div class="nav-item dropdown"></div>');
                actionCell.wrapInner('<div class="dropdown-menu dropdown-menu-left"></div>');
                // Prepare buttons as dropdown items
                actionButtons.map((index, action) => {
                    $(action).addClass('dropdown-item').removeClass('btn btn-sm btn-link shadow-sm');
                    $(action).find('i').addClass('me-2 text-primary');
                });
                actionCell.prepend('<a class="btn btn-sm px-2 py-1 btn-outline-primary dropdown-toggle actions-buttons-column" href="#" data-toggle="dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">{{ trans('starmoozie::crud.actions') }}</a>');
            });
        }
    }

    // Calculate balance each row
    const calculateBalanceEachRow = () => {
        let transactions = [];

        crud.table
            .rows()
            .every( function ( rowIdx, tableLoop, rowLoop ) {
                let data = this.data();

                transactions.push( [this.index(), data[3], data[4]] );
            } );

        let total = 0;

        for (i = 0; i < transactions.length; i++) {
            const data    = transactions[i];
            const rowIdx  = data[0];
            const income  = intVal(data[1].replace(/\D/g, ''));
            const expense = intVal(data[2].replace(/\D/g, ''));

            const cell   = crud.table.cell( rowIdx, 5);
                total   += income - expense;

            cell.data( `<div class="text-right">${formatRupiah(total)}</div>` );
        }
    }
</script>

@include('crud::inc.details_row_logic')
