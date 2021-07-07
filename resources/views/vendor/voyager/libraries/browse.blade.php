@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->getTranslatedAttribute('display_name_plural'))

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="{{ $dataType->icon }}"></i> {{ $dataType->getTranslatedAttribute('display_name_plural') }}
        </h1>
        @can('add', app($dataType->model_name))
            <a>
                <button class="btn btn-sm btn-success"
        data-toggle="modal" data-target="#addAssessment" type="button">
            <i class="voyager-plus"></i> Add New
            </button>
            </a>
        @endcan
        @can('edit', app($dataType->model_name))
            @if(isset($dataType->order_column) && isset($dataType->order_display_column))
                <a href="{{ route('voyager.'.$dataType->slug.'.order') }}" class="btn btn-primary btn-add-new">
                    <i class="voyager-list"></i> <span>{{ __('voyager::bread.order') }}</span>
                </a>
            @endif
        @endcan
        @can('delete', app($dataType->model_name))
            @if($usesSoftDeletes)
                <input type="checkbox" @if ($showSoftDeleted) checked @endif id="show_soft_deletes" data-toggle="toggle" data-on="{{ __('voyager::bread.soft_deletes_off') }}" data-off="{{ __('voyager::bread.soft_deletes_on') }}">
            @endif
        @endcan
        @foreach($actions as $action)
            @if (method_exists($action, 'massAction'))
                @include('voyager::bread.partials.actions', ['action' => $action, 'data' => null])
            @endif
        @endforeach
        @include('voyager::multilingual.language-selector')
    </div>
@stop

@section('content')
@php 
$assessments = \App\Models\Assessment::all();
@endphp

<div class="page-content browse container-fluid">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @if(count($assessments) > 0)
                    @foreach($assessments as $a)
                        <div class="col-md-4" style="padding:0px 40px 40px 10px;">
                            <!-- Tabs nav -->
                            <div class="nav flex-column nav-pills shadow nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link mb-3 p-3 " id="plusButton" href="/admin/assessments/show?id={{$a->id}}">
                                <span class="font-weight-bold big"><br/>
                                <h4 style="color:#0151ce">{{$a->name}}
                                <span class="pull-right"><i onclick="return deleteAssessment(event,{{$a->id}})" class="voyager-trash"></i></h2>
                                <hr/>

                                <h5> Summary </h5></span>
                                <p style="color:black">{{$a->summary}}</p><hr/>

                                <h5> Questions </h5></span>
                                <p style="color:black">{{$a->count}}</p><hr/>

                                <h5> Created <span style="color:black">&nbsp;&nbsp;
                                {{date("d M, Y", strtotime($a->created_at))}}</span></h5>

                                </span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                    @else
                        <h4>No Assessments Available.</h4><span><a style="cursor:pointer;color:#007bff;font-weight:bold;" data-toggle="modal" data-target="#addAssessment" >
                        &nbsp;Click here</a> to create new Assessment</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

<div class="modal" id="addAssessment">
    <div class="modal-dialog">
        <form class="ajax-form" method="POST" id="createForm">
                    @csrf
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Add New Library</h4>
            <button type="button" onclick="emptyAssessment()" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="form-group">
                <label for="assessment_name">Assessment Name</label>
                <input class="form-control" type="text" name="assessment_name" id="assessment_name" /><br/>
            </div>

            <div class="form-group">
                <label for="summary">Assessment Summary</label>
            <textarea class="form-control" type="text" name="summary" id="summary"></textarea>
            </div>

        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" id="save-form" class="btn btn-success" >Create Assessment</button>
            <button type="button"  onclick="emptyAssessment();" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>

        </div>
        </form>
    </div>
</div>
@stop

@section('css')
@if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
    <link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}">
@endif
<style>
    .card-body .nav {
        border-top: 3px solid #132639;
    }
     .shadow {
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    padding: 2px 19px 19px 19px;
    }
</style>
@stop

