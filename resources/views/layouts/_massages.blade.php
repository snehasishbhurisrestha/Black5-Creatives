@if ($errors->any())
    @foreach ($errors->all() as $error)
    <script>
        $(document).ready(function(){
            showToast('error', 'Error', '{{ $error }}');
        });
    </script>
    @endforeach
@endif

@if(Session::has("error"))
    <script>
        $(document).ready(function(){
            showToast('error', 'Error', '{{Session::get("error")}}');
        });
    </script>
@endif

@if(Session::has("success"))
    <script>
        $(document).ready(function(){
            showToast('success', 'Success', '{{ Session::get("success") }}');
        });
    </script>
@endif