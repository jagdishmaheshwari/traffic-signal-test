<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .signal {
            height: 100px;
            aspect-ratio: 1/1;
            background: red;
            border-radius: 50%;

        }
        .disable-inputtt{
            background-color: #cfcfcf;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mt-5">Traffic Signal Test</h2>
        </div>
        <div class="col-8 mx-auto">
            <form method="POST">
                <div class="row">
                    <?php
                    $valArr = [4, 2, 1, 3];
                    for ($i = 0; $i < 4; $i++) { ?>
                        <div class="col-3 col-md-3">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="text-center">Signal <?php echo $i+1 ?></h3>
                                </div>
                                <div class="card-body">
                                    <div class="signal mx-auto"></div>
                                    <div class="row mt-3">
                                        <div class="col-12  mx-auto">
                                            <input type="number" min="1" max="4" name='signal[]' value="" class="form-control border border-2 border-dark">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-8">
                    <div class="form-floating mt-3">
                        <input type="number" class="form-control" min="1" name="GreenTime" value="" placeholder>
                        <label for="">Green Light Interval</label>
                    </div>
                    <div class="form-floating mt-3">
                        <input type="number" class="form-control" min="1" name="YellowTime" value="" placeholder>
                        <label for="">Yellow Light Interval</label>
                    </div>
                </div>
                <div class="col-8 mt-3 d-flex">
                    <div class="ms-auto">
                        <div class="btn btn-danger px-5" id="stopButton">Stop</div>
                        <div class="btn btn-success px-5" id="startButton">Start</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            let pullData = false;
            let pendingRequest = false;

            function updateSignal(flag = '') {
                if (pendingRequest) {
                    return;
                }

                formData = $('form').serialize();

                if (flag != '') {
                    formData += '&flag=' + flag;
                }
                if (!pullData) {
                    return;
                }
                pendingRequest = true;
                $.ajax({
                    url: 'ajax.php',
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        console.log(response);

                        res = JSON.parse(response);

                        if ('open' in res && 'next' in res && 'GreenTime' in res && 'YellowTime' in res) {
                            if (!pullData) {
                                return;
                            }
                            $('.signal').eq(res.open).addClass("bg-success");
                            setTimeout(function() {
                                $('.signal').eq(res.next).addClass("bg-warning");
                            }, (res.GreenTime - res.YellowTime) * 1000);

                            pendingRequest = false;
                            setTimeout(function() {
                                $('.signal').removeClass("bg-warning");
                                $('.signal').removeClass("bg-success");
                                updateSignal();
                            }, res.GreenTime * 1000);
                        } else {
                            pendingRequest = false;
                            alert(res.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                        pendingRequest = false;

                    }
                });
            }


            $('#stopButton').click(function() {
                pullData = false;
                pendingRequest = false;
                $('.signal').removeClass('bg-warning');
                $('.signal').removeClass('bg-success');
                $('input').removeClass('disable-inputtt');
            });
            $('#startButton').click(function() {
                pullData = true;
                updateSignal('start');
                $('input').addClass('disable-inputtt');
            });
        });
    </script>
</body>

</html>