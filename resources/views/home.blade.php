@extends('app')

@section('content')
    <style type="text/css">
        div {
            color: white;
        }

        input {
            color: black;
        }

    </style>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div id="content-div">
                    <div>
                        Date: <input type="date" value="{{date('Y-m-d')}}" id="calender"/>
                    </div>
                    <div id="message" style="display: none;">Copied</div>
                @if(count($data) > 0)
                        @include('table')
                    @else
                        <div id="empty">No any links present on this date.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('foot')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.6.0/clipboard.min.js"></script>
    <script>
        $('#calender').on('change', function () {
            var date = $(this).val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: '/messages/date',
                data: {date: date},
                success: function (response) {
                    $('#linkTable').remove();
                    if (response.status != false) {
                        $('#empty').css('display', 'none');
                        $('#content-div').append(response);
                    } else {
                        $('#empty').css('display', 'block');
                    }
                }
            });
        });

        var clipboard = new Clipboard('.btn');

        clipboard.on('success', function (e) {
            $('#message').css('display', 'block');

            setTimeout(function () {
                $('#message').css('display', 'none');
            }, 2000);
        });
    </script>
@endsection

