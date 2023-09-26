<?php
include "header.php";
?>
<!-- Select2 -->
<link rel="stylesheet" href="theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<?php
include "navbar.php";
include "sidebar.php";

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Background-Photos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="add_background_photo" data-toggle="modal" data-target="#background_photo_modal">
                        Add Background-Photos
                    </button>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Start content -->
    <section class="content">
        <!-- Add Background-Photos Modal -->
        <div class="modal fade" id="background_photo_modal" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="background_photo_modal_label">Add Background-Photos</h5>
                        <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="background_photo_form" name="background_photo_form" method="post">
                        <div class="modal-body">
                            <!-- client code  -->
                            <div class="row">
                                <div id="msg"></div>
                            </div>
                            <!-- Update Hidden id  -->
                            <input type="hidden" name="backGphoto_id" id="backGphoto_id" class="form-control" value="0">

                            <div class="row" style="background-color: #87CEEB; padding: 5px;">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="tittle_name">Title Name<span class="must">*</span></label>
                                        <input type="text" name="tittle_name" id="tittle_name" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="background-color: #87CEEB; padding: 5px;">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="fee">Upload Background-Photo<span class="must">*</span></label>
                                        <input type="hidden" id="photo_Path" value="" />
                                        <input type="file" name="uploadPhoto_path" onchange="encodeImagetoBase64(this, 'photo_file_base64')" id="uploadPhoto_path" class="form-control" />
                                        <input type="hidden" name="photo_file_base64" id="photo_file_base64" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="save" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Case Daily Expenses Modal -->


        <!-- Start Client Table -->
        <div class="card">
            <div class="alert alert-warning alert-dismissible fade hide d-none" role="alert" id="notice">
                <p id="message"></p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- <div class="col-md-12" id="result"></div> -->
            <!-- /.card-header -->
            <div class="card-body">
                <table id="background_photo_table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Title Name</th>
                            <th>Background Photo</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot></tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- Start Client Table -->
    </section>
    <!-- End content -->
</div>




