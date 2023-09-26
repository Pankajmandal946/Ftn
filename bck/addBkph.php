<?php
include "header.php";
include "navbar.php";
include "sidebar.php";
?>
<!-- Select2 -->
<link rel="stylesheet" href="theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">File</h1>
                </div><!-- /.col -->
                <div class="col-sm-6 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="add_file" data-toggle="modal" data-target="#backgdphoto_modal">
                        Click to Add File
                    </button>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Start content -->
    <section class="content">
        <!-- Add Client Modal -->
        <div class="modal fade" id="backgdphoto_modal" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bkgph_modalLabel">Add File</h5>
                        <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="backgdphoto_form" name="backgdphoto_form" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="backgdphoto_id" id="backgdphoto_id">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title_name">Title Name<span class="must">*</span></label>
                                        <input type="text" name="title_name" id="title_name" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="backgroundPhoto">Background Photo<span class="must">*</span></label>
                                        <input type="hidden" id="background_Photo_Path" value=""/>
                                        <input type="file" name="backgroundPhoto_upload" onchange="encodeImagetoBase64(this, 'background_Photo_base64')" id="backgroundPhoto_upload" class="form-control" />
                                        <input type="hidden" name="background_Photo_base64" id="background_Photo_base64" value="" />
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
        <!-- End Client Modal -->


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
                <table id="backgdphoto_table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Title Name</th>
                            <th>Background Photo</th>
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
<!-- Select2 -->
<script src="theme/plugins/select2/js/select2.full.min.js"></script>
<script>
    $(function() {
        let arr = {
            action: 'get'
        };
        var request = JSON.stringify(arr);
        
        // Delete Button click
        $(document).on('click', '.delete', function(e) {
            if (confirm("Are you sure delete this File")) {
                let backgdphoto_id = $(this).data('id');
                let arr = {
                    action: 'delete',
                    backgdphoto_id: backgdphoto_id
                };
                var request = JSON.stringify(arr);
                $.ajax({
                    method: "POST",
                    url: "controller/addBk_Photo.php",
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
                    $('#backgdphoto_table').DataTable().ajax.reload();
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

        // Edit Button Click
        $(document).on('click', '.edit', function(e) {
            let backgdphoto_id = $(this).data('id');
            let arr = {
                action: 'get',
                backgdphoto_id: backgdphoto_id,
            };
            var request = JSON.stringify(arr);
            $.ajax({
                method: "POST",
                url: "controller/addBk_Photo.php",
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
                title = $("#add_user_modalLabel").html("Update user Detail");
                $.each(Response.data, function(index, value) {
                    console.log(value);
                    $("#backgdphoto_id").val(value.backgdphoto_id);
                    $("#title_name").val(value.title_name);
                    $("#backgroundPhoto").val('');
                    $("#backgroundPhoto_base64").val('');
                    $("#backgroundPhotoPath").val(value.backgroundPhotoPath);
                });
                $("#bkgph_modalLabel").html("Update File");
                $("#save").html("Save");
                $("#backgdphoto_modal").modal('show');

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

        var DataTable = $("#backgdphoto_table").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "paging": true,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            processing: true,
            serverSide: true,
            ajax: {
                url: "controller/addBk_Photo.php",
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
                // {
                //     "data": "backgdphoto_id"
                // },
                {
                    "data": "title_name"
                },
                {
                    "data": "backgroundPhoto_upload"
                },
                {
                    "data": "action",
                    "searchable": false,
                    "orderable": false
                },
            ]
        }).buttons().container().appendTo('#backgdphoto_table_wrapper .col-md-6:eq(0)');

        // Add Button click
        $(document).on('click', '#add_file', function() {
            $("#bkgph_modalLabel").html("Add File");
            $("#save").html("Add File");

            $("#backgdphoto_id").val(0);
            $("#title_name").val('');
            $("#backgroundPhoto_upload").val('');
            $("#background_Photo_base64").val('');

            $("#backgdphoto_id").removeClass("is-invalid");
            $("#title_name").removeClass("is-invalid");
            $("#backgroundPhoto_upload").removeClass("is-invalid");
            
            $("#backgdphoto_id-error").hide();
            $("#title_name-error").hide();
            $("#backgroundPhoto_upload-error").hide();
            $("#msg").hide();
            $('#backgdphoto_form').trigger("reset");
        });

        // from submit
        $.validator.setDefaults({
            submitHandler: function(e) {

                let backgdphoto_id = $("#backgdphoto_id").val();
                let title_name = $("#title_name").val();
                let backgroundPhoto_upload = $("#backgroundPhoto_upload").val();
                let backgroundPhoto_base64 = $("#backgroundPhoto_base64").val();
                let backgroundPhotoPath = '';
                if(backgroundPhoto_base64 == ''){
                    backgroundPhotoPath = $("#background_Photo_Path").val();
                }
                let action = 'add';
                if (backgdphoto_id > 0) {
                    action = 'update';
                }
                
                let arr = {
                    action: action,
                    backgdphoto_id,
                    backgdphoto_id,
                    title_name: title_name,
                    backgroundPhoto_upload: backgroundPhoto_upload,
                    backgroundPhoto_base64: backgroundPhoto_base64,
                    backgroundPhotoPath: backgroundPhotoPath
                };
                var request = JSON.stringify(arr);
                $.ajax({
                    method: "POST",
                    url: "controller/addBk_Photo.php",
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
                    $("#case_daily_expenses_modal").modal('hide');
                    $('#case_daily_expenses_table').DataTable().ajax.reload();
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

        // form validation
        $('#backgdphoto_form').validate({
            rules: {
                title_name: {
                    required: true
                },
                backgroundPhoto:{
                    required: true
                }

            },
            messages: {
                title_name: {
                    required: "This field is required"
                },
                backgroundPhoto: {
                    required: "Please Uplode 1125X700 PNG, JPG, JPIG Images!"
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
    });

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