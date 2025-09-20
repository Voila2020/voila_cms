@extends('crudbooster::admin_template')

@section('content')
    <div>
        <div class="panel-body">
            <div class="panel panel-default mt-4">
                <div class="panel-heading">
                    <strong>{{ trans('crudbooster.add_comment') }}</strong>
                </div>
                <div class="panel-body">
                    <!-- Add Comment Form -->
                    <form id="commentForm">
                        @csrf
                        <input type="hidden" name="ticket_id" value="{{ $ticketId }}">
                        <div class="form-group">
                            <label for="comment">{{ trans('crudbooster.description') }}</label>
                            <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="image">{{ trans('crudbooster.attachment') }}</label>
                            <input type="file" class="form-control" id="image" name="attachments" accept="image/*">
                        </div>

                        <button id="addCommentBtn" type="submit"
                            class="btn btn-primary">{{ trans('crudbooster.add_comment') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('bottom')
        <script type="text/javascript">
            $(document).ready(function() {
                const ticketDetailUrl = "{{ url('admin/show_tickets/detail/' . $ticketCode) }}";
                //-----------------------------------------------
                $("#commentForm").on("submit", function(event) {
                    event.preventDefault();
                    const formData = new FormData(this);
                    $('#addCommentBtn').append('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
                    $('#addCommentBtn').attr('disabled', true);
                    //-----------------------------------------------
                    $.ajax({
                        url: "{{ url('/admin/add-comment') }}",
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('#addCommentBtn').attr('disabled', false);
                            window.location.href = ticketDetailUrl;

                        },
                        error: function(xhr, status, error) {
                            alert("An error occurred. Please try again.");
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
