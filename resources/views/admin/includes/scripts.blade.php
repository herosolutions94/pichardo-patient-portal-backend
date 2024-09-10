<!-- login js-->
<!-- Plugin used-->
<script>
    let base_url = '<?= url('/') ?>';
</script>
<script src="{{ asset('admin/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('admin/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/datatables/datatables.min.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.3.0/classic/ckeditor.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>


<script src="{{ asset('admin/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('admin/js/productDetail.js') }}"></script>
<script src="{{ asset('admin/js/custom.js') }}"></script>
<script src="{{ asset('admin/js/tooltip.js') }}"></script>

@if(isset($enable_listing_script) && $enable_listing_script==true)
<script>
    $(document).ready(function() {
        $(document).on('change', '.listing_featured input', function(e) {
            var isChecked = $(this).is(':checked'); // Correctly fetch the checkbox state
            var listingId = $(this).data('id'); // Correctly fetch the data-id
    
            console.log(isChecked, listingId); // Debugging info
    
            $.ajax({
                url: '{{ url("admin/listings/mark_as_featured") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Include CSRF token for security
                    id: listingId,
                    featured: isChecked ? 1 : 0
                },
                success: function(response) {
                    console.log(response); // Debugging info
                    // if (response.status) {
                    //     // Handle success scenario
                    // } else {
                    //     alert('Error: ' + response.message);
                    // }
                },
                error: function(xhr) {
                    alert('An error occurred. Please try again later.');
                }
            });
        });
    });
    </script>
    
@endif