@section('javascript')
    {{-- custom --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        function deleteAssessment(event,id){
            event.preventDefault()

            swal({
                title: "Are You Sure",
                text: "@lang('errors.deleteWarning')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('app.delete')",
                cancelButtonText: "@lang('app.cancel')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('myadmin.questions.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                window.location.reload();
                            }
                        }
                    });
                }
            });
        }

        function emptyAssessment(){
            $("#assessment_name").val("")
            $("#summary").val("")
        }

        function loadLoader(container,message){
            debugger
            if (message == undefined) {
                message = "Loading...";
            }

            var html = '<div class="loading-message"><div class="block-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>';


            if (container != undefined) { // element blocking
                var el = $(container);
                var centerY = false;
                if (el.height() <= ($(window).height())) {
                    centerY = true;
                }
                el.block({
                    message: html,
                    baseZ: 999999,
                    centerY: centerY,
                    css: {
                        top: '10%',
                        border: '0',
                        padding: '0',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: 'transparent',
                        opacity: 0.05,
                        cursor: 'wait'
                    }
                });
            } else { // page blocking
                $.blockUI({
                    message: html,
                    baseZ: 999999,
                    css: {
                        border: '0',
                        padding: '0',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: '#555',
                        opacity: 0.05,
                        cursor: 'wait'
                    }
                });
            }
        }



        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('row-id');
            swal({
                title: "@lang('errors.areYouSure')",
                text: "@lang('errors.deleteWarning')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('app.delete')",
                cancelButtonText: "@lang('app.cancel')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('myadmin.questions.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                                table._fnDraw();
                            }
                        }
                    });
                }
            });
        });

        $('#save-form').click(function () {
            loadLoader('#createForm',"Loading...")
            $.ajax({
                url: '{{route('myadmin.assessments.saveAssessment')}}',
                container: '#createForm',
                type: "POST",
                redirect: true,
                data: $('#createForm').serialize(),
                success:function(data){
                    loadLoader('#createForm',"Loading...")
                    var url = "/admin/assessments/edit?id="+data;

                    window.location.href = url;
                }
            })
        });

    </script>

    {{-- end of custom --}}
    <!-- DataTables -->
    @if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
        <script src="{{ voyager_asset('lib/js/dataTables.responsive.min.js') }}"></script>
    @endif
    <script>
        $(document).ready(function () {
            @if (!$dataType->server_side)
                var table = $('#dataTable').DataTable({!! json_encode(
                    array_merge([
                        "order" => $orderColumn,
                        "language" => __('voyager::datatable'),
                        "columnDefs" => [
                            ['targets' => 'dt-not-orderable', 'searchable' =>  false, 'orderable' => false],
                        ],
                    ],
                    config('voyager.dashboard.data_tables', []))
                , true) !!});
            @else
                $('#search-input select').select2({
                    minimumResultsForSearch: Infinity
                });
            @endif

            @if ($isModelTranslatable)
                $('.side-body').multilingual();
                //Reinitialise the multilingual features when they change tab
                $('#dataTable').on('draw.dt', function(){
                    $('.side-body').data('multilingual').init();
                })
            @endif
            $('.select_all').on('click', function(e) {
                $('input[name="row_id"]').prop('checked', $(this).prop('checked')).trigger('change');
            });
        });


        var deleteFormAction;
        $('td').on('click', '.delete', function (e) {
            $('#delete_form')[0].action = '{{ route('voyager.'.$dataType->slug.'.destroy', '__id') }}'.replace('__id', $(this).data('id'));
            $('#delete_modal').modal('show');
        });

        @if($usesSoftDeletes)
            @php
                $params = [
                    's' => $search->value,
                    'filter' => $search->filter,
                    'key' => $search->key,
                    'order_by' => $orderBy,
                    'sort_order' => $sortOrder,
                ];
            @endphp
            $(function() {
                $('#show_soft_deletes').change(function() {
                    if ($(this).prop('checked')) {
                        $('#dataTable').before('<a id="redir" href="{{ (route('voyager.'.$dataType->slug.'.index', array_merge($params, ['showSoftDeleted' => 1]), true)) }}"></a>');
                    }else{
                        $('#dataTable').before('<a id="redir" href="{{ (route('voyager.'.$dataType->slug.'.index', array_merge($params, ['showSoftDeleted' => 0]), true)) }}"></a>');
                    }

                    $('#redir')[0].click();
                })
            })
        @endif
        $('input[name="row_id"]').on('change', function () {
            var ids = [];
            $('input[name="row_id"]').each(function() {
                if ($(this).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            $('.selected_ids').val(ids);
        });
    </script>
@stop
