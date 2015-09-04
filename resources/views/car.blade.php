@extends('app')

@section('menu-car-class','active')

@section('content')

    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-car"></i> รถ</h3>

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
                url:'car/read',
                datatype: "json",
                colNames:['สาขา','แบบ','รุ่น','คันที่', 'วันที่ออก Do', 'วันที่รับรถเข้า', 'เลขเครื่อง', 'เลขตัวถัง', 'กุญแจ', 'สี', 'รถสำหรับ', 'ประเภทรับรถเข้า', 'ขายแล้ว', 'จดทะเบียนแล้ว', 'ส่งมอบแล้ว','ใบรับรถเข้า', 'ใบส่งรถให้ลูกค้า'],
                colModel:[
                    {name:'branchid',index:'branchid', width:80, editable: true,edittype:"select",formatter:'select',editoptions:{value: "{{$branchselectlist}}"}},
                    {name:'carmodelid',index:'carmodelid', width:80, editable: true,edittype:"select",formatter:'select',editrules:{required:true},align:'left',
                        editoptions:{value: "{{$carmodelselectlist}}",
                            dataEvents :[{type: 'change', fn: function(e){
                                var thisval = $(e.target).val();
                                $.get('carsubmodel/read2/'+thisval, function(data){
                                    $('#carsubmodelid').children('option:not(:first)').remove();
                                    $.each(data, function(i, option) {
                                        $('#carsubmodelid').append($('<option/>').attr("value", option.id).text(option.name));
                                    });
                                });
                            }}]
                        }
                    },
                    {name:'carsubmodelid',index:'carsubmodelid', width:80, editable: true,edittype:"select",formatter:'select',editoptions:{value: "{{$carsubmodelselectlist}}"}},
                    {name:'no',index:'no', width:50,editable: true,editoptions:{size:"5"},editrules:{required:true},align:'left'},
                    {name:'dodate',index:'dodate',width:80, editable:true, sorttype:"date", formatter: "date", unformat: pickDate, editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}}, editrules:{required:true}, align:'center'},
                    {name:'receiveddate',index:'receiveddate',width:80, editable:true, sorttype:"date", formatter: "date", unformat: pickDate, editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}}, editrules:{required:true}, align:'center'},
                    {name:'engineno',index:'engineno', width:80,editable: true,editoptions:{size:"20",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'chassisno',index:'chassisno', width:80,editable: true,editoptions:{size:"20",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'keyno',index:'keyno', width:50,editable: true,editoptions:{size:"5"},editrules:{required:true, number:true},align:'center'},
                    {name:'colour',index:'colour', width:50,editable: true,editoptions:{size:"10",maxlength:"10"},editrules:{required:true},align:'left'},
                    {name:'objective',index:'objective', width:50, editable: true,edittype:"select",formatter:'select',editoptions:{value: "0:ขาย;1:ใช้งาน;2:ทดสอบ"},align:'left'},
                    {name:'receivetype',index:'receivetype', width:50, editable: true,edittype:"select",formatter:'select',editoptions:{value: "0:ปกติ;1:ประมูล"},align:'left'},
                    {name:'issold',index:'issold', width:50, editable: true,edittype:"checkbox",editoptions: {value:"1:0", defaultValue:"0"},formatter: booleanFormatter,unformat: aceSwitch,align:'center'},
                    {name:'isregistered',index:'isregistered', width:50, editable: true,edittype:"checkbox",editoptions: {value:"1:0", defaultValue:"0"},formatter: booleanFormatter,unformat: aceSwitch,align:'center'},
                    {name:'isdelivered',index:'isdelivered', width:50, editable: true,edittype:"checkbox",editoptions: {value:"1:0", defaultValue:"0"},formatter: booleanFormatter,unformat: aceSwitch,align:'center'},
                    {name:'receivecarfilepath',index:'receivecarfilepath',width:100,editable: true,edittype:'file',editoptions:{enctype:"multipart/form-data"},formatter:imageLinkFormatter,search:false,align:'center'},
                    {name:'deliverycarfilepath',index:'receivecarfilepath',width:100,editable: true,edittype:'file',editoptions:{enctype:"multipart/form-data"},formatter:imageLinkFormatter,search:false,align:'center'}
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

                editurl: "car/update",
                caption: "",
                height:'100%'
            });

            $(window).triggerHandler('resize.jqGrid');//trigger window resize to make the grid get the correct size

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
                        centerGridForm(dlgDiv);
                    },
                    editData: {
                        _token: "{{ csrf_token() }}"
                    },
                    afterSubmit : function(response, postdata)
                    {
                        //UploadImage(response, postdata);
                        if(response.responseText == "ok"){
                            //alert("Succefully")
                            //return [true,""];
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
                        centerGridForm(dlgDiv);
                    },
                    editData: {
                        _token: "{{ csrf_token() }}"
                    },
                    /*beforeSubmit : function(postdata, formid) {
                        var filename = $( "#receivecarfilepath" ).val();
                        alert(filename);
                        alert(postdata);
                        alert(formid);
                    },*/
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
                        centerGridForm(dlgDiv);
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
                        centerGridForm(dlgDiv);
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
                        centerGridForm(dlgDiv);
                    },
                    editData: {
                        _token: "{{ csrf_token() }}"
                    }
                }
            )

            function UploadImage(response, postdata) {

                var data = $.parseJSON(response.responseText);

                if (data.success == true) {
                    if ($("#fileToUpload").val() != "") {
                        ajaxFileUpload(data.id);
                    }
                }

                return [data.success, data.message, data.id];

            }
            function ajaxFileUpload(id)
            {

                $.ajaxFileUpload
                (
                        {
                            url: '@Url.Action("UploadImage")',
                            secureuri: false,
                            fileElementId: 'fileToUpload',
                            dataType: 'json',
                            data: { id: id },
                            success: function (data, status) {

                                if (typeof (data.success) != 'undefined') {
                                    if (data.success == true) {
                                        return;
                                    } else {
                                        alert(data.message);
                                    }
                                }
                                else {
                                    return alert('Failed to upload logo!');
                                }
                            },
                            error: function (data, status, e) {
                                return alert('Failed to upload logo!');
                            }
                        }
                )
            }
        })
    </script>
@endsection