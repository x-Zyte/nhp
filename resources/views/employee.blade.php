@extends('app')

@section('menu-employee-class','active')

@section('content')

    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-male"></i> พนักงาน</h3>

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

            $(grid_selector).jqGrid({
                url:'{{ url('/employee/read') }}',
                datatype: "json",
                colNames:['คำนำหน้า', 'ชื่อจริง', 'นามสกุล', 'ชื่อเข้าใช้ระบบ', 'อีเมล์', 'โทรศัพท์', 'เป็นผู้ดูแล', 'สาขา', 'แผนก','ทีมขาย', 'เปิดใช้งาน'],
                colModel:[
                    /*{name:'myac',index:'', width:80, fixed:true, sortable:false, resize:false,
                        formatter:'actions',
                        formatoptions:{
                            keys:true,
                            delOptions:{recreateForm: true, beforeShowForm:beforeDeleteCallback}
                            //editformbutton:true, editOptions:{recreateForm: true, beforeShowForm:beforeEditCallback}
                        }
                    },*/
                    //{hidden: true},
                    {name:'title',index:'title', width:60, editable: true,edittype:"select",formatter:'select',editoptions:{value: "นาย:นาย;นาง:นาง;นางสาว:นางสาว"},align:'left'},
                    {name:'firstname',index:'firstname', width:100,editable: true,editoptions:{size:"20",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'lastname',index:'lastname', width:100,editable: true,editoptions:{size:"20",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'username',index:'username', width:100,editable: true,editoptions:{size:"20",maxlength:"50"},editrules:{required:true, custom: false, custom_func: check_username},align:'left'},
                    {name:'email',index:'email', width:120,editable: true,editoptions:{size:"20",maxlength:"50"},editrules:{required:true,email:true, custom: false, custom_func: check_email},align:'left'},
                    {name:'phone',index:'phone', width:100,editable: true,editoptions:{size:"20",maxlength:"20"},editrules:{},align:'left'},
                    {name:'isadmin',index:'isadmin', width:60, editable: true,edittype:"checkbox",editoptions: {value:"1:0"},formatter: booleanFormatter,unformat: aceSwitch,align:'center'},
                    {name:'branchid',index:'branchid', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value: "{{$branchselectlist}}"},formoptions:{elmsuffix:'(เป็นผู้ดูแลไม่ต้องเลือก)'}},
                    {name:'departmentid',index:'departmentid', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value: "{{$departmentselectlist}}"},formoptions:{elmsuffix:'(เป็นผู้ดูแลไม่ต้องเลือก)'}},
                    {name:'teamid',index:'teamid', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value: "{{$teamselectlist}}"},formoptions:{elmsuffix:'(เป็นผู้ดูแลไม่ต้องเลือก)'}},
                    {name:'active',index:'active', width:60, editable: true,edittype:"checkbox",editoptions: {value:"1:0"},formatter: booleanFormatter,unformat: aceSwitch,align:'center'}
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

                editurl: "employee/update",//nothing is saved
                caption: "",
                height:'100%'
            });

            $(window).triggerHandler('resize.jqGrid');//trigger window resize to make the grid get the correct size

            function check_username(value, colname) {
                var selRowId = $(grid_selector).jqGrid ('getGridParam', 'selrow');
                if(selRowId == null) selRowId = 0;
                $.ajax({
                    url: 'employee/check_username',
                    data: { id:selRowId, username: value, _token: "{{ csrf_token() }}" },
                    type: 'POST',
                    async: false,
                    datatype: 'text',
                    success: function (data) {
                        if (!data) result = [true, ""];
                        else result = [false, colname + ": ชื่อเข้าใช้ระบบนี้ถูกใช้ไปแล้ว"];
                    }
                })
                return result;
            }

            function check_email(value, colname) {
                var selRowId = $(grid_selector).jqGrid ('getGridParam', 'selrow');
                if(selRowId == null) selRowId = 0;
                $.ajax({
                    url: 'employee/check_email',
                    data: { id:selRowId, email: value, _token: "{{ csrf_token() }}" },
                    type: 'POST',
                    async: false,
                    datatype: 'text',
                    success: function (data) {
                        if (!data) result = [true, ""];
                        else result = [false, colname + ": อีเมล์นี้ถูกใช้ไปแล้ว"];
                    }
                })
                return result;
            }

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
                    view: true,
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