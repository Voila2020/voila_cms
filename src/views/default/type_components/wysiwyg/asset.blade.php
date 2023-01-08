@push('head')
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/crudbooster/assets/summernote/summernote.css')}}">
@endpush
@push('bottom')
    <script type="text/javascript" src="{{asset('vendor/crudbooster/assets/summernote/summernote.min.js')}}"></script>
    <script src="{{ asset ('vendor/crudbooster/summernote-cleaner.js')}}"></script>
    <script src="https://cdn.tiny.cloud/1/v62njx9127wlchyml53il7iulsly6idz61l60t9d629j33pt/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
@endpush
