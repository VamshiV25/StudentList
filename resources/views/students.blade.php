<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Students List Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" 
            integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" 
            crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>
    <div class="p-3 text-primary-emphasis bg-warning-subtle border border-primary-subtle rounded-3">
        <div class="container">
            <h1 style="text-align:center;margin:20px;height:10px;color:darkblue;">Student List</h1>            
            <a href="javascript:void(0)" class="btn btn-success" id="createNewStudent" style="float:right">Add</a>
            <table class="table table-bordered border-primary data-table">
                <thead>
                    <tr class="table-dark">
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-success"></tbody>
            </table>
        </div>
        <div class="modal fade" id="ajaxModel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalHeading"></h4>
                    </div>
                    <div class="modal-body">
                        <form id="studentForm" name="studentForm" class="form-horizontal">
                            <input type="hidden" name="student_id" id="student_id">
                            <div class="form-group">
                                Name : <br>
                                <input type="text" class="form-control" id="name" name="name" 
                                placeholder="Enter Name" value="" required>
                            </div>
                            <div class="form-group">
                                Email : <br>
                                <input type="text" class="form-control" id="email" name="email" 
                                placeholder="Enter Email" value="" required>
                            </div>
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" 
        integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" 
        crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
</body>
    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $(".data-table").DataTable({
                serverSide: true,
                processing: true,
                ajax: "{{ route('students.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'action', name: 'action' },
                ]
            });

            $("#createNewStudent").click(function () {
                $("#student_id").val('');
                $("#studentForm").trigger("reset");
                $("#modalHeading").html("Add Student");
                $('#ajaxModel').modal('show');
            });

            $("#saveBtn").click(function (e) {
                e.preventDefault();
                $(this).html('Save');

                $.ajax({
                    data: $("#studentForm").serialize(),
                    url: "{{ route('students.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        $("#studentForm").trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        $("#saveBtn").html('Save');
                    }
                });
            });
            
            $('body').on('click', '.deleteStudent', function () {
                var student_id = $(this).data('id');
                var confirmed = confirm("Are You Sure Want To Delete!");
                if (confirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('students.store') }}/" + student_id,
                        success: function (data) {
                            table.draw();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });
        

            $('body').on('click', '.editStudent', function () {
                var student_id = $(this).data('id');
                $.get("{{ route('students.index') }}/" + student_id+"/edit",
                    function (data) {
                        $("#modalHeading").html("Edit Student");
                        $('#ajaxModel').modal('show');
                        $("#student_id").val(data.id);
                        $("#name").val(data.name);
                        $("#email").val(data.email);
                    }
                );
            });
        });
                
    </script>
</html>