@extends('app')

@section('menu-customer-class','active')

@section('content')

    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-users"></i> ลูกค้า</h3>

    <table id="grid-table"></table>

    <div id="grid-pager"></div>

    <script type="text/javascript">
        var $path_base = "..";//this will be used for editurl parameter
    </script>

    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        $(document).ready(function() {
            var grid_selector = "#grid-table";
            var pager_selector = "#grid-pager";

            //resize to fit page size
            $(window).on('resize.jqGrid', function () {
                $(grid_selector).jqGrid( 'setGridWidth', $(".page-content").width() );
            })
            //resize on sidebar collapse/expand
            var parent_column = $(grid_selector).closest('[class*="col-"]');
            $(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
                if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
                    $(grid_selector).jqGrid( 'setGridWidth', parent_column.width() );
                }
            })

            var defaultBranch = '';
            var hiddenBranch = false;
            if('{{Auth::user()->isadmin}}' == '0'){
                defaultBranch = '{{Auth::user()->branchid}}';
                hiddenBranch = true;
            }

            $(grid_selector).jqGrid({
                url:'{{ url('/customer/read') }}',
                datatype: "json",
                colNames:['สาขา', 'คำนำหน้า', 'ชื่อจริง', 'นามสกุล', 'ที่อยู่', 'จังหวัด', 'เขต/อำเภอ', 'แขวง/ตำบล', 'รหัสไปรษณีย์', 'อีเมล์', 'โทรศัพท์'],
                colModel:[
                    {name:'branchid',index:'branchid', width:70, editable: true,edittype:"select",formatter:'select',editoptions:{value: "{{$branchselectlist}}", defaultValue:defaultBranch},editrules:{required:true},hidden:hiddenBranch},
                    {name:'title',index:'title', width:50, editable: true,edittype:"select",formatter:'select',editoptions:{value: "นาย:นาย;นาง:นาง;นางสาว:นางสาว"},align:'left'},
                    {name:'firstname',index:'firstname', width:100,editable: true,editoptions:{size:"20",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'lastname',index:'lastname', width:100,editable: true,editoptions:{size:"20",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'address',index:'address', width:100,editable: true,editoptions:{size:"50",maxlength:"200"},align:'left'},
                    {name:'provinceid',index:'provinceid', width:80, editable: true,edittype:"select",formatter:'select',align:'left',
                        editoptions:{value: "{{$provinceselectlist}}",
                            dataEvents :[{type: 'change', fn: function(e){
                                var thisval = $(e.target).val();
                                $.get('amphur/read/'+thisval, function(data){
                                    $('#amphurid').children('option:not(:first)').remove();
                                    $('#districtid').children('option:not(:first)').remove();
                                    //$('#zipcodeid').children('option:not(:first)').remove();
                                    $('#zipcode').val('');
                                    $.each(data, function(i, option) {
                                        $('#amphurid').append($('<option/>').attr("value", option.id).text(option.name));
                                    });
                                });
                            }}]
                        }
                    },
                    {name:'amphurid',index:'amphurid', width:80, editable: true,edittype:"select",formatter:'select',align:'left',
                        editoptions:{value: ":เลือกเขต/อำเภอ",
                            dataEvents :[{type: 'change', fn: function(e){
                                var thisval = $(e.target).val();
                                $.get('district/read/'+thisval, function(data){
                                    $('#districtid').children('option:not(:first)').remove();
                                    //$('#zipcodeid').children('option:not(:first)').remove();
                                    $('#zipcode').val('');
                                    $.each(data, function(i, option) {
                                        $('#districtid').append($('<option/>').attr("value", option.id).text(option.name));
                                    });
                                });
                            }}]
                        }
                    },
                    {name:'districtid',index:'districtid', width:80, editable: true,edittype:"select",formatter:'select',align:'left',
                        editoptions:{value: ":เลือกตำบล/แขวง",
                            dataEvents :[{type: 'change', fn: function(e){
                                var thisval = $(e.target).val();
                                $.get('zipcode/read/'+thisval, function(data){
                                    $('#zipcode').val(data.code);
                                    //$('#zipcodeid').children('option:not(:first)').remove();
                                    //$.each(data, function(i, option) {
                                    //$('#zipcodeid').append($('<option/>').attr("value", option.id).text(option.code));
                                    //});
                                });
                            }}]
                        }
                    },
                    {name:'zipcode',index:'zipcode', width:60,editable: true,editoptions:{size:"5",maxlength:"5"},align:'left'},
                    {name:'email',index:'email', width:100,editable: true,editoptions:{size:"20",maxlength:"50"},align:'left'},
                    {name:'phone',index:'phone', width:70,editable: true,editoptions:{size:"20",maxlength:"20"},align:'left'}
                ],
                viewrecords : true,
                rowNum:10,
                rowList:[10,20,30],
                pager : pager_selector,
                altRows: true,
                multiselect: true,
                multiboxonly: true,

                loadComplete : function() {
                    var table = this;
                    setTimeout(function(){
                        styleCheckbox(table);

                        updateActionIcons(table);
                        updatePagerIcons(table);
                        enableTooltips(table);
                    }, 0);
                },

                editurl: "customer/update",
                caption: "",
                height:'100%',
                subGrid: true,
                subGridOptions : {
                    plusicon : "ace-icon fa fa-plus center bigger-110 blue",
                    minusicon  : "ace-icon fa fa-minus center bigger-110 blue",
                    openicon : "ace-icon fa fa-chevron-right center orange"
                },
                subGridRowExpanded: function(subgrid_id, row_id) {
                    var subgrid_table_id, pager_id;
                    subgrid_table_id = subgrid_id+"_t";
                    pager_id = "p_"+subgrid_table_id;

                    //resize to fit page size
                    $(window).on('resize.jqGridSubGrid', function () {
                        $("#"+subgrid_table_id).jqGrid( 'setGridWidth', $(grid_selector).width() - 55);
                    })
                    //resize on sidebar collapse/expand
                    var parent_column = $("#"+subgrid_table_id).closest('[class*="col-"]');
                    $(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
                        if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
                            $("#"+subgrid_table_id).jqGrid( 'setGridWidth', parent_column.width() );
                        }
                    })

                    //desired_width = $(grid_selector).width();
                    //desired_width -= 55;
                    $("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
                    jQuery("#"+subgrid_table_id).jqGrid({
                        url:'customerexpectation/read?customerid='+row_id,
                        datatype: "json",
                        colNames:['วันที่', 'รายละเอียด'],
                        colModel:[
                            {name:'date',index:'date',width:90, editable:true, sorttype:"date", formatter: "date", unformat: pickDate, editoptions:{dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}}, editrules:{required:true}, align:'center'},
                            {name:'details',index:'details', width:300,editable: true,edittype:'textarea',editoptions:{rows:"2",cols:"40"},editrules:{required:true},align:'left'}
                        ],
                        viewrecords : true,
                        rowNum:10,
                        rowList:[10,20,30],
                        pager : pager_id,
                        altRows: true,
                        multiselect: true,
                        multiboxonly: true,

                        loadComplete : function() {
                            var table = this;
                            setTimeout(function(){
                                styleCheckbox(table);

                                updateActionIcons(table);
                                updatePagerIcons(table);
                                enableTooltips(table);
                            }, 0);
                        },

                        editurl: "customerexpectation/update",
                        caption: "ความคาดหวัง",
                        height:'100%'
                        //width:desired_width
                    });

                    $(window).triggerHandler('resize.jqGridSubGrid');

                    jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,
                            { 	//navbar options
                                edit: true,
                                editicon : 'ace-icon fa fa-pencil blue',
                                add: true,
                                addicon : 'ace-icon fa fa-plus-circle purple',
                                del: true,
                                delicon : 'ace-icon fa fa-trash-o red',
                                search: true,
                                searchicon : 'ace-icon fa fa-search orange',
                                refresh: true,
                                refreshicon : 'ace-icon fa fa-refresh green',
                                view: false,
                                viewicon : 'ace-icon fa fa-search-plus grey'
                            },
                            {
                                //edit record form
                                closeAfterEdit: true,
                                width: 500,
                                recreateForm: true,
                                beforeShowForm : function(e) {
                                    var form = $(e[0]);
                                    form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                                    style_edit_form(form);

                                    var dlgDiv = $("#editmod" + jQuery("#"+subgrid_table_id)[0].id);
                                    var parentDiv = dlgDiv.parent(); // div#gbox_list
                                    var dlgWidth = dlgDiv.width();
                                    var parentWidth = parentDiv.width();
                                    //var dlgHeight = dlgDiv.height();
                                    //var parentHeight = parentDiv.height();
                                    //var parentTop = parentDiv.offset().top;
                                    var parentLeft = parentDiv.offset().left;
                                    //dlgDiv[0].style.top =  Math.round(  (parentTop+160)  + (parentHeight-dlgHeight)/2  ) + "px";
                                    dlgDiv[0].style.left = Math.round(  parentLeft + (parentWidth-dlgWidth  )/2 )  + "px";
                                },
                                editData: {
                                    _token: "{{ csrf_token() }}",
                                    customerid: row_id
                                },
                                afterSubmit : function(response, postdata)
                                {
                                    if(response.responseText == "ok"){
                                        alert("Succefully")
                                        return [true,""];
                                    }else{
                                        return [false,response.responseText];
                                    }
                                }
                            },
                            {
                                //new record form
                                width: 500,
                                closeAfterAdd: true,
                                recreateForm: true,
                                viewPagerButtons: false,
                                beforeShowForm : function(e) {
                                    jQuery("#"+subgrid_table_id).jqGrid('resetSelection');
                                    var form = $(e[0]);
                                    form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar')
                                            .wrapInner('<div class="widget-header" />')
                                    style_edit_form(form);

                                    var dlgDiv = $("#editmod" + jQuery("#"+subgrid_table_id)[0].id);
                                    var parentDiv = dlgDiv.parent(); // div#gbox_list
                                    var dlgWidth = dlgDiv.width();
                                    var parentWidth = parentDiv.width();
                                    //var dlgHeight = dlgDiv.height();
                                    //var parentHeight = parentDiv.height();
                                    //var parentTop = parentDiv.offset().top;
                                    var parentLeft = parentDiv.offset().left;
                                    //dlgDiv[0].style.top =  Math.round(  (parentTop+160)  + (parentHeight-dlgHeight)/2  ) + "px";
                                    dlgDiv[0].style.left = Math.round(  parentLeft + (parentWidth-dlgWidth  )/2 )  + "px";
                                },
                                editData: {
                                    _token: "{{ csrf_token() }}",
                                    customerid: row_id
                                },
                                afterSubmit : function(response, postdata)
                                {
                                    if(response.responseText == "ok"){
                                        alert("Succefully")
                                        return [true,""];
                                    }else{
                                        return [false,response.responseText];
                                    }
                                }
                            },
                            {
                                //delete record form
                                recreateForm: true,
                                beforeShowForm : function(e) {
                                    var form = $(e[0]);
                                    if(form.data('styled')) return false;

                                    form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                                    style_delete_form(form);

                                    form.data('styled', true);

                                    var dlgDiv = $("#delmod" + jQuery(grid_selector)[0].id);
                                    var parentDiv = dlgDiv.parent(); // div#gbox_list
                                    var dlgWidth = dlgDiv.width();
                                    var parentWidth = parentDiv.width();
                                    //var dlgHeight = dlgDiv.height();
                                    //var parentHeight = parentDiv.height();
                                    //var parentTop = parentDiv.offset().top;
                                    var parentLeft = parentDiv.offset().left;
                                    //dlgDiv[0].style.top =  Math.round(  (parentTop+160)  + (parentHeight-dlgHeight)/2  ) + "px";
                                    dlgDiv[0].style.left = Math.round(  parentLeft + (parentWidth-dlgWidth  )/2 )  + "px";
                                },
                                onClick : function(e) {
                                    alert(1);
                                },
                                delData: {
                                    _token: "{{ csrf_token() }}"
                                },
                                afterSubmit : function(response, postdata)
                                {
                                    if(response.responseText == "ok"){
                                        alert("Succefully")
                                        return [true,""];
                                    }else{
                                        return [false,response.responseText];
                                    }
                                }
                            },
                            {
                                //search form
                                recreateForm: true,
                                afterShowSearch: function(e){
                                    var form = $(e[0]);
                                    form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
                                    style_search_form(form);

                                    var dlgDiv = $("#searchmodfbox_" + jQuery(grid_selector)[0].id);
                                    var parentDiv = dlgDiv.parent(); // div#gbox_list
                                    var dlgWidth = dlgDiv.width();
                                    var parentWidth = parentDiv.width();
                                    //var dlgHeight = dlgDiv.height();
                                    //var parentHeight = parentDiv.height();
                                    //var parentTop = parentDiv.offset().top;
                                    var parentLeft = parentDiv.offset().left;
                                    //dlgDiv[0].style.top =  Math.round(  (parentTop+160)  + (parentHeight-dlgHeight)/2  ) + "px";
                                    dlgDiv[0].style.left = Math.round(  parentLeft + (parentWidth-dlgWidth  )/2 )  + "px";
                                },
                                afterRedraw: function(){
                                    style_search_filters($(this));
                                }
                                ,
                                multipleSearch: true,
                                editData: {
                                    _token: "{{ csrf_token() }}"
                                }
                                /**
                                 multipleGroup:true,
                                 showQuery: true
                                 */
                            },
                            {
                                //view record form
                                recreateForm: true,
                                beforeShowForm: function(e){
                                    var form = $(e[0]);
                                    form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')

                                    var dlgDiv = $("#viewmod" + jQuery(grid_selector)[0].id);
                                    var parentDiv = dlgDiv.parent(); // div#gbox_list
                                    var dlgWidth = dlgDiv.width();
                                    var parentWidth = parentDiv.width();
                                    //var dlgHeight = dlgDiv.height();
                                    //var parentHeight = parentDiv.height();
                                    //var parentTop = parentDiv.offset().top;
                                    var parentLeft = parentDiv.offset().left;
                                    //dlgDiv[0].style.top =  Math.round(  (parentTop+160)  + (parentHeight-dlgHeight)/2  ) + "px";
                                    dlgDiv[0].style.left = Math.round(  parentLeft + (parentWidth-dlgWidth  )/2 )  + "px";
                                },
                                editData: {
                                    _token: "{{ csrf_token() }}"
                                }
                            }
                    )
                }
            });

            $(window).triggerHandler('resize.jqGrid');//trigger window resize to make the grid get the correct size

            function booleanFormatter( cellvalue, options, cell ) {
                if (cellvalue == '1') {
                    return 'Yes';
                }else if(cellvalue == '0') {
                    return 'No';
                }
            }

            //switch element when editing inline
            function aceSwitch( cellvalue, options, cell ) {
                setTimeout(function(){
                    $(cell) .find('input[type=checkbox]')
                            .addClass('ace ace-switch ace-switch-5')
                            .after('<span class="lbl"></span>');
                }, 0);

                if (cellvalue == 'Yes') {
                    return '1';
                }else if(cellvalue == 'No') {
                    return '0';
                }
            }
            //enable datepicker
            function pickDate( cellvalue, options, cell ) {
                setTimeout(function(){
                    $(cell) .find('input[type=text]')
                            .datepicker({format:'yyyy-mm-dd' , autoclose:true});
                }, 0);
            }

            //navButtons
            jQuery(grid_selector).jqGrid('navGrid',pager_selector,
                { 	//navbar options
                    edit: true,
                    editicon : 'ace-icon fa fa-pencil blue',
                    add: true,
                    addicon : 'ace-icon fa fa-plus-circle purple',
                    del: true,
                    delicon : 'ace-icon fa fa-trash-o red',
                    search: true,
                    searchicon : 'ace-icon fa fa-search orange',
                    refresh: true,
                    refreshicon : 'ace-icon fa fa-refresh green',
                    view: false,
                    viewicon : 'ace-icon fa fa-search-plus grey'
                },
                {
                    //edit record form
                    closeAfterEdit: true,
                    width: 500,
                    recreateForm: true,
                    beforeShowForm : function(e) {
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                        style_edit_form(form);

                        var dlgDiv = $("#editmod" + jQuery(grid_selector)[0].id);
                        var parentDiv = dlgDiv.parent(); // div#gbox_list
                        var dlgWidth = dlgDiv.width();
                        var parentWidth = parentDiv.width();
                        //var dlgHeight = dlgDiv.height();
                        //var parentHeight = parentDiv.height();
                        //var parentTop = parentDiv.offset().top;
                        var parentLeft = parentDiv.offset().left;
                        //dlgDiv[0].style.top =  Math.round(  (parentTop+160)  + (parentHeight-dlgHeight)/2  ) + "px";
                        dlgDiv[0].style.left = Math.round(  parentLeft + (parentWidth-dlgWidth  )/2 )  + "px";
                    },
                    editData: {
                        _token: "{{ csrf_token() }}"
                    },
                    afterSubmit : function(response, postdata)
                    {
                        if(response.responseText == "ok"){
                            alert("Succefully")
                            return [true,""];
                        }else{
                            return [false,response.responseText];
                        }
                    }
                },
                {
                    //new record form
                    width: 500,
                    closeAfterAdd: true,
                    recreateForm: true,
                    viewPagerButtons: false,
                    beforeShowForm : function(e) {
                        jQuery(grid_selector).jqGrid('resetSelection');
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar')
                                .wrapInner('<div class="widget-header" />')
                        style_edit_form(form);

                        var dlgDiv = $("#editmod" + jQuery(grid_selector)[0].id);
                        var parentDiv = dlgDiv.parent(); // div#gbox_list
                        var dlgWidth = dlgDiv.width();
                        var parentWidth = parentDiv.width();
                        //var dlgHeight = dlgDiv.height();
                        //var parentHeight = parentDiv.height();
                        //var parentTop = parentDiv.offset().top;
                        var parentLeft = parentDiv.offset().left;
                        //dlgDiv[0].style.top =  Math.round(  (parentTop+160)  + (parentHeight-dlgHeight)/2  ) + "px";
                        dlgDiv[0].style.left = Math.round(  parentLeft + (parentWidth-dlgWidth  )/2 )  + "px";
                    },
                    editData: {
                        _token: "{{ csrf_token() }}"
                    },
                    afterSubmit : function(response, postdata)
                    {
                        if(response.responseText == "ok"){
                            alert("Succefully")
                            return [true,""];
                        }else{
                            return [false,response.responseText];
                        }
                    }
                },
                {
                    //delete record form
                    recreateForm: true,
                    beforeShowForm : function(e) {
                        var form = $(e[0]);
                        if(form.data('styled')) return false;

                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                        style_delete_form(form);

                        form.data('styled', true);

                        var dlgDiv = $("#delmod" + jQuery(grid_selector)[0].id);
                        var parentDiv = dlgDiv.parent(); // div#gbox_list
                        var dlgWidth = dlgDiv.width();
                        var parentWidth = parentDiv.width();
                        //var dlgHeight = dlgDiv.height();
                        //var parentHeight = parentDiv.height();
                        //var parentTop = parentDiv.offset().top;
                        var parentLeft = parentDiv.offset().left;
                        //dlgDiv[0].style.top =  Math.round(  (parentTop+160)  + (parentHeight-dlgHeight)/2  ) + "px";
                        dlgDiv[0].style.left = Math.round(  parentLeft + (parentWidth-dlgWidth  )/2 )  + "px";
                    },
                    onClick : function(e) {
                        alert(1);
                    },
                    delData: {
                        _token: "{{ csrf_token() }}"
                    },
                    afterSubmit : function(response, postdata)
                    {
                        if(response.responseText == "ok"){
                            alert("Succefully")
                            return [true,""];
                        }else{
                            return [false,response.responseText];
                        }
                    }
                },
                {
                    //search form
                    recreateForm: true,
                    afterShowSearch: function(e){
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
                        style_search_form(form);

                        var dlgDiv = $("#searchmodfbox_" + jQuery(grid_selector)[0].id);
                        var parentDiv = dlgDiv.parent(); // div#gbox_list
                        var dlgWidth = dlgDiv.width();
                        var parentWidth = parentDiv.width();
                        //var dlgHeight = dlgDiv.height();
                        //var parentHeight = parentDiv.height();
                        //var parentTop = parentDiv.offset().top;
                        var parentLeft = parentDiv.offset().left;
                        //dlgDiv[0].style.top =  Math.round(  (parentTop+160)  + (parentHeight-dlgHeight)/2  ) + "px";
                        dlgDiv[0].style.left = Math.round(  parentLeft + (parentWidth-dlgWidth  )/2 )  + "px";
                    },
                    afterRedraw: function(){
                        style_search_filters($(this));
                    }
                    ,
                    multipleSearch: true,
                    editData: {
                        _token: "{{ csrf_token() }}"
                    }
                    /**
                     multipleGroup:true,
                     showQuery: true
                     */
                },
                {
                    //view record form
                    recreateForm: true,
                    beforeShowForm: function(e){
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')

                        var dlgDiv = $("#viewmod" + jQuery(grid_selector)[0].id);
                        var parentDiv = dlgDiv.parent(); // div#gbox_list
                        var dlgWidth = dlgDiv.width();
                        var parentWidth = parentDiv.width();
                        //var dlgHeight = dlgDiv.height();
                        //var parentHeight = parentDiv.height();
                        //var parentTop = parentDiv.offset().top;
                        var parentLeft = parentDiv.offset().left;
                        //dlgDiv[0].style.top =  Math.round(  (parentTop+160)  + (parentHeight-dlgHeight)/2  ) + "px";
                        dlgDiv[0].style.left = Math.round(  parentLeft + (parentWidth-dlgWidth  )/2 )  + "px";
                    },
                    editData: {
                        _token: "{{ csrf_token() }}"
                    }
                }
            )

            function style_edit_form(form) {
                //enable datepicker on "sdate" field and switches for "stock" field
                form.find('input[name=isadmin],input[name=active]')
                        .addClass('ace ace-switch ace-switch-5').after('<span class="lbl"></span>');
                //don't wrap inside a label element, the checkbox value won't be submitted (POST'ed)
                //.addClass('ace ace-switch ace-switch-5').wrap('<label class="inline" />').after('<span class="lbl"></span>');

                //update buttons classes
                var buttons = form.next().find('.EditButton .fm-button');
                buttons.addClass('btn btn-sm').find('[class*="-icon"]').hide();//ui-icon, s-icon
                buttons.eq(0).addClass('btn-primary').prepend('<i class="ace-icon fa fa-check"></i>');
                buttons.eq(1).prepend('<i class="ace-icon fa fa-times"></i>')

                buttons = form.next().find('.navButton a');
                buttons.find('.ui-icon').hide();
                buttons.eq(0).append('<i class="ace-icon fa fa-chevron-left"></i>');
                buttons.eq(1).append('<i class="ace-icon fa fa-chevron-right"></i>');
            }

            function style_delete_form(form) {
                var buttons = form.next().find('.EditButton .fm-button');
                buttons.addClass('btn btn-sm btn-white btn-round').find('[class*="-icon"]').hide();//ui-icon, s-icon
                buttons.eq(0).addClass('btn-danger').prepend('<i class="ace-icon fa fa-trash-o"></i>');
                buttons.eq(1).addClass('btn-default').prepend('<i class="ace-icon fa fa-times"></i>')
            }

            function style_search_filters(form) {
                form.find('.delete-rule').val('X');
                form.find('.add-rule').addClass('btn btn-xs btn-primary');
                form.find('.add-group').addClass('btn btn-xs btn-success');
                form.find('.delete-group').addClass('btn btn-xs btn-danger');
            }
            function style_search_form(form) {
                var dialog = form.closest('.ui-jqdialog');
                var buttons = dialog.find('.EditTable')
                buttons.find('.EditButton a[id*="_reset"]').addClass('btn btn-sm btn-info').find('.ui-icon').attr('class', 'ace-icon fa fa-retweet');
                buttons.find('.EditButton a[id*="_query"]').addClass('btn btn-sm btn-inverse').find('.ui-icon').attr('class', 'ace-icon fa fa-comment-o');
                buttons.find('.EditButton a[id*="_search"]').addClass('btn btn-sm btn-purple').find('.ui-icon').attr('class', 'ace-icon fa fa-search');
            }

            function beforeDeleteCallback(e) {
                var form = $(e[0]);
                if(form.data('styled')) return false;

                form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                style_delete_form(form);

                form.data('styled', true);
            }

            function beforeEditCallback(e) {
                var form = $(e[0]);
                form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                style_edit_form(form);
            }

            //it causes some flicker when reloading or navigating grid
            //it may be possible to have some custom formatter to do this as the grid is being created to prevent this
            //or go back to default browser checkbox styles for the grid
            function styleCheckbox(table) {
                /**
                 $(table).find('input:checkbox').addClass('ace')
                 .wrap('<label />')
                 .after('<span class="lbl align-top" />')


                 $('.ui-jqgrid-labels th[id*="_cb"]:first-child')
                 .find('input.cbox[type=checkbox]').addClass('ace')
                 .wrap('<label />').after('<span class="lbl align-top" />');
                 */
            }


            //unlike navButtons icons, action icons in rows seem to be hard-coded
            //you can change them like this in here if you want
            function updateActionIcons(table) {
                /**
                 var replacement =
                 {
                     'ui-ace-icon fa fa-pencil' : 'ace-icon fa fa-pencil blue',
                     'ui-ace-icon fa fa-trash-o' : 'ace-icon fa fa-trash-o red',
                     'ui-icon-disk' : 'ace-icon fa fa-check green',
                     'ui-icon-cancel' : 'ace-icon fa fa-times red'
                 };
                 $(table).find('.ui-pg-div span.ui-icon').each(function(){
						var icon = $(this);
						var $class = $.trim(icon.attr('class').replace('ui-icon', ''));
						if($class in replacement) icon.attr('class', 'ui-icon '+replacement[$class]);
					})
                 */
            }

            //replace icons with FontAwesome icons like above
            function updatePagerIcons(table) {
                var replacement =
                {
                    'ui-icon-seek-first' : 'ace-icon fa fa-angle-double-left bigger-140',
                    'ui-icon-seek-prev' : 'ace-icon fa fa-angle-left bigger-140',
                    'ui-icon-seek-next' : 'ace-icon fa fa-angle-right bigger-140',
                    'ui-icon-seek-end' : 'ace-icon fa fa-angle-double-right bigger-140'
                };
                $('.ui-pg-table:not(.navtable) > tbody > tr > .ui-pg-button > .ui-icon').each(function(){
                    var icon = $(this);
                    var $class = $.trim(icon.attr('class').replace('ui-icon', ''));

                    if($class in replacement) icon.attr('class', 'ui-icon '+replacement[$class]);
                })
            }

            function enableTooltips(table) {
                $('.navtable .ui-pg-button').tooltip({container:'body'});
                $(table).find('.ui-pg-div').tooltip({container:'body'});
            }
        })
    </script>
@endsection