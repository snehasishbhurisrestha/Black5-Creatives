<script>
    $("#country_id").on('change', function(){ 
        $("#states_id").html('');
        const countryid= $(this).val();
        $.ajax({
            url : "{{ route('get-state-list') }}",
            data:{country_id : countryid, _token:"{{ csrf_token() }}"},
            method:'post',
            dataType:'json',
            beforeSend: function(){
                $('#states_id').addClass('eventbtn'); 
            },
            success:function(response) {
                $("#states_id").append('<option value="">Select State</option>');
                $.each(response , function(index, item) { 
                    $("#states_id").append('<option value="'+item.id+'">'+item.name+'</option>');
                });
                $('.spinner-border').hide();
            }
        });
    });

    $("#states_id").on('change', function(){ 
        $("#citys_id").html('');
        const stateid= $(this).val();
        $.ajax({
            url : "{{ route('get-city-list') }}",
            data:{state_id : stateid, _token:"{{ csrf_token() }}" },
            method:'post',
            dataType:'json',
            beforeSend: function(){
                $('#citys_id').html('<option value="">Loading...</option>'); 
                },
            success:function(response) {
                $("#citys_id").html('');
                $("#citys_id").append('<option value="">Select City</option>');
                $.each(response , function(index, item) {
                    $("#citys_id").append('<option value="'+item.id+'">'+item.name+'</option>');
                });
            }
        });
    });
</script>