<!-- Start Footer -->
<?php include "footer_js.php"; ?>
<!-- End Footer -->
<script src="theme/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="theme/plugins/jquery-validation/additional-methods.min.js"></script>
<script src="theme/plugins/select2/js/select2.full.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script>
    $(function() {

        $("#justice_id").select2();

        $('#expense_date').datetimepicker({
            format: 'DD-MM-yyyy',
            stepping: 5
        });
        $('#file_id').select2({
            theme: 'bootstrap4'
        });

        $(document).on('click', '.delete', function(e) {
            if (confirm("Are you sure delete this Case Daily Expenses!")) {
                let backGphoto_id = $(this).data('id');
                let arr = {
                    action: 'delete',
                    backGphoto_id: backGphoto_id
                };
                var request = JSON.stringify(arr);
                $.ajax({
                    method: "POST",
                    url: "controller/backPhoto.php",
                    data: request,
                    dataType: "JSON",
                    async: false,
                    headers: {
                        "Content-Type": "application/json"
                    },
                    beforeSend: function() {
                        console.log(request);
                    },
                }).done(function(Response) {
                    $('#background_photo_table').DataTable().ajax.reload();
                    $("#message").html(Response.msg).show();
                    $("#notice").removeClass("d-none");
                    $("#notice").removeClass("hide");
                    $("#notice").addClass("d-block");
                    $("#notice").addClass("show");
                }).fail(function(jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseJSON.msg;
                    }
                    $("#message").html(msg).show();
                }).always(function(xhr) {
                    console.log(xhr);
                });
            }
        });

        $(document).on('click', '.edit', function(e) {
            let backGphoto_id = $(this).data('id');
            let arr = {
                action: 'getSingle',
                backGphoto_id: backGphoto_id
            };
            var request = JSON.stringify(arr);
            $.ajax({
                method: "POST",
                url: "controller/backPhoto.php",
                data: request,
                dataType: "JSON",
                async: false,
                headers: {
                    "Content-Type": "application/json"
                },
                beforeSend: function() {
                    console.log(request);
                },
            }).done(function(Response) {
                $("#background_photo_modal_label").html("Update Case Daily Expenses");
                $("#save").html("Update");
                $.each(Response.data, function(index, value) {
                    $("#backGphoto_id").val(value.backGphoto_id);
                    $("#expense_date").val(value.expense_date);
                    $("#file_id").val(value.file_id);
                    $("#file_id").trigger('change');
                    $("#client_code_id").val(value.client_code_id);
                    $("#client_code_id").trigger('change');
                    $("#case_id").val(value.case_id);
                    $('#photocopy').val(value.photocopy);
                    $('#courier_domestic').val(value.courier_domestic);
                    $('#courier_international').val(value.courier_international);
                    $('#hotel_stay').val(value.hotel_stay);
                    $('#stay_place').val(value.stay_place);
                    $('#stayWithAss').val(value.stayWithAss);
                    $('#hotelNarration').val(value.hotelNarration);
                    $('#hotelCalculat_bas').val(value.hotelCalculat_bas);
                    $('#conveyance').val(value.conveyance);
                    $('#air_ticket').val(value.air_ticket);
                    $('#airStay').val(value.airStay);
                    $('#airAss').val(value.airAss);
                    $('#airNarration').val(value.airNarration);
                    $('#airCalculat_bas').val(value.airCalculat_bas);
                    $('#oth_expense').val(value.oth_expense);
                    $("#bill_upload").val('');
                    $("#bill_file_base64").val('');
                    $("#billPath").val(value.bill_path);
                });

                $("#background_photo_modal").modal('show');

            }).fail(function(jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseJSON.msg;
                }
                $("#message").html(msg).show();
            }).always(function(xhr) {
                console.log(xhr);
            });
        });

        var DataTable = $("#background_photo_table").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "paging": true,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            processing: true,
            serverSide: true,
            ajax: {
                url: "controller/backPhoto.php",
                type: "POST",
                dataType: "json",
                async: false,
                headers: {
                    "Content-Type": "application/json"
                },
                data: function(d) {
                    d.action = 'get';
                    return JSON.stringify(d);
                }
            },
            "columns": [{
                    "data": "s_no",
                    "searchable": false,
                    "orderable": false
                },
                {
                    "data": "tittle_name"
                },
                {
                    "data": "uploadPhoto_path"
                },
                {
                    "data": "is_status"
                },
                {
                    "data": "action",
                    "searchable": false,
                    "orderable": false
                }
            ]
        }).buttons().container().appendTo('#background_photo_table_wrapper .col-md-6:eq(0)');

        $(document).on('click', '#add_background_photo', function() {
            $("#background_photo_modal_label").html("Add Daily Expenses");
            $("#save").html("Save");
            $("#backGphoto_id").val(0);
            $("#tittle_name").val('');
            $("#uploadPhoto_path").val('');
            $("#photo_file_base64").val('');

            $("#tittle_name").removeClass('is-invalid');
            $("#uploadPhoto_path").removeClass('is-invalid');

            $("#tittle_name_date-error").hide();
            $("#uploadPhoto_path-error").hide();
            $("#msg").hide();

        });

        $.validator.setDefaults({
            submitHandler: function(e) {

                let backGphoto_id = $("#backGphoto_id").val();
                let tittle_name = $("#tittle_name").val();
                let uploadPhoto_path = $("#uploadPhoto_path").val();
                let photo_file_base64 = $("#photo_file_base64").val();
                let photoPath = '';
                if (photo_file_base64 == '') {
                    photoPath = $("#photo_Path").val();
                }
                let action = 'add';
                if (backGphoto_id > 0) {
                    action = 'update';
                }

                let arr = {
                    action: action,
                    backGphoto_id: backGphoto_id,
                    tittle_name: tittle_name,
                    uploadPhoto_path: uploadPhoto_path,
                    photo_file_base64: photo_file_base64,
                    photoPath: photoPath
                };
                var request = JSON.stringify(arr);
                $.ajax({
                    method: "POST",
                    url: "controller/backPhoto.php",
                    data: request,
                    dataType: "JSON",
                    async: false,
                    headers: {
                        "Content-Type": "application/json"
                    },
                    beforeSend: function() {
                        console.log(request);
                    },
                }).done(function(Response) {
                    $("#background_photo_modal").modal('hide');
                    $('#background_photo_table').DataTable().ajax.reload();
                    $("#message").html(Response.msg).show();
                    $("#notice").removeClass("d-none");
                    $("#notice").removeClass("hide");
                    $("#notice").addClass("d-block");
                    $("#notice").addClass("show");
                }).fail(function(jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseJSON.msg;
                    }
                    $("#message").html(msg).show();
                }).always(function(xhr) {
                    console.log(xhr);
                });
            }
        });

        $('#background_photo_form').validate({
            rules: {
                tittle_name: {
                    required: true
                },
                uploadPhoto_path: {
                    required: true
                }
            },
            messages: {
                tittle_name: {
                    required: "This field is required"
                },
                uploadPhoto_path: {
                    required: "This field is required"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        // $(document).on('change', '#client_code_id', function(e) {
        //     var client_code_id = $(this).val();
        //     case_detail(client_code_id);
        //     console.log('abc');
        //     return false;
        //     $("#case_id").trigger("change");
        // });
        $(document).on("change", "#file_id", function() {
            let client_code_id = $(this).find(':selected').attr('data-client_code_id');
            $("#client_code_id").val(client_code_id);
            $("#client_code_id").trigger('change');
            $("#client_code_id").prop('disabled', true);

            case_detail($(this).val());
            $("#case_id").prop('disabled', true);


            $("#client_code_id").trigger('change');
            $("#client_code_id").prop('disabled', true);
        });

        // file_id();
        // client_code();
        // case_detail(0);
        // task_type();

    });

    // function client_code() {
    //     let arr = {
    //         action: 'get',
    //     };
    //     var request = JSON.stringify(arr);
    //     $.ajax({
    //         method: "POST",
    //         url: "controller/client_code.php",
    //         data: request,
    //         dataType: "JSON",
    //         async: false,
    //         headers: {
    //             "Content-Type": "application/json"
    //         },
    //         beforeSend: function() {
    //             console.log(request);
    //         },
    //     }).done(function(Response) {
    //         $("#client_code_id").html('<option value="0">Select Client Code</option>');
    //         $.each(Response.data, function(index, value) {
    //             $("#client_code_id").append('<option value="' + value.client_code_id + '">' + value.client_code + '</option>');
    //         });
    //     }).fail(function(jqXHR, exception) {
    //         var msg = '';
    //         if (jqXHR.status === 0) {
    //             msg = 'Not connect.\n Verify Network.';
    //         } else if (jqXHR.status == 404) {
    //             msg = 'Requested page not found. [404]';
    //         } else if (jqXHR.status == 500) {
    //             msg = 'Internal Server Error [500].';
    //         } else if (exception === 'parsererror') {
    //             msg = 'Requested JSON parse failed.';
    //         } else if (exception === 'timeout') {
    //             msg = 'Time out error.';
    //         } else if (exception === 'abort') {
    //             msg = 'Ajax request aborted.';
    //         } else {
    //             msg = 'Uncaught Error.\n' + jqXHR.responseJSON.msg;
    //         }
    //         $("#message").html(msg).show();
    //     }).always(function(xhr) {
    //         console.log(xhr);
    //     });
    // }

    // function file_id() {
    //     let arr = {
    //         action: 'get',
    //     };
    //     var request = JSON.stringify(arr);
    //     $.ajax({
    //         method: "POST",
    //         url: "controller/file_master.php",
    //         data: request,
    //         dataType: "JSON",
    //         async: false,
    //         headers: {
    //             "Content-Type": "application/json"
    //         },
    //         beforeSend: function() {
    //             console.log(request);
    //         },
    //     }).done(function(Response) {
    //         $("#file_id").html('<option value="0">Select File No</option>');
    //         $.each(Response.data, function(index, value) {
    //             $("#file_id").append('<option value="' + value.file_id + '" data-client_code_id="' + value.client_code_id + '">' + value.file_no + '</option>');
    //         });
    //     })
    // }

    // function case_detail(file_id) {
    //     if (file_id > 0) {
    //         var arr = {
    //             action: 'fatch_by_file_id',
    //             file_id: file_id
    //         };
    //     } else {
    //         var arr = {
    //             action: 'get',
    //         };
    //     }
    //     var request = JSON.stringify(arr);
    //     $.ajax({
    //         method: "POST",
    //         url: "controller/case_detail.php",
    //         data: request,
    //         dataType: "JSON",
    //         async: false,
    //         headers: {
    //             "Content-Type": "application/json"
    //         },
    //         beforeSend: function() {
    //             console.log(request);
    //         },
    //     }).done(function(Response) {
    //         if (Response.data.length > 0) {
    //             $("#case_id").html('<option value="0" data-case_detail="" data-short_title="">Select Case</option>');
    //             $.each(Response.data, function(index, value) {
    //                 $("#case_id").append('<option selected value="' + value.case_id + '" data-case_detail="' + value.case_detail + '" data-short_title="' + value.case_from + ' VS ' + value.case_to + '">' + value.case_no + '</option>');
    //             });
    //         } else {
    //             $("#case_id").html('<option selected value="0" data-case_detail="" data-short_title="">No Case Found</option>');
    //         }

    //     }).fail(function(jqXHR, exception) {
    //         var msg = '';
    //         if (jqXHR.status === 0) {
    //             msg = 'Not connect.\n Verify Network.';
    //         } else if (jqXHR.status == 404) {
    //             msg = 'Requested page not found. [404]';
    //         } else if (jqXHR.status == 500) {
    //             msg = 'Internal Server Error [500].';
    //         } else if (exception === 'parsererror') {
    //             msg = 'Requested JSON parse failed.';
    //         } else if (exception === 'timeout') {
    //             msg = 'Time out error.';
    //         } else if (exception === 'abort') {
    //             msg = 'Ajax request aborted.';
    //         } else {
    //             msg = 'Uncaught Error.\n' + jqXHR.responseJSON.msg;
    //         }
    //         $("#message").html(msg).show();
    //     }).always(function(xhr) {
    //         console.log(xhr);
    //     });
    // }

    // function task_type() {
    //     let arr = {
    //         action: 'get',
    //     };
    //     var request = JSON.stringify(arr);
    //     $.ajax({
    //         method: "POST",
    //         url: "controller/task_type.php",
    //         data: request,
    //         dataType: "JSON",
    //         async: false,
    //         headers: {
    //             "Content-Type": "application/json"
    //         },
    //         beforeSend: function() {
    //             console.log(request);
    //         },
    //     }).done(function(Response) {
    //         $("#task_id").html('<option value="0">Select Task Type</option>');
    //         $.each(Response.data, function(index, value) {
    //             $("#task_id").append('<option value="' + value.task_id + '">' + value.task_type + '</option>');
    //         });
    //     }).fail(function(jqXHR, exception) {
    //         var msg = '';
    //         if (jqXHR.status === 0) {
    //             msg = 'Not connect.\n Verify Network.';
    //         } else if (jqXHR.status == 404) {
    //             msg = 'Requested page not found. [404]';
    //         } else if (jqXHR.status == 500) {
    //             msg = 'Internal Server Error [500].';
    //         } else if (exception === 'parsererror') {
    //             msg = 'Requested JSON parse failed.';
    //         } else if (exception === 'timeout') {
    //             msg = 'Time out error.';
    //         } else if (exception === 'abort') {
    //             msg = 'Ajax request aborted.';
    //         } else {
    //             msg = 'Uncaught Error.\n' + jqXHR.responseJSON.msg;
    //         }
    //         $("#message").html(msg).show();
    //     }).always(function(xhr) {
    //         console.log(xhr);
    //     });
    // }

    function encodeImagetoBase64(element, base64Id) {
        console.log('working');
        var File = element.files[0];
        var FR = new FileReader();
        FR.addEventListener("load", function() {
            $("#" + base64Id).val(FR.result);
        });
        FR.readAsDataURL(File);
    }
</script>
<?php include "footer.php"; ?>